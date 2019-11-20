<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Business\Search\Writer;

interface ProductReviewSearchWriterInterface
{
    /**
     * @param array $productReviewIds
     *
     * @return void
     */
    public function publish(array $productReviewIds): void;

    /**
     * @param array $productReviewIds
     *
     * @return void
     */
    public function unpublish(array $productReviewIds): void;
}
