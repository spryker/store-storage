<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShareExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ResourceShareTransfer;

interface ResourceShareActivatorStrategyPluginInterface
{
    /**
     * Specification:
     * - Executes additional actions, based on resource data and resource type values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return void
     */
    public function execute(ResourceShareTransfer $resourceShareTransfer): void;

    /**
     * Specification:
     * - Determines if strategy plugin requires the customer to be logged in.
     *
     * @api
     *
     * @return bool
     */
    public function isLoginRequired(): bool;

    /**
     * Specification:
     * - Checks if strategy plugin is applicable, based on resource data and resource type values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool;
}
