<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Persistence;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\StoreStorageCriteriaTransfer;

interface StoreStorageRepositoryInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getStoreStorageSynchronizationDataTransfers(StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer): array;

    /**
     * @param array<int> $ids
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getStoreListStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $ids): array;

    /**
     * @return array<string>
     */
    public function getStoreNames(): array;
}
