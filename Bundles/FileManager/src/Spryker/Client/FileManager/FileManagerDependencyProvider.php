<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FileManager;

use Spryker\Client\FileManager\Dependency\Client\FileManagerToLocaleClientBridge;
use Spryker\Client\FileManager\Dependency\Client\FileManagerToStorageClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\Store;

class FileManagerDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[static::CLIENT_STORAGE] = function (Container $container) {
            return new FileManagerToStorageClientBridge(
                $container->getLocator()->storage()->client()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container[static::CLIENT_LOCALE] = function (Container $container) {
            return new FileManagerToLocaleClientBridge(
                $container->getLocator()->locale()->client()
            );
        };

        return $container;
    }
}
