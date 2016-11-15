# Usage

You can change the configuration by editing .phpsa.yml in the root directory. Here, it's possible to disable every single analyzer. Or, just configure a minimum php version that your checked code should run on and we will automatically disable all analyzers that you don't need.

```sh
$ ./bin/phpsa
PHP Smart Analyzer version 0.6.1

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
  check                      Runs compiler and analyzers on all files in path
  compile                    Runs compiler on all files in path
  help                       Displays help for a command
  list                       Lists commands
 config
  config:dump-documentation  Dumps the analyzer documentation
  config:dump-reference      Dumps the default configuration

```

## It is highly recommended to disable xdebug

You can run `php` with these parameters to disable it:

```sh
php -n -d xdebug.enable=0 -f ./bin/phpsa
```

## Example output

You can try it out and run phpsa on our fixtures/directory. It contains various things the analyzer will notify you of.

```sh
$ ./bin/phpsa check fixtures/

Syntax error:  Syntax error, unexpected T_RETURN on line 11 in fixtures/simple/syntax/Error2.php 

    $b = $a + 1; 123123

Notice:  Constant BBBB does not exist in self scope in fixtures/simple/undefined/Const.php on 29 [undefined-const]

    return self::BBBB; 

Notice:  You are trying to cast 'string' to 'string'. in fixtures/simple/code-smell/StandardFunctionCall.php on 16 [stupid.cast]

    return (string) json_encode(array(

Notice:  Missing docblock for callStaticMethodBySelf() method in fixtures/Compiling/Expression/StaticCall.php on 18 [missing-docblock]

    public static function callStaticMethodBySelf()

Notice:  Is not object cannot be called like this in fixtures/simple/undefined/MCall.php on 101 [mcall.not-object]

    return $floatVariable->FloatMethod(); 

Notice:  Method simpleStaticMethod() is a static function but called like class method in fixtures/simple/undefined/MCall.php on 119 [mcall.static]

    return $this->simpleStaticMethod(); 

Notice:  You trying to use undefined variable $b in fixtures/simple/undefined/LocalVariable.php on 13 [undefined-variable]

    return $a + $b; 

Notice:  Please use []  (short syntax) for array definition. in fixtures/simple/undefined/MCall.php on 79 [array.short-syntax]

    $arrayVariable = array(); 

Notice:  You trying to use division on {expr}/0 in fixtures/simple/code-smell/DivisionZero.php on 52 [division-zero]

    return 1000 / ((-4) + (5^1)); 

Notice:  You trying to use division from 0/{expr} in fixtures/simple/code-smell/DivisionZero.php on 60 [division-zero]

    return 0 / 1000; 

```

Next: [Configuration](./03_Configuration.md)
