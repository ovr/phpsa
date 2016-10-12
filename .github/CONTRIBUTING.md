# Contributing Guide
Hey Contributor! :smiley_cat:

All contributions to PHPSA are very much encouraged, and we do our best to make it as welcoming and simple as possible.

## Coding Standards

We require that all contributions meet at least the following guidelines:

* Follow PSR-1 & PSR-2
* Use camelCase for variables and methods/functions.
* Don't use functions for casting like `intval`, `boolval` and etc, We are using `(int) $a`.
* Avoid aliases for functions: `sizeof`, `join` and etc.
* Avoid global variables.
* Avoid strict comparisons if not necessary.
* Avoid `static` methods.
* Only use `static::` binding if it's really needed. `parent::` or `self::` will be sufficient in most cases.
* Don't use `Singleton` pattern anywhere.
* Use strict types (objects, arrays); for example: `function testMethod(array $array = [])`.
* Use `$v === null` instead of 'is_null()' for null checking.
* Avoid "Yoda conditions", where constants are placed first in comparisons:

```php
if (true == $someParameter) {
}
```
* Don't forget about empty lines after logical blocks:

```php
public function simpleMethod($a)
{
    $result = 1 + 2;
                                // $result is not related to if block, please write empty line
    $b = $a;
    if ($b) {
        $result = 1;
    }
                                // Empty line is needed there
    return $result;
}
```

**ATTENTION** Some rules can be omitted in `tests/analyze-fixtures`, because we need to check Analyzers on bad code.

### Naming Conventions

#### Naming

* For `abstract` classes, use `Abstract` prefix, `AbstractCondition`
* For `trait`(s), use `Trait` suffix, `ResolveExpressionTrait`
* For `interface`(s), use `Interface` suffix, `PassFunctionCallInterface`
* For any classes that extend from `Exception`, use `Exception` suffix, `UnknownException`

#### Namespacing

Please omit `s` at the end of NS/Class/Trait/Interface names

What we are using:

`\PHPSA\Analyzer\Helper\ResolveExpressionTrait`

What we don't use:

`\PHPSA\Analyzer\HelperS\ResolveExpressionSTrait`

## GIT

Please don't use "merge" in your PR, we are using "rebase", small guide:

[Git Branching Rebasing](https://git-scm.com/book/en/v2/Git-Branching-Rebasing)

Example:

```bash
git checkout YOU_BRANCH

git fetch ORIGIN_REMOVE_OF_THE_PHPSA

git rebase ORIGIN_REMOVE_OF_THE_PHPSA/master

git push YOU_REMOVE YOU_BRANCH -f
```

## Testing

We are using Makefile:

```bash
# Running code style check, unit tests, check PHPSA and fixtures
make test

# Running unit tests
make tests


# Running all (CI)
make ci

# Running code style check
make cs
```

## Maintaining (for push only developers)

- If you are going to close an issue, write a comment describing why you are going to do so (with link reference to the commit/issue/PR)
- Before merge, check that CI passes
- Merge after review (1 other developer reviewed)
- Check that code uses our `Coding Standards` and `Naming Conventions`
- Don't merge big PRs (only simple PRs), if it's a big PR - please ping @ovr
- Write `Thanks` to developer(s) and reviewer(s) after PR was merged
- If there are any `merge` commits in PR, you should write a notice to the submitter to remove those

Thanks :cake:
