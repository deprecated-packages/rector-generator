<?php

declare(strict_types=1);

return static function (\Symplify\EasyCI\Config\EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->paths([__DIR__ . '/config', __DIR__ . '/src']);

    $easyCIConfig->typesToSkip([
        \Rector\Set\Contract\SetListInterface::class,
        \Rector\Core\Contract\Rector\RectorInterface::class,
    ]);
};
