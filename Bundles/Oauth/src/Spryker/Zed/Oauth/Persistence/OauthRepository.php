<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\SpyOauthClientEntityTransfer;
use Generated\Shared\Transfer\SpyOauthScopeEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Oauth\Persistence\OauthPersistenceFactory getFactory()
 */
class OauthRepository extends AbstractRepository implements OauthRepositoryInterface
{
    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\SpyOauthClientEntityTransfer|null
     */
    public function findClientByIdentifier(string $identifier): ?SpyOauthClientEntityTransfer
    {
        $query = $this->getFactory()
            ->createOauthClientQuery()
            ->filterByIdentifier($identifier);

        return $this->buildQueryFromCriteria($query)->findOne();
    }

    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\SpyOauthScopeEntityTransfer|null
     */
    public function findScopeByIdentifier(string $identifier): ?SpyOauthScopeEntityTransfer
    {
        $query = $this->getFactory()
            ->createScopeQuery()
            ->filterByIdentifier($identifier);

        return $this->buildQueryFromCriteria($query)->findOne();
    }

    /**
     * @param string[] $customerScopes
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopesByIdentifiers(array $customerScopes): array
    {
        $spyOauthScopeEntities = $this->getFactory()
            ->createScopeQuery()
            ->filterByIdentifier_In($customerScopes)
            ->find();

        $oauthScopeTransfers = [];

        if ($spyOauthScopeEntities->count() === 0) {
            return $oauthScopeTransfers;
        }

        foreach ($spyOauthScopeEntities as $spyOauthScopeEntity) {
            $oauthScopeTransfer = new OauthScopeTransfer();
            $oauthScopeTransfers[] = $oauthScopeTransfer->fromArray(
                $spyOauthScopeEntity->toArray(),
                true
            );
        }

        return $oauthScopeTransfers;
    }

    /**
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findRefreshTokenByIdentifier(string $identifier): ?OauthRefreshTokenTransfer
    {
        $authRefreshTokenEntity = $this->getFactory()
            ->createRefreshTokenQuery()
            ->findOneByIdentifier($identifier);

        if (!$authRefreshTokenEntity) {
            return null;
        }

        return $authRefreshTokenTransfer = $this->getFactory()
            ->createOauthRefreshTokenMapper()
            ->mapOauthRefreshTokenEntityToMapOauthRefreshTokenTransfer($authRefreshTokenEntity, new OauthRefreshTokenTransfer());
    }
}
