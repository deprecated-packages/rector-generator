# [Deprecated] Rector Generator

Note: We've had this custom package for creating Rector rules from simple file for years.

Yet in recent times, we practically never used it and only copy-paste old rule to new one :) package has no value, time to let go.

---

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
