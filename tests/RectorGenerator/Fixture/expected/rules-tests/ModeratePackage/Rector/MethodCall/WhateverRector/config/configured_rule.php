<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(\Rector\ModeratePackage\Rector\MethodCall\WhateverRector::class,
        ['old_package_name' => 'new_package_name']
    );
};
