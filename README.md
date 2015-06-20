PHP Static Analysis for PHP
===========================

> Awesome tool for awesome PHP developers

```sh
ovr@ovr-desktop:~/projects/ovr/phpsa$ ./bin/phpsa
PHP Static Analyzer version 0.0.1-dev #1c583e3

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
./bin/phpsa check .
Notice:  Method b() is not exists on this scope.  in  tests/simple/test-1/1.php on 7 [undefined-mcall]

	 return $this->b(); 
```


## LICENSE

MIT, Have fun
