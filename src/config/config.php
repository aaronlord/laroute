<?php

return array(

    /**
     * The destination path for the javascript file.
     */
    'path' => 'public/js',

    /**
     * The destination filename for the javascript file.
     */
    'filename' => 'laroute',

    /**
     * The namespace for the helper functions. By default this will bind them to
     * `window.laroute`.
     */
    'namespace' => 'laroute',

	/**
	 * This allows to specify a filter for generated routes, if one is specified and you
	 * add the filter to the route or group of routes only this routes will be added to file,
	 * still, if route has laroute => false it will not be added
	 */
	'filter' => null,

    /**
     * The path to the template `laroute.js` file. This is the file that contains
     * the ported helper Laravel url/route functions and the route data to go
     * with them.
     */
    'template' => 'vendor/lord/laroute/src/Lord/Laroute/templates/laroute.min.js',

);
