<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector\Business\StrategyResolver;

use Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface;

/**
 * @deprecated Will be removed in next major release.
 */
interface CartExpanderStrategyResolverInterface
{
    /**
     * @param iterable|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Spryker\Zed\ShipmentCartConnector\Business\Cart\ShipmentCartExpanderInterface
     */
    public function resolve(iterable $itemTransfers): ShipmentCartExpanderInterface;
}
