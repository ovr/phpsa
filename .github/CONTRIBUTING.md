
Hey Contributor! :smiley_cat:

Any contribution(s) to PHPSA are very much encouraged, and we do our best to make it as welcoming and simple as possible.

## Coding Standards

We require that all contributions meet at least the following guidelines:

* PSR-1 & PSR-2
* We are using camelCase for variables and methods/functions.
* Don't use functions for casting like `intval`, `boolval` and etc, We are using `(int) $a`.
* Avoid "Yoda conditions", where constants are placed first in comparisons:

```php
if (true == $someParameter) {
}
```

* Avoid strict compare When it's not needed.
* Avoid `static` methods.
* Use `static::` binding if it's really needed, mostly you will use `parent::` or `self::`
* Don't use `Singleton` pattern anywhere.
* Avoid aliases for functions: `sizeof`, `join` and etc.
* Avoid global variables.
* Use strict types (objects, arrays), `function testMethod(array $array = [])` as an example.
* For null checking use `$v === null` instead of `is_null()`.
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

**ATTENTION** Some rules can be omitted in `tests/analyze-fixtures`, because We need to check Analyzer on bad code.

### Naming Conventions

#### Naming

* For `abstract` class We are using `Abstract` prefix, `AbstractCondition`
* For `trait`(s) We are using `Trait` suffix, `ResolveExpressionTrait`
* For `interfaces`(es) We are using `Interface` suffix, `PassFunctionCallInterface`
* For any class(es) that extends from `Exception` We are using `Exception` suffix, `UnknownException`

#### Namespacing

Please omit `s` in the end of NS/Class/Trait/Interface names

What we are using:

`\PHPSA\Analyzer\Helper\ResolveExpressionTrait`

What we don't use:

`\PHPSA\Analyzer\HelperS\ResolveExpressionSTrait`

## GIT

Please don't use "merged" in your PR, We are using "rebase", small guide:

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

- If you are going to close issue, write a comment why you are going to close it (with referenced link to commit/issue/PR)
- Before merge check that CI pass
- Merge after review (yet another developer review)
- Check that code use our `Coding Standards` and `Naming Conventions`
- Don't merge big PRs (only simple PRs), if it's a big PR - please ping @ovr
- Write `Thanks` to developer(s) and reviewer(s) after PR was merged
- Protect any `merge` commits in PR, you should write a notice to submitter

Thanks :cake:
