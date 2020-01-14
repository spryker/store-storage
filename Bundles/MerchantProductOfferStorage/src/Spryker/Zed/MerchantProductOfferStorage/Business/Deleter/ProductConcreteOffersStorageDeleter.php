<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Deleter;

use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductConcreteOffersStorageDeleter implements ProductConcreteOffersStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $merchantProductOfferStorageEntityManager;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteByProductSkuEvents(array $eventTransfers): void
    {
        $productSkus = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_CONCRETE_SKU);

        if (!$productSkus) {
            return;
        }

        $this->deleteByProductSkus($productSkus);
    }

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function deleteByProductSkus(array $productSkus): void
    {
        $this->merchantProductOfferStorageEntityManager
            ->deleteProductConcreteProductOffersStorageEntitiesByProductSkus($productSkus);
    }

    /**
     * @param string[] $productOfferReferences
     * @param string $storeName
     *
     * @return void
     */
    public function deleteByProductOfferReferencesAndStore(array $productOfferReferences, string $storeName): void
    {
        $this->merchantProductOfferStorageEntityManager->deleteProductOfferStorageEntitiesByProductOfferReferencesAndStore($productOfferReferences, $storeName);
    }
}
