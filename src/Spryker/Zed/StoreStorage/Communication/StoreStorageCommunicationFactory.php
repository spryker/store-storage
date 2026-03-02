<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToStoreFacadeInterface;
use Spryker\Zed\StoreStorage\Dependency\Facade\StoreStorageToSynchronizationFacadeInterface;
use Spryker\Zed\StoreStorage\StoreStorageDependencyProvider;

/**
 * @method \Spryker\Zed\StoreStorage\StoreStorageConfig getConfig()
 * @method \Spryker\Zed\StoreStorage\Business\StoreStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\StoreStorage\Persistence\StoreStorageRepositoryInterface getRepository()
 */
class StoreStorageCommunicationFactory extends AbstractCommunicationFactory
{
    public function getStoreFacade(): StoreStorageToStoreFacadeInterface
    {
        return $this->getProvidedDependency(StoreStorageDependencyProvider::FACADE_STORE);
    }

    public function getSynchronizationFacade(): StoreStorageToSynchronizationFacadeInterface
    {
        return $this->getProvidedDependency(StoreStorageDependencyProvider::FACADE_SYNCHRONIZATION);
    }
}
