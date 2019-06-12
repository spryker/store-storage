<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryImageStorage;

use Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer;
use Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer;

interface CategoryImageStorageClientInterface
{
    /**
     * Specification:
     * - Get category image set collection transfer object from storage for the specified category id and locale combination.
     *
     * @api
     *
     * @deprecated Use `Spryker\Client\CategoryImageStorage\CategoryImageStorageClientInterface::findCategoryImageStorageItem()` instead.
     *
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetCollectionStorageTransfer|null
     */
    public function findCategoryImageSetCollectionStorage(int $idCategory, string $localeName): ?CategoryImageSetCollectionStorageTransfer;

    /**
     * Specification:
     * - Get category image set item transfer object from storage for the specified category id and locale combination.
     *
     * @api
     *
     * @param int $idCategory
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryImageStorageItemDataTransfer|null
     */
    public function findCategoryImageStorageItemData(int $idCategory, string $localeName): ?CategoryImageStorageItemDataTransfer;
}
