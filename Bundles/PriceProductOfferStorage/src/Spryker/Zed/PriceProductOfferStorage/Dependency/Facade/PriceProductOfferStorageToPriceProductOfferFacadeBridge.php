<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferStorage\Dependency\Facade;

use ArrayObject;
use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;

class PriceProductOfferStorageToPriceProductOfferFacadeBridge implements PriceProductOfferStorageToPriceProductOfferFacadeInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface
     */
    protected $priceProductOfferFacade;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface $priceProductOfferFacade
     */
    public function __construct($priceProductOfferFacade)
    {
        $this->priceProductOfferFacade = $priceProductOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function getProductOfferPrices(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): ArrayObject {
        return $this->priceProductOfferFacade->getProductOfferPrices($priceProductOfferCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer
     */
    public function getPriceProductOfferCollection(
        PriceProductOfferCriteriaTransfer $priceProductOfferCriteriaTransfer
    ): PriceProductOfferCollectionTransfer {
        return $this->priceProductOfferFacade->getPriceProductOfferCollection($priceProductOfferCriteriaTransfer);
    }
}
