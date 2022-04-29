<?php

namespace Utils\Rector\Tests\Rector\MethodCall\WhateverRector\Fixture;

$result = [];
echo 'code before';

?>
-----
<?php

namespace Utils\Rector\Tests\Rector\MethodCall\WhateverRector\Fixture;

$result = [];
echo 'code after';

?>
