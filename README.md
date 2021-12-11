# Rector Generator

## Installation

This package require to use `rector-src`, you need to install `rector-src` to use it with the following steps:

```
composer config minimum-stability dev
composer config prefer-stable true
composer require --dev rector/rector-src
composer require --dev symplify/vendor-patches
```

After that, update composer.json and add extra:

```
    "extra": {
        "enable-patching": true
    }
```

After that, run composer update: 

```
composer update
```

After that, require rector-generator:

```
composer require --dev rector/rector-generator:dev-main
```

## Structure

Every Rector rule requires test, test fixtures, config and the rule.

```bash
- rules/Package/Rector/Category/SomeRector.php

- rules-tests/Package/Rector/Category/SomeRector/SomeRectorTest.php
- rules-tests/Package/Rector/Category/SomeRector/config/confured_rule.php
- rules-tests/Package/Rector/Category/SomeRector/Fixture/some_fixture.php.inc
```

~80 % of the code is repeated text - namespace, Rector name, default test setup etc.

This package will **save your typing the repeated code** and gives you **more space for writing `refactor()` method logic**.

## How to Generate a new Rule?

1. Initialize `rector-recipe.php` config

```bash
vendor/bin/rector init-recipe
```

2. Complete parameters in `rector-recipe.php` to design your new rule

3. Run command

```bash
vendor/bin/rector generate
```

<br>

That's it :)
