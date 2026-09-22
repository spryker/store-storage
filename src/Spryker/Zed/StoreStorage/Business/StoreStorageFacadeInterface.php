<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Business;

use Generated\Shared\Transfer\StoreStorageCriteriaTransfer;

interface StoreStorageFacadeInterface
{
    /**
     * Specification:
     * - Queries all stores with the given $eventTransfers by StoreEvents.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     */
    public function writeCollectionByStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Queries all locale stores with the given $eventTransfers by LocaleStoreEvents.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     */
    public function writeCollectionByLocaleStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Queries all currency stores with the given $eventTransfers by CurrencyStoreEvents.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     */
    public function writeCollectionByCurrencyStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Queries all country stores with the given $eventTransfers by CountryStoreEvents.
     * - Stores data as json encoded to storage table.
     * - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     */
    public function writeCollectionByCountryStoreEvents(array $eventTransfers): void;

    /**
     * Specification:
     * - Returns SynchronizationData transfers for StoreStorage entities based on filter and ids.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getStoreStorageSynchronizationDataTransfers(StoreStorageCriteriaTransfer $storeStorageCriteriaTransfer): array;
}
