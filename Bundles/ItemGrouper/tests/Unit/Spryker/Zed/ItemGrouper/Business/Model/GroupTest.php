<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ItemGrouper\Business\Model;

use Generated\Shared\Transfer\GroupableContainerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ItemGrouper\Business\Model\Group;

class GroupTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIsGroupedBySku()
    {
        $groupAbleContainer = $this->getGroupableContainer();

        $group = new Group(0, true);
        $groupedItems = (array)$group->groupByKey($groupAbleContainer)->getItems();

        $this->assertCount(2, $groupedItems);

        $firstItem = array_shift($groupedItems);
        $this->assertEquals('A', $firstItem->getGroupKey());
        $this->assertEquals(2, $firstItem->getQuantity());

        $secondItem = array_shift($groupedItems);
        $this->assertEquals('B', $secondItem->getGroupKey());
        $this->assertEquals(1, $secondItem->getQuantity());
    }

    /**
     * @return void
     */
    public function testIsThresholdValidatorApplied()
    {
        $groupAbleContainer = $this->getGroupableContainer();

        $group = new Group(1, true);
        $groupedItems = (array)$group->groupByKey($groupAbleContainer)->getItems();

        $this->assertCount(2, $groupedItems);

        $firstItem = array_shift($groupedItems);
        $this->assertEquals('A', $firstItem->getGroupKey());
        $this->assertEquals(2, $firstItem->getQuantity());

        $secondItem = array_shift($groupedItems);
        $this->assertEquals('B', $secondItem->getGroupKey());
        $this->assertEquals(1, $secondItem->getQuantity());
    }

    /**
     * @return \Generated\Shared\Transfer\GroupableContainerTransfer
     */
    protected function getGroupableContainer()
    {
        $cartItems = [];
        $cartItem = new ItemTransfer();
        $cartItem->setGroupKey('A');
        $cartItem->setQuantity(1);
        $cartItems[] = $cartItem;

        $cartItem = new ItemTransfer();
        $cartItem->setGroupKey('A');
        $cartItem->setQuantity(1);
        $cartItems[] = $cartItem;

        $cartItem = new ItemTransfer();
        $cartItem->setGroupKey('B');
        $cartItem->setQuantity(1);
        $cartItems[] = $cartItem;

        $groupAbleContainer = new GroupableContainerTransfer();
        $groupAbleContainer->setItems(new \ArrayObject($cartItems));

        return $groupAbleContainer;
    }

}
