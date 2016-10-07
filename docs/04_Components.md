# Components

This is an overview of the components that are contained in PHPSA. Currently it's not possible to use them separately.

### Core

The Core component contains all the things that are used by multiple other components. For example all our own definitions are here.

### Compiler

The Compiler can compile your PHP Code version 5.2 to 7.0 and can notice every syntax error (and in the future language level errors).

### Analyzer

The Analyzer contains various checks to improve your codebase. You can change which of them are active in the configuration file. (see [Configuration](./03_Configuration.md))

### Control Flow Graph

work in progress

Next: [Analyzers](./05_Analyzers.md)