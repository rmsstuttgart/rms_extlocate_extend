<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;

// see https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Icon/Index.html

return [
    // Icon identifier
    'rms_extlocate_extend_icon_png' => [
        'provider' => BitmapIconProvider::class,
        //'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        // The source SVG for the SvgIconProvider
        'source' => 'EXT:rms_extlocate_extend/Resources/Public/Icons/rms_extlocate_extend.png',
    ],
];
