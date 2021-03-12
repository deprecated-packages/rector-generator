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

## How to Generate a new Rule?

There are 2 ways to generate a new rule.

### 1. Generate using Configuration File

1. Initialize `rector-recipe.php` config

```bash
vendor/bin/rector init-recipe
```

2. Complete parameters in `rector-recipe.php` to design your new rule

3. Run command

```bash
vendor/bin/rector generate
```

### 2. Generate using Commandline Interactive Mode

**Important**: using this approach will generate Rector rule with placeholder Code Samples, which should be changed
by hand to reflect what the rule does

1. Run Generate command in Interactive Mode

```bash
vendor/bin/rector generate --interactive
```

2. Provide an answer to questions asked by the command

<br>

That's it :)
