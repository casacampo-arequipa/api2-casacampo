<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de Cross-Origin Resource Sharing (CORS)
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar los ajustes para compartir recursos entre orígenes
    | (CORS). Esto determina qué operaciones de origen cruzado pueden ejecutarse
    | en los navegadores web. Puedes ajustar estos valores según sea necesario.
    |
    | Para más información: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://127.0.0.1:5173'], // Reemplaza con el origen de tu frontend

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
