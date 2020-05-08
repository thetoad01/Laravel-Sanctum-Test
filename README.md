## Laravel Sanctum Test

[Official Docs](https://laravel.com/docs/master/sanctum).

## Sanctum for token-based SPAs

Test the functionality with thetoad01/vue-sanctum-example

**Install**
```
composer require laravel/sanctum
composer require laravel/ui
php artisan ui vue --auth
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

**Put database settings in .env file**
```
php artisan migrate
```

**In app/Http/Kernel.php**
```
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

'api' => [
    EnsureFrontendRequestsAreStateful::class,
    'throttle:60,1',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

**In .env**
```
SESSION_DOMAIN=localhost
# where your frontend lives
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

**Follow directions in "Configuring Your First-Party Domains" in [Official Docs](https://laravel.com/docs/master/sanctum#spa-configuration)**

## Laravel Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Laravel License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
