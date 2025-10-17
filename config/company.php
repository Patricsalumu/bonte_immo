<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Company configuration
    |--------------------------------------------------------------------------
    |
    | Centralized company information. These values are read from the .env file
    | so you can override them per environment. Use config('company.name'),
    | config('company.email'), etc. in views and controllers.
    |
    */

    'name' => env('COMPANY_NAME', 'Eben-Ezer Immo'),
    'address' => env('COMPANY_ADDRESS', 'Avenue de la révolution, Q. Battant C. Lshi'),
    'email' => env('COMPANY_EMAIL', 'clb@collegelabonte.com'),
    'phone' => env('COMPANY_PHONE', '+243 812801656 & 820049444'),
    'logo' => env('COMPANY_LOGO', 'images/logo.png'),
];
