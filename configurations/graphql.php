<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-10-26 14:03
 */
return [
    'schema'  => 'default',
    'schemas' => [
        'default' => [
            'mutation' => [
                'enableAddon'        => \Notadd\Foundation\Addon\GraphQL\Mutations\EnableMutation::class,
                'enableModule'       => \Notadd\Foundation\Module\GraphQL\Mutations\EnableMutation::class,
                'exportAddon'        => \Notadd\Foundation\Addon\GraphQL\Mutations\ExportMutation::class,
                'exportModule'       => \Notadd\Foundation\Module\GraphQL\Mutations\ExportMutation::class,
                'navigations'        => \Notadd\Foundation\Administration\GraphQL\Mutations\NavigationMutation::class,
                'clearCache'         => \Notadd\Foundation\Cache\GraphQL\Mutations\ClearMutation::class,
                'importAddon'        => \Notadd\Foundation\Addon\GraphQL\Mutations\ImportMutation::class,
                'importModule'       => \Notadd\Foundation\Module\GraphQL\Mutations\ImportMutation::class,
                'installAddon'       => \Notadd\Foundation\Addon\GraphQL\Mutations\InstallMutation::class,
                'installExtension'   => \Notadd\Foundation\Extension\GraphQL\Mutations\InstallMutation::class,
                'installModule'      => \Notadd\Foundation\Module\GraphQL\Mutations\InstallMutation::class,
                'setting'            => \Notadd\Foundation\Setting\GraphQL\Mutations\SettingMutation::class,
                'settings'           => \Notadd\Foundation\Setting\GraphQL\Mutations\SettingsMutation::class,
                'uninstallAddon'     => \Notadd\Foundation\Addon\GraphQL\Mutations\UninstallMutation::class,
                'uninstallExtension' => \Notadd\Foundation\Extension\GraphQL\Mutations\UninstallMutation::class,
                'uninstallModule'    => \Notadd\Foundation\Module\GraphQL\Mutations\UninstallMutation::class,
            ],
            'query'    => [
                'addons'        => \Notadd\Foundation\Addon\GraphQL\Queries\AddonQuery::class,
                'dashboards'    => \Notadd\Foundation\Administration\GraphQL\Queries\DashboardQuery::class,
                'informations'  => \Notadd\Foundation\Administration\GraphQL\Queries\InformationQuery::class,
                'navigations'   => \Notadd\Foundation\Administration\GraphQL\Queries\NavigationQuery::class,
                'extensions'    => \Notadd\Foundation\Extension\GraphQL\Queries\ExtensionQuery::class,
                'moduleDomains' => \Notadd\Foundation\Module\GraphQL\Queries\DomainQuery::class,
                'modules'       => \Notadd\Foundation\Module\GraphQL\Queries\ModuleQuery::class,
                'setting'       => \Notadd\Foundation\Setting\GraphQL\Queries\SettingQuery::class,
                'settings'      => \Notadd\Foundation\Setting\GraphQL\Queries\SettingsQuery::class,
            ],
        ],
    ],
    'types'   => [
        \Notadd\Foundation\Addon\GraphQL\Types\AddonType::class,
        \Notadd\Foundation\Administration\GraphQL\Types\DashboardType::class,
        \Notadd\Foundation\Administration\GraphQL\Types\InformationType::class,
        \Notadd\Foundation\Administration\GraphQL\Types\NavigationType::class,
        \Notadd\Foundation\Extension\GraphQL\Types\ExtensionType::class,
        \Notadd\Foundation\Module\GraphQL\Types\DomainType::class,
        \Notadd\Foundation\Module\GraphQL\Types\ModuleType::class,
        \Notadd\Foundation\Setting\GraphQL\Types\SettingsType::class,
    ],
];