<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule('SomeDummyRector');
};

?>
-----
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule('SomeDummyRector');
    $rectorConfig->rule(\Rector\SomePackage\Rector\String_\SomeRector::class);
};

?>
