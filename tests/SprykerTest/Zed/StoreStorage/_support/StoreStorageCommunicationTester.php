<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreStorage;

use Codeception\Actor;
use Orm\Zed\StoreStorage\Persistence\SpyStoreListStorage;
use Orm\Zed\StoreStorage\Persistence\SpyStoreListStorageQuery;
use Orm\Zed\StoreStorage\Persistence\SpyStoreStorage;
use Orm\Zed\StoreStorage\Persistence\SpyStoreStorageQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class StoreStorageCommunicationTester extends Actor
{
    use _generated\StoreStorageCommunicationTesterActions;

    /**
     * @var string
     */
    public const LOCALE_DE = 'de_DE';

    public function findStoreStorageEntityByIdStore(int $idStore): ?SpyStoreStorage
    {
        return $this->createStoreStoragePropelQuery()->findOneByFkStore($idStore);
    }

    public function findStoreListStorageEntity(): ?SpyStoreListStorage
    {
        return $this->createStoreListStoragePropelQuery()->findOne();
    }

    public function haveStoreStorageEntity(int $idStore, string $storeName): SpyStoreStorage
    {
        $storeStorageEntity = $this->createStoreStoragePropelQuery()
            ->filterByFkStore($idStore)
            ->findOneOrCreate();

        $storeStorageEntity
            ->setStoreName($storeName)
            ->setData(['id_store' => $idStore, 'name' => $storeName])
            ->save();

        return $storeStorageEntity;
    }

    protected function createStoreStoragePropelQuery(): SpyStoreStorageQuery
    {
        return SpyStoreStorageQuery::create();
    }

    protected function createStoreListStoragePropelQuery(): SpyStoreListStorageQuery
    {
        return SpyStoreListStorageQuery::create();
    }
}
