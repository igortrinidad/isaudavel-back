<?php

return [

    /*
    |--------------------------------------------------------------------------
    | View to Bind JavaScript Vars To
    |--------------------------------------------------------------------------
    |
    | Set this value to the name of the view (or partial) that
    | you want to prepend all JavaScript variables to.
    | This can be a single view, or an array of views.
    | Example: 'footer' or ['footer', 'bottom']
    |
    */
    'bind_js_vars_to_this_view' => [
        'dashboard.companies.edit',
        'dashboard.companies.show',
        'oracle.dashboard.companies.edit',
        'oracle.dashboard.companies.subscription',
        'oracle.dashboard.companies.subscription-create',
        'oracle.dashboard.companies.invoice-show',
        'oracle.dashboard.clients.show',
        'oracle.dashboard.professionals.show',
        'oracle.dashboard.oracles.show',
        'oracle.dashboard.events.list',
        'oracle.dashboard.events.edit',
        'oracle.dashboard.recipes.edit',
        'landing.recipes.list',
        'landing.events.list',
        'oracle.dashboard.modalities.list',
        'oracle.dashboard.modalities.edit',
    ],

    /*
    |--------------------------------------------------------------------------
    | JavaScript Namespace
    |--------------------------------------------------------------------------
    |
    | By default, we'll add variables to the global window object. However,
    | it's recommended that you change this to some namespace - anything.
    | That way, you can access vars, like "SomeNamespace.someVariable."
    |
    */
    'js_namespace' => 'window'

];
