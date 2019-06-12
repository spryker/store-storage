<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CmsPageSearch;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class CmsPageSearchConstants
{
    /**
     * Specification:
     * - Queue name as used for processing cms block messages
     *
     * @api
     */
    public const CMS_SYNC_SEARCH_QUEUE = 'sync.search.cms';

    /**
     * Specification:
     * - Queue name as used for error cms block messages
     *
     * @api
     */
    public const CMS_SYNC_SEARCH_ERROR_QUEUE = 'sync.search.cms.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const CMS_PAGE_RESOURCE_NAME = 'cms_page_search';

    /**
     * Specification:
     * - Enables/disables search synchronization.
     *
     * @api
     *
     * @uses \Spryker\Shared\Synchronization\SynchronizationConstants::SEARCH_SYNC_ENABLED
     */
    public const SEARCH_SYNC_ENABLED = 'SYNCHRONIZATION:SEARCH_SYNC_ENABLED';
}
