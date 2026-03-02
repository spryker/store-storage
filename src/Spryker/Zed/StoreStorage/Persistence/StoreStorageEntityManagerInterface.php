<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreStorage\Persistence;

use Generated\Shared\Transfer\StoreStorageTransfer;

interface StoreStorageEntityManagerInterface
{
    public function updateStoreStorage(StoreStorageTransfer $storeStorageTransfer): void;

    /**
     * @param array<string> $storeNames
     *
     * @return void
     */
    public function updateStoreList(array $storeNames): void;
}
