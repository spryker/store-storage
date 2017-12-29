<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CategoryStorage\CategoryStorageFactory getFactory()
 */
class CategoryStorageClient extends AbstractClient implements CategoryStorageClientInterface
{

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getCategories($locale)
    {
        return $this->getFactory()
            ->createCategoryTreeStorageReader()
            ->getCategories($locale);
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return CategoryNodeStorageTransfer
     */
    public function getCategoryNodeById($idCategoryNode, $localeName)
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getCategoryNodeById($idCategoryNode, $localeName);
    }
}
