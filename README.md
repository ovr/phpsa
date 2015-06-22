PHPSA - Static Analysis for PHP
===============================

> Awesome tool for awesome PHP developers

## Goals

What is needed or planned as future.

#### Unused

- [ ] Import
- [ ] Local variable
- [ ] Parameter
- [ ] Private field
- [ ] Private method

#### Undefined checks

- [ ] Undefined class
- [X] Undefined class constant
- [X] Undefined class property
- [X] Undefined class method
- [ ] Undefined callback
- [ ] Undefined constant
- [ ] Undefined function
- [ ] Undefined namespace
- [ ] Undefined variable
- [ ] Undefined callback

#### PHPDockblock

- [ ] Missing @return
- [ ] Missing doc block

#### Control flow

- [ ] Loop which does not loop
- [ ] Ternary operator simplification
- [ ] Elvis operator can be used
- [ ] Not optimal if conditions
- [ ] Infinity loop
- [ ] Unreachable statement

#### Probable bugs

- [X] Division by zero
- [ ] Missing 'break' statement
- [ ] Void function result used

#### General

- [ ] Language level
- [X] Syntax error

## Installation

@todo

## How to use

```sh
$ ./bin/phpsa
PHP Static Analyzer version 0.0.1-dev #489272e

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

Notice:  Static method b() is not exists on self scope in ./tests/simple/undefined-scall/1.php on 7 [undefined-scall]

	 return self::b(); 

Notice:  Static method a() is not exists on self scope in ./tests/simple/undefined-scall/1.php on 12 [undefined-scall]

	 return self::a(); 

Notice:  Method b() is not exists on this scope in ./tests/simple/undefined-mcall/1.php on 7 [undefined-mcall]

	 return $this->b(); 

Notice:  Property a is not exists on this scope in ./tests/simple/undefined-property/1.php on 9 [undefined-property]

	 return $this->a; 

Notice:  Function undefinedFunction() is not exists in ./tests/simple/undefined-fcall/1.php on 7 [undefined-fcall]

	 undefinedFunction(); 

Notice:  Constant BBBB is not exists on self scope in ./tests/simple/undefined-const/1.php on 14 [undefined-const]

	 return self::BBBB; 

Syntax error:  Syntax error, unexpected '}' on line 8 in ./tests/simple/syntax-error/1.php 

```


## LICENSE

MIT, Have fun
