
cs:
	./vendor/bin/phpcs --standard=PSR2 --report=emacs --extensions=php --warning-severity=0 src/ tests/PHPSA/

check-src:
	./bin/phpsa check -vvv ./src

# Rubric, hacking themselves (c) @Kistamushken
check-fixtures:
	./bin/phpsa check -vvv ./fixtures

# For local dev
dev:
	./bin/phpsa check -vvv ./sandbox

tests-local:
	./vendor/bin/phpunit -v

# Alias for tests-local
tests: tests-local

tests-ci:
	./vendor/bin/phpunit -v --debug --coverage-clover=coverage.clover

# For renewing config and documentation when analyzers were changed
analyzers:
	./bin/phpsa config:dump-reference > .phpsa.yml	
	./bin/phpsa config:dump-documentation > docs/05_Analyzers.md

test: tests-local cs

ci: cs tests-ci check-fixtures check-src
