![Logo](http://dmtry.me/img/logos/phpsa.png?v1 "PHPSA")

PHPSA - Smart Analysis for PHP
===============================
[![Build Status](https://travis-ci.org/ovr/phpsa.svg?branch=master)](https://travis-ci.org/ovr/phpsa)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ovr/phpsa/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ovr/phpsa/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ovr/phpsa/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ovr/phpsa/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/ovr/phpsa/v/stable.svg)](https://packagist.org/packages/ovr/phpsa)
[![License](https://poser.pugx.org/ovr/phpsa/license.svg)](https://packagist.org/packages/ovr/phpsa)

> PHPSA is a development tool aimed at bringing complex analysis for PHP applications and libraries.

P.S This software is currently in early alpha state, any contributions/stars will be awesome.

## Components

- [X] Compiler - Component to compile expression(s) and statement(s) from AST
- [X] Analyzer
- [X] ControlFlow (WIP)
- [X] Definition - base definitions

## Goals

What is needed or planned as future.

#### Unused

- [ ] Import
- [X] Local variable
- [X] Parameter
- [ ] Private field
- [ ] Private method

#### Undefined

- [X] Class
- [X] Class constant
- [X] Class property
- [X] Class method
- [ ] Callback
- [X] Constant
- [X] Function
- [ ] Namespace
- [X] Variable

#### PHPDockblock

- [X] Missing doc block
- [ ] Missing @return
- [ ] Missing @param

#### Control flow

- [ ] Loop which does not loop
- [ ] Ternary operator simplification
- [ ] Elvis operator can be used
- [ ] Not optimal if conditions
- [ ] Infinity loop
- [ ] Unreachable statement
- [X] Stupid cast
- [X] Not implemented class methods
- [X] Not implemented function

#### Probable bugs

- [X] Division by zero {expr}/0
- [X] Division from zero 0/{expr}
- [X] Missing 'break' statement
- [X] Void function result used

#### General

- [ ] Language level
- [X] Syntax error

#### Analyzer(s)

- Alias Check
- Debug Code
- Deperecated Ini Options
- Random Api Migration
- Use Cast
- Array short definition

## Installation

### Via `.phar`

The easiest way to get it working is to download a tagged phpsa.phar release, and put this on your path. For example:

```
wget https://github.com/ovr/phpsa/releases/download/0.5.0/phpsa.phar
chmod +x phpsa.phar
sudo mv phpsa.phar /usr/local/bin/phpsa
```

### Via composer

The recommended way to install phpsa is via `composer`.

1. If you do not have composer installed, download the [`composer.phar`](https://getcomposer.org/composer.phar) executable or use the installer.

``` sh
$ curl -sS https://getcomposer.org/installer | php
```

2. Run `php composer.phar require ovr/phpsa` or add requirement in composer.json.

``` json
{
  "require": {
    "ovr/phpsa": "*"
  }
}
```

3. Run `php composer.phar update`


### Via source

```sh
git clone https://github.com/ovr/phpsa
cd phpsa
./bin/phpsa
```

### It is highly recommended to disable xdebug

You can run `php` with parameters to disable it:

```sh
php -n -d xdebug.enable=0 -f ./bin/phpsa
```

## How to use

```sh
$ ./bin/phpsa
PHP Smart Analyzer version 0.5.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  check  SPA
  help   Displays help for a command
  list   Lists commands
```

Example output:

```sh
$ ./bin/phpsa check ./tests/

It is highly recommended to disable the XDebug extension before invoking this command.
Scanning directory ./tests/simple
found 10 files

Syntax error:  Syntax error, unexpected '}' on line 8 in ./tests/simple/syntax-error/1.php

Notice:  Unused variable $a in method test() in ./tests/simple/unused-variable/1.php  [unused-variable]

Notice:  Property a does not exist in this scope in ./tests/simple/undefined/Property.php on 9 [undefined-property]

	 return $this->a;

Notice:  Constant BBBB does not exist in self scope in ./tests/simple/undefined/Const.php on 14 [undefined-const]

	 return self::BBBB;

Notice:  Method b() does not exist in this scope in ./tests/simple/undefined/MCall.php on 7 [undefined-mcall]

	 return $this->b();

Notice:  You trying to use undefined variable $unusedVariable in ./tests/simple/undefined/MCall.php on 23 [undefined-variable]

	 return $unusedVariable->b();

Notice:  Function undefinedFunction() does not exist in ./tests/simple/undefined/FCall.php on 7 [undefined-fcall]

	 undefinedFunction();

Notice:  You trying to use undefined variable $b in ./tests/simple/undefined/LocalVariable.php on 8 [undefined-variable]

	 return $a + $b;

Notice:  Static method b() does not exist in self scope in ./tests/simple/undefined/SCall.php on 7 [undefined-scall]

	 return self::b();

Notice:  You trying to use division on {expr}/0 in ./tests/simple/devision-by-zero/1.php on 7 [division-zero]

	 return 1000 / 0;

Notice:  You trying to use division on {expr}/0 in ./tests/simple/devision-by-zero/1.php on 12 [division-zero]

	 return 1000 / (100-100);

Notice:  You trying to use division on {expr}/0 in ./tests/simple/devision-by-zero/1.php on 17 [division-zero]

	 return 1000 / ((50+50)-100);

Notice:  You trying to use division on {expr}/0 in ./tests/simple/devision-by-zero/1.php on 22 [division-zero]

	 return 1000 / ((5*5)-25);

Notice:  You trying to use division on {expr}/0 in ./tests/simple/devision-by-zero/1.php on 27 [division-zero]

	 return 1000 / ((-25) + (5*5));

Notice:  You trying to use division on {expr}/0 in ./tests/simple/devision-by-zero/1.php on 32 [division-zero]

	 return 1000 / ((-4) + (5^1));

Notice:  You trying to use division from 0/{expr} in ./tests/simple/devision-by-zero/1.php on 37 [division-zero]

	 return 0 / 1000;

Memory usage: 4.97 (peak: 5.25) MB
```

## Requirements

- PHP >= 5.5 (compatible up to version 7.0 && hhvm), but anyway this can check files for PHP >= 5.2

## Sponsors

Thanks to our sponsors and supporters:

| JetBrains |
|---|
| <a href="https://www.jetbrains.com/phpstorm/" title="PHP IDE :: JetBrains PhpStorm" target="_blank"><img src="https://www.jetbrains.com/phpstorm/documentation/docs/logo_phpstorm.png"></img></a> |

## LICENSE

This project is open-sourced software licensed under the MIT License.

See the LICENSE file for more information.
