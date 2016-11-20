# Analyzers
This doc gives an overview about what the different analyzers do.

#### error_suppression

Discourages the use of the `@` operator to silence errors.

#### multiple_unary_operators

Checks for use of multiple unary operators that cancel each other out. For example `!!boolean` or `- -int`. (there is a space between the two minus)

#### stupid_unary_operators

Checks for use of UnaryPlus `+$a` and suggests to use an int or float cast instead.

#### variable_variable_usage

Discourages the use of variable variables.

#### casts

Checks for casts that try to cast a type to itself.

#### eval_usage

Discourages the use of `eval()`.

#### final_static_usage

Checks for use of `static::` inside a final class.

#### compare_with_array

Checks for `{type array} > 1` and similar and suggests use of `count()`.

#### division_from_zero

Checks for division from 0. For example: `0/$x`, `false%$x`

#### division_by_one

Checks for division by 1. For example: `$x/1`, `$x%true`

#### backtick_usage

Discourages the use of backtick operator for shell execution.

#### logic_inversion

Checks for Logic inversion like `if (!($a == $b))` and suggests the correct operator.

#### exit_usage

Discourages the use of `exit()` and `die()`.

#### nested_ternary

Discourages the use of nested ternaries.

#### array_short_definition

Recommends the use of [] short syntax for arrays.

#### array_duplicate_keys

This inspection reports any duplicated keys on array creation expression.
If multiple elements in the array declaration use the same key, only the last
one will be used as all others are overwritten.

#### array_illegal_offset_type

Checks for illegal array key types (for example objects).

#### alias_check

Checks for use of alias functions and suggests the use of the originals.

#### debug_code

Checks for use of debug code and suggests to remove it.

#### random_api_migration

Checks for use of old rand, srand, getrandmax functions and suggests alternatives.

#### sleep_usage

Checks for use of different sleep functions which can lead to a DoS vulnerability.

#### use_cast

Checks for use of functions like boolval, strval and others and suggests the use of casts.

#### deprecated_ini_options

Checks for use of deprecated php.ini options and gives alternatives if available.

#### regular_expressions

Checks that regular expressions are syntactically correct.

#### argument_unpacking

Checks for use of `func_get_args()` and suggests the use of argument unpacking. (... operator)

#### deprecated_functions

Checks for use of deprecated functions and gives alternatives if available.

#### magic_method_parameters

Checks that magic methods have the right amount of parameters.

#### goto_usage

Discourages the use of goto and goto labels.

#### global_usage

Discourages the use of `global $var;`.

#### has_more_than_one_property

Checks for multiple property definitions in one line. For example public $a, $b; and discourages it.

#### missing_break_statement

Checks for a missing break or return statement in switch cases. Can ignore empty cases and the last case.

#### missing_visibility

Checks for missing visibility modifiers for properties and methods.

#### method_cannot_return

Checks for return statements in `__construct` and `__destruct` since they can't return anything.

#### unexpected_use_of_this

Checks for behavior that would result in overwriting $this variable.

#### test_annotation

Checks for use of `@test` when methods name begins with test, since it is unnecessary.

#### missing_docblock

Checks for a missing docblock for: class, property, class constant, trait, interface, class method, function.

#### old_constructor

Checks for use of PHP 4 constructors and discourages it.

#### constant_naming

Checks that constants are all uppercase.

#### missing_body

Checks that statements that define a block of statements are not empty.

#### inline_html_usage

Discourages the use of inline html.

#### assignment_in_condition

Checks for assignments in conditions. (= instead of ==)

#### static_usage

Discourages the use of static variables (not properties).

#### optional_param_before_required

Checks if any optional parameters are before a required one. For example: `function ($a = 1, $b)`

#### yoda_condition

Checks for Yoda conditions, where a constant is placed before the variable. For example: `if (3 == $a)`

#### for_condition

Discourages the use of `for` with multiple conditions.

#### property_definition_default_value

Checks if any Property Definition is done with a default null value (not needed). For example: `$a = null`

#### return_and_yield_in_one_method

Checks for using return and yield statements in a one method and discourages it.

#### check_l_number_kind

Using octal, hexadecimal or binary integers is discouraged.

Next: [How To: Write own Analyzer](./06_HowTo_Own_Analyzer.md)
