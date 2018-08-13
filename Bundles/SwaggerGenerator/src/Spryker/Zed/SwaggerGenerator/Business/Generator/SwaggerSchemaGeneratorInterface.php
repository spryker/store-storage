<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator\Business\Generator;

interface SwaggerSchemaGeneratorInterface
{
    /**
     * @param string $transferName
     *
     * @return void
     */
    public function addSchemaFromTransferClassName(string $transferName): void;

    /**
     * @return string
     */
    public function getLastAddedSchemaKey(): string;

    /**
     * @return array
     */
    public function getSchemas(): array;
}
