![Logo](http://dmtry.me/img/logos/phpsa.png?v2 "PHPSA" | width=100)

PHPSA - Smart Analyzer for PHP
===============================
[![Build Status](https://travis-ci.org/ovr/phpsa.svg?branch=master)](https://travis-ci.org/ovr/phpsa)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ovr/phpsa/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ovr/phpsa/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ovr/phpsa/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ovr/phpsa/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/ovr/phpsa/v/stable.svg)](https://packagist.org/packages/ovr/phpsa)
[![License](https://poser.pugx.org/ovr/phpsa/license.svg)](https://packagist.org/packages/ovr/phpsa)

> PHPSA is a development tool aimed at bringing complex analysis for PHP applications and libraries.

P.S This software is currently in early alpha state, any contributions/stars will be awesome.

### Components

- [X] Core - Component containing definitions and other core files
- [X] Compiler - Component to compile expression(s) and statement(s) from an abstract syntax tree
- [X] Analyzer - Component doing various checks on your files
- [X] ControlFlow - Component for ControlFlow (WIP)

### Installation <sub>[(more)](/docs/01_Installation.md)</sub>

The recommended way to install phpsa is via Composer.

Run `php composer.phar require ovr/phpsa` or add a new requirement in your composer.json.

``` json
{
  "require": {
    "ovr/phpsa": "*"
  }
}
```

### How to use <sub>[(more)](/docs/02_Usage.md)</sub>

```sh
$ ./bin/phpsa check fixtures/

Syntax error:  Syntax error, unexpected T_RETURN on line 11 in fixtures/simple/syntax/Error2.php 

    $b = $a + 1; 123123

Notice:  Constant BBBB does not exist in self scope in fixtures/simple/undefined/Const.php on 29 [undefined-const]

    return self::BBBB; 

Notice:  You are trying to cast 'string' to 'string' in fixtures/simple/code-smell/StandardFunctionCall.php on 16 [stupid.cast]

    return (string) json_encode(array(

Notice:  Missing docblock for callStaticMethodBySelf() method in fixtures/Compiling/Expression/StaticCall.php on 18 [missing-docblock]

    public static function callStaticMethodBySelf()

```


### Requirements

PHP >= 5.5 (compatible up to version 7.0 && hhvm), but you can check files for PHP >= 5.2 with this.

### Documentation

See our [documentation](/docs/) in case you need more information on some topic.

### Contributing

Check our [Contributing Guide](/.github/CONTRIBUTING.md) to see how you can help.

### Sponsors

Thanks to our sponsors and supporters:

| JetBrains |
|---|
| <a href="https://www.jetbrains.com/phpstorm/" title="PHP IDE :: JetBrains PhpStorm" target="_blank"><img src="https://resources.jetbrains.com/assets/media/open-graph/jetbrains_250x250.png" height="55"></img></a> |

### LICENSE

This project is open-sourced software licensed under the MIT License.

See the [LICENSE](LICENSE) file for more information.
