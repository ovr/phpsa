# How to write your own Analyzer

This gives an overview about what is needed to do, to create your own analyzer. It mainly consists of 3 steps.

### 1. Create Analyzer

First you need to find out what you want to do. There are Expressions and Statements. 

A rule of thumb is: if what you want to check for has blocks (for example if, while, try catch) it is a statement, else it's an expression. But there are exceptions to this so just check nikic's PhpParser project to find out what you need:

[All Expression Nodes are here](https://github.com/nikic/PHP-Parser/tree/master/lib/PhpParser/Node/Expr)

[All Statement Nodes are here](https://github.com/nikic/PHP-Parser/tree/master/lib/PhpParser/Node/Stmt)

If you found what you need you can create your analyzer in the appropriate directory. It's best to copy an existing one and change that.

You will have to change the getRegister() method. Add here all the Nodes you want your analyzer to check. (can be multiple!) In the pass() method you write the code your analyzer should execute. The metadata are some configuration settings. For example you can set a minimum php version level. If the checked code is below that version level your analyzer won't be executed.

A tip for writing your analyzer: Check out the `$context` variable that gets passed in. It contains a lot of useful things. For example you can get an Expression Compiler like this:

`$context->getExpressionCompiler()->compile($something);`

You can then use that CompiledExpression. This can for example compile variables down to it's value or check data types.

### 2. Add it to the Factory

You need to add it to \PHPSA\Analyzer\Factory. If you have created an Expression Analyzer add it to the top list. If you have created a Statement Analyzer add it to the bottom one.

### 3. Add Config setting

In the project root directory is a config file called `.phpsa.yml` here you can add your analyzer and give it a default setting of true/false for enabled.

### 4. Write Tests

If you want to create a Pull Request and want to share your new analyzer with the rest of us, we require that you write some tests for it.

This is not that hard, you only have to create a new file in tests/analyze-fixtures/ (and from here the same directory structure your analyzer lives in)

At the top of your test you write a class/function/trait/interface with an example of what your analyzer should notice and one where it should not notice anything (if there is a reasonable case for this)

Now you split that file by: `----------------------------` (it has to be exactly this long) and below it add a JSON with all the notices of one type (the one of your analyzer) that should occur when checking the file at the top. (Look at existing ones)

### 5. Add it to Documentation

Add your analyzer to `docs/05_Analyzers.md` with a short description of what your analyzer does.
