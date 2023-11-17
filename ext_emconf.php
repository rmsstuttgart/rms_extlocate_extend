<?php

declare(strict_types=1);

$_EXTKEY = 'rms_extlocate_extend';
$EM_CONF[$_EXTKEY] = [
    'title' => 'rms_extlocate_extend',
    'description' => 'Rms ext:locate extension extension',
    'category' => 'plugin',
    'author' => 'mk',
    'author_email' => 'mkettel@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
 