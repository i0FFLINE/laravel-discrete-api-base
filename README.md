# Laravel Discrete API

## Base package

### Description

This package implements the following features:

- Registration
- Password recovery
- Authorization
- Two-factor authorization by email
- Email Confirmation
- Password Confirmation
- Profile management (name, avatar, locale)
- Locale management via header `Accept-Language`
- Change email with confirmation (confirmation of new email will be required)
- Server-side notifications
- Organizations (can be renamed to teams, projects, groups with auto-route change)

The reason why I created this package is simple: Laravel + Breeze / Laravel + Jetstream are tied to a specific technology stack.
I'm not happy with this, as it doesn't allow me to throw out certain structures without destroying the entire software suite.
What is important for high loads - too much code is involved in the interpretation, which will not be used when using Laravel only as an API.
I do not use fantasy in the code, I often take what is already created by Laravel authors as a basis.

### Pre-Requirements

`.env`-file. Add:
```
APP_FRONTEND_DOMAIN="domain.com"
APP_DOMAIN="backend.domain.com"
APP_FRONTEND_URL="https://${APP_FRONTEND_DOMAIN}"
APP_URL="https://${APP_DOMAIN}"
```
### Installation

```
composer require ioffline/laravel-discrete-api-base
```
Publich the package config and model
```
php artisan vendor:publish --provider="IOF\DiscreteApi\Base\Providers\DiscreteApiBaseServiceProvider" --tag="discreteapibase-config"
php artisan vendor:publish --provider="IOF\DiscreteApi\Base\Providers\DiscreteApiBaseServiceProvider" --tag="discreteapibase-model"
```
If You plan make some modifications
```
php artisan vendor:publish --provider="IOF\DiscreteApi\Base\Providers\DiscreteApiBaseServiceProvider" --tag="discreteapibase-migrations"
php artisan vendor:publish --provider="IOF\DiscreteApi\Base\Providers\DiscreteApiBaseServiceProvider" --tag="discreteapibase-lang"
```
Check and do some corrections to the config at `config/discreteapibase.php`,<br>
Then do migrate `php artisan migrate`

Check the routes exists by `php artisan route:list`

### Modification

- Option 1:<br>
  - Copy Controllers and Actions from a Package to the app directory preserving structure
  - Change namespace at each file
  - Change in the config keys: `route_namespace` and `actions_namespace`
- Option 2:<br>
  - Create new classes (preserving structure as in `Option 1`) and extends to the Package classes.
  - Change in the config keys: `route_namespace` and `actions_namespace`

Withis solution you will able to modify and grow functionality.<br>
Attention ! This is not coplete solution. You will need to debug all steps to make this work.
<hr>
License MIT