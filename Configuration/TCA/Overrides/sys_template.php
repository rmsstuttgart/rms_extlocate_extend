<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

call_user_func(
    static function () {
        ExtensionManagementUtility::addStaticFile(
            'rms_extlocate_extend',
            'Configuration/TypoScript',
            'rms_extlocate_extend'
        );
    }
);