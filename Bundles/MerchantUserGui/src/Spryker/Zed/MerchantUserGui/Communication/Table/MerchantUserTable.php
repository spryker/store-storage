<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserGui\Communication\Table;

use Orm\Zed\MerchantUser\Persistence\Map\SpyMerchantUserTableMap;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;
use Orm\Zed\User\Persistence\Map\SpyUserTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantUserGui\Communication\Controller\DeleteMerchantUserController;
use Spryker\Zed\MerchantUserGui\Communication\Controller\EditMerchantUserController;
use Spryker\Zed\MerchantUserGui\Communication\Controller\IndexController;
use Spryker\Zed\MerchantUserGui\Communication\Controller\MerchantUserStatusController;
use Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToRouterFacadeInterface;
use Spryker\Zed\Router\Business\Router\ChainRouter;

class MerchantUserTable extends AbstractTable
{
    protected const MERCHANT_USER_STATUS = 'status';
    protected const MERCHANT_USER_FIRST_NAME = 'first_name';
    protected const MERCHANT_USER_LAST_NAME = 'last_name';
    protected const MERCHANT_USER_NAME = 'username';
    protected const MERCHANT_USER_ID = 'id';
    protected const ACTIONS = 'actions';

    /**
     * @var array
     */
    protected const STATUS_CLASS_LABEL_MAPPING = [
        SpyUserTableMap::COL_STATUS_ACTIVE => [
            'title' => 'Active',
            'class' => 'label-info',
            'status_change_action_title' => 'Activate',
            'status_change_action_class' => 'btn-create',
        ],
        SpyUserTableMap::COL_STATUS_BLOCKED => [
            'title' => 'Blocked',
            'class' => 'label-danger',
            'status_change_action_title' => 'Deny Access',
            'status_change_action_class' => 'btn-remove',
        ],
        SpyUserTableMap::COL_STATUS_DELETED => [
            'title' => 'Deleted',
            'class' => 'label-default',
            'status_change_action_title' => 'Delete',
            'status_change_action_class' => 'btn-delete',
        ],
    ];

    /**
     * @var \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    protected $merchantUserQuery;

    /**
     * @var \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToRouterFacadeInterface
     */
    protected $routerFacade;

    /**
     * @var string
     */
    protected $baseUrl = '/merchant-user-gui/index';

    /**
     * @var int
     */
    protected $idMerchant;

