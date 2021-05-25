<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeBridge;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollection;
use Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollection;

/**
 * @method \Spryker\Zed\Payment\PaymentConfig getConfig()
 */
class PaymentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_STORE = 'FACADE_STORE';

    public const PAYMENT_METHOD_FILTER_PLUGINS = 'PAYMENT_METHOD_FILTER_PLUGINS';

    /**
     * @deprecated Use {@link \Spryker\Zed\Checkout\CheckoutDependencyProvider::CHECKOUT_POST_HOOKS},
     * {@link \Spryker\Zed\Checkout\CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS},
     * {@link \Spryker\Zed\Checkout\CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS} instead.
     */
    public const CHECKOUT_PLUGINS = 'checkout plugins';

    /**
     * @deprecated Use {@link \Spryker\Zed\Checkout\CheckoutDependencyProvider::CHECKOUT_PRE_CONDITIONS} instead.
     */
    public const CHECKOUT_PRE_CHECK_PLUGINS = 'pre check';

    /**
     * @deprecated Use {@link \Spryker\Zed\Checkout\CheckoutDependencyProvider::CHECKOUT_ORDER_SAVERS} instead.
     */
    public const CHECKOUT_ORDER_SAVER_PLUGINS = 'order saver';

    /**
     * @deprecated Use {@link \Spryker\Zed\Checkout\CheckoutDependencyProvider::CHECKOUT_POST_HOOKS} instead.
     */
    public const CHECKOUT_POST_SAVE_PLUGINS = 'post save';

    /**
     * @deprecated Use {@link \Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider::SALES_PAYMENT_EXPANDER_PLUGINS} instead.
     */
    public const PAYMENT_HYDRATION_PLUGINS = 'payment hydration plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addStoreFacade($container);
        $container = $this->addPaymentMethodFilterPlugins($container);

        $container = $this->addCheckoutPlugins($container);
        $container = $this->addPaymentHydrationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new PaymentToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentMethodFilterPlugins(Container $container): Container
    {
        $container->set(static::PAYMENT_METHOD_FILTER_PLUGINS, function (Container $container) {
            return $this->getPaymentMethodFilterPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentMethodFilterPluginInterface[]
     */
    protected function getPaymentMethodFilterPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCheckoutPlugins(Container $container)
    {
        $container->set(static::CHECKOUT_PLUGINS, function (Container $container) {
            return new CheckoutPluginCollection();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentHydrationPlugins(Container $container)
    {
        $container->set(static::PAYMENT_HYDRATION_PLUGINS, function () {
            return $this->getPaymentHydrationPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginCollectionInterface
     */
    protected function getPaymentHydrationPlugins()
    {
        return new PaymentHydratorPluginCollection();
    }
}
