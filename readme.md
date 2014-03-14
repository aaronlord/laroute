# Laroute - Laravel Routes to JS

This package gives js the handful of helper routing functions available to Laravel.

## Installation

###### package.json
```
{
	"require" : {
		"lord/laroute" : "1.0"
	}

}

```

###### app/config/app.php
```
	...
	
	'providers' => array(
		...
		'Lord\Laroute\LarouteServiceProvider',
	],
	
	...
```

## Configure (optional)


###### app/config/packages/lord/laroute/config.php

```
<?php

return array(

    'template' => 'vendor/lord/laroute/src/Lord/Laroute/templates/laroute.min.txt',

    'path'     => 'public/js',

    'filename' => 'laroute',

    'namespace' => 'laroute',

);

```

## Generate laroute.js

```
php artisan generate:laroute
```