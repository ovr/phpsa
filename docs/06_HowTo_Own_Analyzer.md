# How to write your own Analyzer

This gives an overview of what needs to be done to create your own analyzer. It mainly consists of 4 steps:

### 1. Create Analyzer

First, you need to find out what you want to do. There are Expressions, Statements and Scalars. 

A rule of thumb is: if what you want to check for has blocks (for example if, while, try catch) it is a statement, else it's an expression. But there are exceptions to this so just check nikic's PhpParser project to find out what you need:

[All Expression Nodes are here](https://github.com/nikic/PHP-Parser/tree/master/lib/PhpParser/Node/Expr)

[All Statement Nodes are here](https://github.com/nikic/PHP-Parser/tree/master/lib/PhpParser/Node/Stmt)

If you found what you need you can create your analyzer in the appropriate directory. It's best to copy an existing one and change that.

You will have to change the getRegister() method. Add here all the Nodes you want your analyzer to check. (can be multiple!) In the pass() method you write the code your analyzer should execute. The metadata are some configuration settings. For example you can set a minimum php version level. If the checked code is below that version level your analyzer won't be executed.

A tip for writing your analyzer: Check out the `$context` variable that gets passed in. It contains a lot of useful things. For example, you can get an Expression Compiler like this:

`$context->getExpressionCompiler()->compile($something);`

You can then use that CompiledExpression. This can, for example, compile variables down to its value or check data types.

### 2. Add it to the Factory

You need to add it to \PHPSA\Analyzer\Factory. Check that you used the correct list.

### 3. Write Tests

If you want to create a Pull Request and want to share your new analyzer with the rest of us, we require that you write some tests for it.

This is not that hard, you only have to create a new file in tests/analyze-fixtures/ (and from here the same directory structure your analyzer lives in)

At the top of your test you write a class/function/trait/interface with an example of what your analyzer should notice and one where it should not notice anything (if there is a reasonable case for this)

Now you split that file by: `----------------------------` (it has to be exactly this long) and below it add a JSON with all the notices of one type (the one of your analyzer) that should occur when checking the file at the top. (Look at existing ones)

You can run the tests by typing `make ci` in the project root directory. This will automatically run all tests Travis runs.

### 4. Make resources

Execute `make analyzers` in the project root directory to update the configuration and documentation file. If all tests pass you can now create your pull request.

Next: [Plugins](./07_Plugins.md)
