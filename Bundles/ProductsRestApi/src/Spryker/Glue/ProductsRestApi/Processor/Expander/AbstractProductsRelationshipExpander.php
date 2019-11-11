<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class AbstractProductsRelationshipExpander implements AbstractProductsRelationshipExpanderInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface
     */
    protected $abstractProductsReader;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Processor\AbstractProducts\AbstractProductsReaderInterface $abstractProductsReader
     */
    public function __construct(AbstractProductsReaderInterface $abstractProductsReader)
    {
        $this->abstractProductsReader = $abstractProductsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsBySkus(array $resources, RestRequestInterface $restRequest): array
    {
        $abstractSkus = [];
        foreach ($resources as $resource) {
            $productAbstractSku = $this->findSku($resource->getAttributes());
            if (!$productAbstractSku) {
                continue;
            }

            $abstractSkus[] = $productAbstractSku;
        }

        if (!$abstractSkus) {
            return [];
        }

        foreach ($resources as $resource) {
            $abstractProductsResources = $this->abstractProductsReader->getProductAbstractsBySkus($abstractSkus, $restRequest);
            if ($abstractProductsResources) {
                $this->addRelationships($abstractProductsResources, $resource);
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $abstractProductsResources
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return void
     */
    protected function addRelationships(array $abstractProductsResources, RestResourceInterface $resource): void
    {
        foreach ($abstractProductsResources as $abstractProductsResource) {
            $resource->addRelationship($abstractProductsResource);
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $attributes
     *
     * @return string|null
     */
    protected function findSku(?AbstractTransfer $attributes): ?string
    {
        if ($attributes
            && $attributes->offsetExists(static::KEY_SKU)
            && $attributes->offsetGet(static::KEY_SKU)
        ) {
            return $attributes->offsetGet(static::KEY_SKU);
        }

        return null;
    }
}
