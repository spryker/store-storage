<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CartCreator implements CartCreatorInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     */
    public function __construct(
        CartsResourceMapperInterface $cartsResourceMapper,
        CartsRestApiClientInterface $cartsRestApiClient,
        CartRestResponseBuilderInterface $cartRestResponseBuilder
    ) {
        $this->cartsResourceMapper = $cartsResourceMapper;
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function create(
        RestRequestInterface $restRequest,
        RestCartsAttributesTransfer $restCartsAttributesTransfer
    ): RestResponseInterface {
        $quoteTransfer = $this->createQuoteTransfer($restCartsAttributesTransfer, $restRequest);
        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        $quoteResponseTransfer = $this->cartsRestApiClient->createQuote($restQuoteRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedCreatingCartError($quoteResponseTransfer);
        }

        $restResource = $this->cartsResourceMapper->mapCartsResource(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );

        return $this->cartRestResponseBuilder->createCartRestResponse($restResource);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(
        RestCartsAttributesTransfer $restCartsAttributesTransfer,
        RestRequestInterface $restRequest
    ): QuoteTransfer {
        $currencyTransfer = $this->getCurrencyTransfer($restCartsAttributesTransfer);
        $customerTransfer = $this->getCustomerTransfer($restRequest);
        $storeTransfer = $this->getStoreTransfer($restCartsAttributesTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->fromArray($restCartsAttributesTransfer->toArray(), true)
            ->setCurrency($currencyTransfer)
            ->setCustomer($customerTransfer)
            ->setPriceMode($restCartsAttributesTransfer->getPriceMode())
            ->setStore($storeTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer(RestCartsAttributesTransfer $restCartsAttributesTransfer): CurrencyTransfer
    {
        return (new CurrencyTransfer())
            ->setCode($restCartsAttributesTransfer->getCurrency());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer(RestRequestInterface $restRequest): CustomerTransfer
    {
        return (new CustomerTransfer())->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(RestCartsAttributesTransfer $restCartsAttributesTransfer): StoreTransfer
    {
        return (new StoreTransfer())
            ->setName($restCartsAttributesTransfer->getStore());
    }
}
