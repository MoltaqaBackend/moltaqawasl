<?php

return [
    # Auth
    'WASL_CLIENT_ID' => env('WASL_CLIENT_ID', null),
    'WASL_APP_ID' => env('WASL_APP_ID', null),
    'WASL_APP_KEY' => env('WASL_APP_KEY', null),
    # Base URL
    'WASL_BASE_URL' => env('WASL_BASE_URL','https://wasl.api.elm.sa'),
    # EndPoints
    'WASL_REGISTER_DRIVER_AND_VEHICLE_ENDPOINT' => env('WASL_REGISTER_DRIVER_AND_VEHICLE_ENDPOINT','/api/dispatching/v2/drivers'),
    'WASL_CHECK_DRIVER_ELIGIBLIITY_ENDPOINT' => env('WASL_CHECK_DRIVER_ELIGIBLIITY_ENDPOINT','/api/dispatching/v2/drivers/eligibility'),
];
