# Analyzers

This doc gives an overview about what the different analyzers do.

#### AliasCheck

Checks for use of alias functions and suggests the use of the originals.

#### ArgumentUnpacking

Checks for use of `func_get_args()` and suggests the use of argument unpacking. (... operator)

#### ArrayDuplicateKeys

This inspection reports any duplicated keys on array creation expression.
If multiple elements in the array declaration use the same key, only the last one will be used as all others are overwritten.

#### ArrayIllegalOffsetType

Checks for illegal array key types (for example objects).

#### ArrayShortDefinition

Recommends the use of [] short syntax for arrays.

#### AssignmentInCondition

Checks for assignments in conditions. (= instead of ==)

#### BacktickUsage

Discourages the use of backtick operator for shell execution.

#### Casts

Checks for casts that try to cast a type to itself.

#### CompareWithArray

Checks for `{type array} > 1` and similar and suggests use of `count()`.

#### ConstantNaming

Checks that constants are all uppercase.

#### DebugCode

Checks for use of debug code and suggests to remove it.

#### DeprecatedFunctions

Checks for use of deprecated functions and gives alternatives if available.

#### DeprecatedIniOptions

Checks for use of deprecated php.ini options and gives alternatives if available.

#### ErrorSuppression

Discourages the use of the `@` operator to silence errors.

#### EvalUsage

Discourages the use of `eval()`.

#### ExitUsage

Discourages the use of `exit()` and `die()`.

#### FinalStaticUsage

Checks for use of `static::` inside a final class.

#### ForCondition

Discourages the use of `for` with multiple conditions

#### GlobalUsage

Discourages the use of `global $var;`.

#### GotoUsage

Discourages the use of goto and goto labels.

#### HasMoreThanOneProperty

Checks for multiple property definitions in one line. For example public $a, $b; and discourages it.

#### InlineHtmlUsage

Discourages the use of inline html.

#### LogicInversion

Checks for Logic inversion like `if (!($a == $b))` and suggests the correct operator.

#### MagicMethodParameters

Checks that magic methods have the right amount of parameters.

#### MethodCannotReturn

Checks for return statements in `__construct` and `__destruct` since they can't return anything.

#### MissingBody

Checks that statements that define a block of statements are not empty.

#### MissingBreakStatement

Checks for a missing break or return statement in switch cases. Can ignore empty cases and the last case.

#### MissingDocblock

Checks for a missing docblock for: class, property, class constant, trait, interface, class method, function.

#### MissingVisibility

Checks for missing visibility modifiers for properties and methods.

#### MultipleUnaryOperators

Checks for use of multiple unary operators that cancel each other out. For example `!!boolean` or `- -int`. (there is a space between the two minus)

#### OldConstructor

Checks for use of PHP 4 constructors and discourages it.

#### OptionalParamBeforeRequired

Checks if any optional parameters are before a required one. For example: `function ($a = 1, $b)`

#### RandomApiMigration

Checks for use of old rand, srand, getrandmax functions and suggests alternatives.

#### RegularExpressions

Checks that regular expressions are syntactically correct.

#### StaticUsage

Discourages the use of static variables (not properties).

#### StupidUnaryOperators

Checks for use of UnaryPlus `+$a` and suggests to use an int or float cast instead.

#### TestAnnotation

Checks for use of `@test` when methods name begins with test, since it is unnecessary.

#### UnexpectedUseOfThis

Checks for behavior that would result in overwriting $this variable.

#### UseCast

Checks for use of functions like boolval, strval and others and suggests the use of casts.

#### VariableVariableUsage

Discourages the use of variable variables.

#### YodaCondition

Checks for Yoda conditions, where a constant is placed before the variable. For example: `if (3 == $a)`

Next: [How To: Write own Analyzer](./06_HowTo_Own_Analyzer.md)
