<?php

return [

    /*
     * The destination path for the javascript file.
     */
    'path' => 'public/js',

    /*
     * The destination filename for the javascript file.
     */
    'filename' => 'routejs',

    /*
     * The namespace for the helper functions. By default this will bind them to
     * `window.routejs`.
     */
    'namespace' => 'routejs',

    /*
     * Generate absolute URLs
     *
     * Set the Application URL in config/app.php
     */
    'absolute' => false,

    /*
     * The Filter Method
     *
     * 'all' => All routes except "'routejs' => false"
     * 'only' => Only "'routejs' => true" routes
     * 'force' => All routes, ignored "routejs" route parameter
     */
    'filter' => 'all',

    /*
     * Controller Namespace
     *
     * Set here your controller namespace (see RouteServiceProvider -> $namespace) for cleaner action calls
     * e.g. 'App\Http\Controllers'
     */
    'action_namespace' => '',

    /*
     * The path to the template `routejs.js` file. This is the file that contains
     * the ported helper Laravel url/route functions and the route data to go
     * with them.
     */
    'template' => 'vendor/noonic/routejs/src/templates/routejs.js',
    
    /*
     * Appends a prefix to URLs. By default the prefix is an empty string.
    *
    */
    'prefix' => '',

];
