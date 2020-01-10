<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Writer;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $merchantProductOfferStorageEntityManager;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface
     */
    protected $productOfferStorageDeleter;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     * @param \Spryker\Zed\MerchantProductOfferStorage\Business\Deleter\ProductOfferStorageDeleterInterface $productOfferStorageDeleter
     */
    public function __construct(
        MerchantProductOfferStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager,
        ProductOfferStorageDeleterInterface $productOfferStorageDeleter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
        $this->productOfferStorageDeleter = $productOfferStorageDeleter;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeProductOfferStorageCollectionByProductOfferReferenceEvents(array $eventTransfers): void
    {
        $productOfferReferences = $this->eventBehaviorFacade->getEventTransfersAdditionalValues($eventTransfers, SpyProductOfferTableMap::COL_PRODUCT_OFFER_REFERENCE);

        if (!$productOfferReferences) {
            return;
        }

        $this->writeProductOfferStorageCollectionByProductOfferReferences($productOfferReferences);
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    protected function writeProductOfferStorageCollectionByProductOfferReferences(array $productOfferReferences): void
    {
        $productOfferCriteriaFilterTransfer = $this->createProductOfferCriteriaFilterTransfer($productOfferReferences);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        $storedProductOfferReferences = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $this->merchantProductOfferStorageEntityManager->saveProductOfferStorage($productOfferTransfer);
            $storedProductOfferReferences[] = $productOfferTransfer->getProductOfferReference();
        }

        $productOfferReferencesToDelete = array_diff($productOfferReferences, $storedProductOfferReferences);
        $this->productOfferStorageDeleter->deleteProductOfferStorageCollectionByProductOfferReferences($productOfferReferencesToDelete);
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function createProductOfferCriteriaFilterTransfer(array $productOfferReferences): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReferences($productOfferReferences)
            ->setIsActive(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
    }
}