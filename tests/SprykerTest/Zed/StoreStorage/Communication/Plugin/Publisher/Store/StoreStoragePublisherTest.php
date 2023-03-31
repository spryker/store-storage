<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreStorage\Communication\Plugin\Publisher\Store;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface;
use Spryker\Shared\StoreStorage\StoreStorageConfig;
use Spryker\Zed\StoreStorage\Communication\Plugin\Publisher\Store\StoreWritePublisherPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group StoreStorage
 * @group Communication
 * @group Plugin
 * @group Publisher
 * @group Store
 * @group StoreStoragePublisherTest
 * Add your own group annotations below this line
 */
class StoreStoragePublisherTest extends Unit
{
    /**
     * @var string
     */
    protected const DATA_KEY_STORE_NAME = 'name';

    /**
     * @var string
     */
    protected const DATA_KEY_ID_STORE = 'id_store';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\StoreStorage\StoreStorageCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testStoreWritePublisherStoreData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME]);

        $this->tester->setDependency(StoreDependencyProvider::STORE, $this->getStoreToStoreInterface());
        $this->tester->setDependency(StoreDependencyProvider::SERVICE_STORE, $storeTransfer);

        $eventTransfers = [
            (new EventEntityTransfer())->setId($storeTransfer->getIdStore()),
        ];

        // Act
        (new StoreWritePublisherPlugin())->handleBulk($eventTransfers, StoreStorageConfig::ENTITY_SPY_STORE_CREATE);

        // Assert
        $storeStorageEntity = $this->tester->findStoreStorageEntityByIdStore($storeTransfer->getIdStore());
        $this->assertNotNull($storeStorageEntity);
        $this->assertArrayHasKey(static::DATA_KEY_ID_STORE, $storeStorageEntity->getData());
        $this->assertArrayHasKey(static::DATA_KEY_STORE_NAME, $storeStorageEntity->getData());
        $this->assertSame($storeTransfer->getIdStore(), $storeStorageEntity->getData()[static::DATA_KEY_ID_STORE]);
        $this->assertSame($storeTransfer->getName(), $storeStorageEntity->getData()[static::DATA_KEY_STORE_NAME]);
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected function getStoreToStoreInterface(): StoreToStoreInterface
    {
        return $this->getMockBuilder(StoreToStoreInterface::class)->getMock();
    }
}
