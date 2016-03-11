<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Refund;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Refund\Dependency\Facade\RefundToOmsBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToPayoneBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesAggregatorBridge;
use Spryker\Zed\Refund\Dependency\Facade\RefundToSalesSplitBridge;

class RefundDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_REFUND = 'QUERY_CONTAINER_REFUND';
    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_PAYONE = 'payone facade';
    const FACADE_SALES_AGGREGATOR  = 'FACADE_SALES_AGGREGATOR';
    const FACADE_SALES_SPLIT = 'FACADE_SALES_SPLIT';
    const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::FACADE_OMS] = function (Container $container) {
            return new RefundToOmsBridge($container->getLocator()->oms()->facade());
        };

        $container[static::FACADE_PAYONE] = function (Container $container) {
            return new RefundToPayoneBridge($container->getLocator()->payone()->facade());
        };

        $container[static::QUERY_CONTAINER_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        $container[self::FACADE_SALES_SPLIT] = function (Container $container) {
            return new RefundToSalesSplitBridge($container->getLocator()->salesSplit()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_REFUND] = function (Container $container) {
            return $container->getLocator()->refund()->queryContainer();
        };

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        $container[self::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new RefundToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
        };

        $container[self::SERVICE_DATE_FORMATTER] = function () {
            return (new Pimple())->getApplication()['dateFormatter'];
        };

        return $container;
    }

}
