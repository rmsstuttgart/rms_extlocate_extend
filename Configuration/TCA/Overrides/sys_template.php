<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

ExtensionManagementUtility::addStaticFile('rms_extlocate_extend', 'Configuration/TypoScript', 'rms_extlocate_extend');
