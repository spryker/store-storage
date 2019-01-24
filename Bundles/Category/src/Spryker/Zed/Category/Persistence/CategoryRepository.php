<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\Category\Persistence\CategoryPersistenceFactory getFactory()
 */
class CategoryRepository extends AbstractRepository implements CategoryRepositoryInterface
{
    public const NODE_PATH_GLUE = '/';
    public const CATEGORY_NODE_PATH_GLUE = ' / ';
    public const EXCLUDE_NODE_PATH_ROOT = true;
    public const NODE_PATH_NULL_DEPTH = null;
    public const NODE_PATH_ZERO_DEPTH = 0;
    public const IS_NOT_ROOT_NODE = 0;
    protected const COL_CATEGORY_NAME = 'name';

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getAllCategoryCollection(LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        $spyCategories = SpyCategoryQuery::create()
            ->joinWithAttribute()
            ->leftJoinNode()
            ->addAnd(
                SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                $localeTransfer->getIdLocale(),
                Criteria::EQUAL
            )
            ->find();

        return $this->getFactory()
            ->createCategoryMapper()
            ->mapCategoryCollection($spyCategories, new CategoryCollectionTransfer());
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    public function getNodePath(int $idCategoryNode, LocaleTransfer $localeTransfer)
    {
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery $categoryPathQuery */
        $categoryPathQuery = $this->queryNodePath($idCategoryNode, $localeTransfer->getIdLocale())
            ->clearSelectColumns()
            ->addSelectColumn(static::COL_CATEGORY_NAME);

        /** @var string[] $pathTokens */
        $pathTokens = $categoryPathQuery->find();

        return implode('/', $pathTokens);
    }

    /**
     * @param int $idNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function queryNodePath(
        $idNode,
        $idLocale
    ): SpyCategoryNodeQuery {
        $depth = 0;
        $nodeQuery = SpyCategoryNodeQuery::create();

        $nodeQuery
            ->useClosureTableQuery()
                ->orderByFkCategoryNodeDescendant(Criteria::DESC)
                ->orderByDepth(Criteria::DESC)
                ->filterByFkCategoryNodeDescendant($idNode)
                ->filterByDepth($depth, Criteria::NOT_EQUAL)
            ->endUse()
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse();

        $nodeQuery->setFormatter(new PropelArraySetFormatter());

        return $nodeQuery;
    }

    /**
     * @param string $nodeName
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return bool
     */
    public function checkSameLevelCategoryByNameExists(string $nodeName, CategoryTransfer $categoryTransfer): bool
    {
        return $this->getFactory()->createCategoryNodeQuery()
            ->setIgnoreCase(true)
            ->filterByFkParentCategoryNode($categoryTransfer->getParentCategoryNode()->getIdCategoryNode())
            ->useCategoryQuery()
                ->filterByIdCategory($categoryTransfer->getIdCategory(), Criteria::NOT_EQUAL)
                ->useAttributeQuery()
                    ->filterByName($nodeName)
                ->endUse()
            ->endUse()
            ->exists();
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer|null
     */
    public function findCategoryById(int $idCategory): ?CategoryTransfer
    {
        $spyCategoryEntity = $this->getFactory()
            ->createCategoryQuery()
            ->leftJoinWithNode()
            ->useNodeQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkCategory($idCategory)
            ->endUse()
            ->leftJoinWithAttribute()
            ->useAttributeQuery(null, Criteria::LEFT_JOIN)
                ->filterByFkCategory($idCategory)
            ->endUse()
            ->findByIdCategory($idCategory)
            ->getFirst();

        if ($spyCategoryEntity === null) {
            return null;
        }

        return $this->getFactory()->createCategoryMapper()->mapCategoryWithRelations(
            $spyCategoryEntity,
            new CategoryTransfer()
        );
    }
}
