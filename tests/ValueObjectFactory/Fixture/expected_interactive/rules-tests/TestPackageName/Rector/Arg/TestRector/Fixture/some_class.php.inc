<?php

namespace Rector\Tests\TestPackageName\Rector\Arg\TestRector\Fixture;

class SomeClass
{
    public function run()
    {
        $this->something();
    }
}

?>
-----
<?php

namespace Rector\Tests\TestPackageName\Rector\Arg\TestRector\Fixture;

class SomeClass
{
    public function run()
    {
        $this->somethingElse();
    }
}

?>
