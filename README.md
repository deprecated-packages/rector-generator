# Rector Generator

Every Rector rule requires test, test fixtures, config and the rule.

```bash
- src/Rector/Category/SomeRector.php
- tests/Rector/Category SomeRectorTest.php
- tests/Rector/Category/config/config.php
- tests/Rector/Category/Fixture/some_fixture.php.inc
```

~80 % of the code is repeated text - namespace, Rector name, default test setup etc.

This package will **save your typing the repeated code** and gives you **more space for writing `refactor()` method logic**.

## How to Generate?

### 1. From Config

@todo move from core

### 2. From CLI

@todo move from core
