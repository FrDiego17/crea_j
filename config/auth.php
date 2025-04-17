<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Authentication Defaults
    |---------------------------------------------------------------------------
    |
    | Este valor define el "guard" por defecto para la autenticación y el "broker"
    | para el restablecimiento de contraseñas. Estos valores son configuraciones
    | predeterminadas útiles para la mayoría de las aplicaciones.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'api'),  // Cambiado a 'api' para usar Sanctum por defecto
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |---------------------------------------------------------------------------
    | Authentication Guards
    |---------------------------------------------------------------------------
    |
    | Aquí defines cada uno de los guards para la autenticación en tu aplicación.
    | Un "guard" utiliza un proveedor de usuarios que define cómo se recuperan
    | los usuarios de la base de datos u otro sistema de almacenamiento.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [  // Agregado guardia API con Sanctum
            'driver' => 'sanctum', 
            'provider' => 'users',
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | User Providers
    |---------------------------------------------------------------------------
    |
    | Los "providers" definen cómo se recuperan los usuarios de la base de datos.
    | Si tienes múltiples tablas de usuarios o modelos, puedes configurar
    | proveedores separados.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Resetting Passwords
    |---------------------------------------------------------------------------
    |
    | Aquí puedes configurar la funcionalidad de restablecimiento de contraseñas.
    | Cambia el tiempo de expiración de los tokens y los ajustes de throttling.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |---------------------------------------------------------------------------
    | Password Confirmation Timeout
    |---------------------------------------------------------------------------
    |
    | Aquí defines la cantidad de tiempo antes de que expire la ventana de confirmación
    | de contraseña.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
