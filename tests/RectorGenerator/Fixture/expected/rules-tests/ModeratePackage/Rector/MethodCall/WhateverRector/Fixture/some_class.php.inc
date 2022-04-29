<?php

namespace Rector\Tests\ModeratePackage\Rector\MethodCall\WhateverRector\Fixture;

$result = [];
echo 'code before';

?>
-----
<?php

namespace Rector\Tests\ModeratePackage\Rector\MethodCall\WhateverRector\Fixture;

$result = [];
echo 'code after';

?>