    /**
     * @param \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery $merchantUserQuery
     * @param \Spryker\Zed\MerchantUserGui\Dependency\Facade\MerchantUserGuiToRouterFacadeInterface $routerFacade
     * @param int $idMerchant
     */
    public function __construct(
        SpyMerchantUserQuery $merchantUserQuery,
        MerchantUserGuiToRouterFacadeInterface $routerFacade,
        int $idMerchant
    ) {
        $this->merchantUserQuery = $merchantUserQuery;
        $this->routerFacade = $routerFacade;
        $this->idMerchant = $idMerchant;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::MERCHANT_USER_ID => 'Merchant User ID',
            static::MERCHANT_USER_NAME => 'E-mail',
            static::MERCHANT_USER_FIRST_NAME => 'First Name',
            static::MERCHANT_USER_LAST_NAME => 'Last Name',
            static::MERCHANT_USER_STATUS => 'Status',
            static::ACTIONS => 'Actions',
        ]);

        $config->setSortable([
            static::MERCHANT_USER_NAME,
            static::MERCHANT_USER_FIRST_NAME,
            static::MERCHANT_USER_LAST_NAME,
            static::MERCHANT_USER_STATUS,
        ]);

        $config->setRawColumns([
            static::ACTIONS,
            static::MERCHANT_USER_STATUS,
        ]);

        $config->setDefaultSortField(static::MERCHANT_USER_ID, TableConfiguration::SORT_DESC);

        $config->setSearchable([
            static::MERCHANT_USER_NAME,
            static::MERCHANT_USER_STATUS,
        ]);

        $config->setUrl(sprintf('table?%s=%d', IndexController::PARAM_MERCHANT_ID, $this->idMerchant));

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->merchantUserQuery
            ->innerJoinSpyUser()
            ->filterByFkMerchant($this->idMerchant)
            ->withColumn(SpyUserTableMap::COL_STATUS, static::MERCHANT_USER_STATUS)
            ->withColumn(SpyUserTableMap::COL_FIRST_NAME, static::MERCHANT_USER_FIRST_NAME)
            ->withColumn(SpyUserTableMap::COL_LAST_NAME, static::MERCHANT_USER_LAST_NAME)
            ->withColumn(SpyUserTableMap::COL_USERNAME, static::MERCHANT_USER_NAME);

        $queryResults = $this->runQuery($query, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $item[static::MERCHANT_USER_STATUS] = $this->convertStatusEnumKeyToValue($item[static::MERCHANT_USER_STATUS]);
            $results[] = [
                static::MERCHANT_USER_ID => $item[SpyMerchantUserTableMap::COL_ID_MERCHANT_USER],
                static::MERCHANT_USER_NAME => $item[static::MERCHANT_USER_NAME],
                static::MERCHANT_USER_FIRST_NAME => $item[static::MERCHANT_USER_FIRST_NAME],
                static::MERCHANT_USER_LAST_NAME => $item[static::MERCHANT_USER_LAST_NAME],
                static::MERCHANT_USER_STATUS => $this->createStatusLabel($item),
                static::ACTIONS => implode(
                    ' ',
                    $this->createActionColumn($item)
                ),

            ];
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function createActionColumn(array $item): array
    {
        $router = $this->routerFacade->getRouter();
        $buttons = [];

        $buttons[] = $this->generateEditButton(
            $router->generate(
                'merchant-user-gui:edit-merchant-user',
                [
                    EditMerchantUserController::PARAM_MERCHANT_USER_ID =>
                        $item[SpyMerchantUserTableMap::COL_ID_MERCHANT_USER],
                ]
            ),
            'Edit'
        );

        $buttons[] = $this->buildAvailableStatusButton($router, $item);

        $buttons[] = $this->generateRemoveButton(
            $router->generate(
                'merchant-user-gui:delete-merchant-user:confirm-delete',
                [
                    DeleteMerchantUserController::PARAM_MERCHANT_USER_ID =>
                        $item[SpyMerchantUserTableMap::COL_ID_MERCHANT_USER],
                ]
            ),
            'Delete'
        );

        return $buttons;
    }

    /**
     * @param array $merchantUserData
     *
     * @return string
     */
    protected function createStatusLabel(array $merchantUserData): string
    {
        $currentStatus = $merchantUserData[static::MERCHANT_USER_STATUS];

        if (!isset(static::STATUS_CLASS_LABEL_MAPPING[$currentStatus])) {
            return '';
        }

        return $this->generateLabel(
            static::STATUS_CLASS_LABEL_MAPPING[$currentStatus]['title'],
            static::STATUS_CLASS_LABEL_MAPPING[$currentStatus]['class']
        );
    }

    /**
     * @param \Spryker\Zed\Router\Business\Router\ChainRouter $router
     * @param array $item
     *
     * @return string
     */
    protected function buildAvailableStatusButton(ChainRouter $router, array $item): string
    {
        $availableStatus = $item[static::MERCHANT_USER_STATUS] === SpyUserTableMap::COL_STATUS_ACTIVE
            ? SpyUserTableMap::COL_STATUS_BLOCKED
            : SpyUserTableMap::COL_STATUS_ACTIVE;

        return $this->generateButton(
            $router->generate(
                'merchant-user-gui:merchant-user-status',
                [
                    MerchantUserStatusController::PARAM_MERCHANT_USER_ID =>
                        $item[SpyMerchantUserTableMap::COL_ID_MERCHANT_USER],
                    'status' => $availableStatus,
                ]
            ),
            static::STATUS_CLASS_LABEL_MAPPING[$availableStatus]['status_change_action_title'],
            [
                'icon' => 'fa fa-key',
                'class' => static::STATUS_CLASS_LABEL_MAPPING[$availableStatus]['status_change_action_class'],
            ]
        );
    }

    /**
     * @param int $status
     *
     * @return string
     */
    protected function convertStatusEnumKeyToValue(int $status): string
    {
        return SpyUserTableMap::getValueSet(SpyUserTableMap::COL_STATUS)[$status];
    }
}
