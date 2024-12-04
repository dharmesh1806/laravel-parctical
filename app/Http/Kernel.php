<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\SubstituteBindings;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth.check' => \App\Http\Middleware\CheckUserAuthentication::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * These middleware can be assigned to groups of routes.
     *
     * @var array
     */
}
