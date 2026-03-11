<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StoreStorage\Reader;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface;
use Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface;
use Spryker\Shared\StoreStorage\StoreStorageConfig;

class StoreListReader
{
    /**
     * @var array<string>|null
     */
    protected static ?array $storeListCache = null;

    /**
     * @var \Spryker\Client\StoreStorage\Dependency\Service\StoreStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\StoreStorage\Dependency\Client\StoreStorageToStorageClientInterface
     */
    protected $storageClient;

    public function __construct(
        StoreStorageToSynchronizationServiceInterface $synchronizationService,
        StoreStorageToStorageClientInterface $storageClient
    ) {
        $this->synchronizationService = $synchronizationService;
        $this->storageClient = $storageClient;
    }

    /**
     * @return array<string>
     */
    public function getStoresNames(): array
    {
        if (static::$storeListCache === null) {
            $storeData = $this->storageClient->get(
                $this->generateKey(),
            );

            static::$storeListCache = $storeData['stores'] ?? [];
        }

        return static::$storeListCache;
    }

    protected function generateKey(): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        return $this->synchronizationService
            ->getStorageKeyBuilder(StoreStorageConfig::STORE_LIST_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);
    }
}
