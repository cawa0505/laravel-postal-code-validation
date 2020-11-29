# Changelog

## [v4.0.0](https://github.com/axlon/laravel-postal-code-validation/tree/v4.0.0)
- Removed support for Laravel 5
- Removed support for custom validators that do not extend the Laravel base validator
- Removed `postal_code_for` validation rule
- Removed the rule builder class, rules may now be built from the facade
- Changed facade name from `PostalCodes` to `PostalCode`
- Added a fallback validation error message
