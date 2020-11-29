# Changelog

## [v4.0.0](https://github.com/axlon/laravel-postal-code-validation/tree/v4.0.0)
- Removed support for Laravel 5
- Removed support for custom validators that do not extend the Laravel base validator
- Removed `postal_code_for` validation rule
- Removed the rule builder class, rules may now be built from the facade
- Changed facade name from `PostalCodes` to `PostalCode`
- Changed `postal_code_with`, it will no longer pass when all the referenced fields are missing
- Added a fallback validation error message
- Fixed `TypeError` when a rule received a value that was not stringable (e.g. an array)
- Fixed `TypeError` when `postal_code_with` received a parameter that pointed to a field that was not stringable (e.g. an array)
