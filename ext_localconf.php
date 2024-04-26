<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied.');

(static function () {
    $_EXTKEY = 'rms_extlocate_extend';

    #ExtensionManagementUtility::addTypoScript(
    #    'rms_extlocate_extend',
    #    'setup',
    #    ' <INCLUDE_TYPOSCRIPT: source="FILE:EXT:rms_extlocate_extend/Configuration/TypoScript/setup.typoscript">'
    #);
})();
