<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'IP-address Cache',
        'label' => 'ip',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'sortby' => 'sorting',
        'versioningWS' => false,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'ip,country_name,country_code',
        'iconfile' => 'EXT:rms_extlocate_extend/Resources/Public/Icons/rms_extlocate_extend_domain_model_ip_cache.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'ip,country_name,country_code,hash_value, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'rms_extlocate_extend_domain_model_ip_cache',
                'foreign_table_where' => 'AND {#rms_extlocate_extend_domain_model_ip_cache}.{#pid}=###CURRENT_PID### AND {#rms_extlocate_extend_domain_model_ip_cache}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],

        'hash_value' => [
            'exclude' => false,
            'label' => 'Hash Value',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'default' => '',
                'required' => true,
            ],
        ],

        'ip' => [
            'exclude' => false,
            'label' => 'IP-Address',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'default' => '',
                'required' => true,
            ],
        ],

        'country_name' => [
            'exclude' => false,
            'label' => 'Country name',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'default' => '',
                'required' => true,
            ],
        ],

        'country_code' => [
            'exclude' => false,
            'label' => 'Country code',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 200,
                'eval' => 'trim',
                'default' => '',
                'required' => true,
            ],
        ],

        'json_geodata' => [
            'exclude' => false,
            'label' => 'Geoinformation as JSON',
            'config' => [
                'readOnly' => 1,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'default' => '',
            ],
        ],
    ],
];
