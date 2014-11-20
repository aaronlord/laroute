# Laroute

[Laravel](http://laravel.com/) has some pretty sweet [helper functions](http://laravel.com/docs/helpers#urls) for generating urls/links and its auto-json-magic makes it building APIs super easy. It's my go-to choice for building single-page js apps, but routing can quickly become a bit of a pain.

Wouldn't it be amazing if we could access our Laravel routes from JavaScript?

This package allows us to port our routes over to JavaScript, and gives us a bunch of _very familiar_ helper functions to use.

![Laroute in action](laroute.png)

## Installation

Install the usual [composer](https://getcomposer.org/) way.

###### package.json
```json
{
	"require" : {
		"lord/laroute" : "1.*"
	}
}
```

###### app/config/app.php
```php
	...
	
	'providers' => array(
		...
		'Lord\Laroute\LarouteServiceProvider',
	],
	
	...
```

### Configure (optional)

Copy the packages config files.

```
php artisan config:publish lord/laroute
```

###### app/config/packages/lord/laroute/config.php

```php
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
     * The path to the template `laroute.js` file. This is the file that contains
     * the ported helper Laravel url/route functions and the route data to go
     * with them.
     */
    'template' => 'vendor/lord/laroute/src/Lord/Laroute/templates/laroute.min.js',

);
```

### Generate the `laroute.js`

To access the routes, we need to "port" them over to a JavaScript file:

```
php artisan generate:laroute
```

With the default configuration, this will create a `public/js/laroute.js` file to include in your page, or build.

```html
<script src="/js/laroute.js"></script>
```

**Note: You'll have to `generate:laroute` if you change your routes.**

## JavaScript Documentation

By default, all of the functions are under the `laroute` namespace. This documentation will stick with this convention.


### action

Generate a URL for a given controller action. 

```js
/** 
 * laroute.action(action, [parameters = {}])
 *
 * action     : The action to route to.
 * parameters : Optional. key:value object literal of route parameters.
 */

laroute.action('HomeController@getIndex');
```

### route

Generate a URL for a given named route.

```js
/**
 * laroute.route(name, [parameters = {}])
 *
 * name       : The name of the route to route to.
 * parameters : Optional. key:value object literal of route parameters.
 */
 
 laroute.route('Hello.{planet}', { planet : 'world' });
```

### link_to

Generate a html link to the given url.

```js
/**
 * laroute.link_to(url, [title = url, attributes = {}]])
 *
 * url        : A relative url.
 * title      : Optional. The anchor text to display
 * attributes : Optional. key:value object literal of additional html attributes.
 */
 
 laroute.link_to('foo/bar', 'Foo Bar', { style : "color:#bada55;" });
```

### link_to_route

Generate a html link to the given route.

```js
/**
 * laroute.link_to_route(name, [title = url, parameters = {}], attributes = {}]]])
 *
 * name       : The name of the route to route to.
 * title      : Optional. The anchor text to display
 * parameters : Optional. key:value object literal of route parameters.
 * attributes : Optional. key:value object literal of additional html attributes.
 */
 
 laroute.link_to_route('home', 'Home');
```

### link_to_action

Generate a html link to the given action.

```js
/**
 * laroute.link_to_action(action, [title = url, parameters = {}], attributes = {}]]])
 *
 * action     : The action to route to.
 * title      : Optional. The anchor text to display
 * parameters : Optional. key:value object literal of route parameters.
 * attributes : Optional. key:value object literal of additional html attributes.
 */
 
 laroute.link_to_action('HelloController@planet', undefined, { planet : 'world' });
```

## PHP Documentation

### Ignore/Filter Routes

By default, all routes are available to laroute after a `php artisan laroute:generate`. However, it is sometimes desirable to have laroute ignore certain routes. You can do this by passing a `laroute` route option.

```php
Route::get('/ignore-me', [
    'laroute' => false,
    'as'      => 'ignoreme',
    'uses'    => 'IgnoreController@me'
]);

Route::group(['laroute' => false], function () {
    Route::get('/groups-are-super-useful', 'GroupsController@index');
});

```

Another way to filter your routes is to set the ```filter``` option in config file so only the routes with specific key will be added to the laroute. Same key can be applied to group to add every route in it.
###### app/config/packages/lord/laroute/config.php
```php
<?php
return [
    'filter' => 'jsroutes'
];
```

###### app/routes.php
```php
Route::get('/i-will-be-added', [
    'jsroutes' => true,
    'as'      => 'route',
    'uses'    => 'Controller@me'
]);
Route::get('/i-wont-be-added', [
    'as'      => 'route',
    'uses'    => 'Controller@me'
]);
Route::get('/i-wont-be-added-too', [
    'jsroutes' => true,
    'laroute' => false,
    'as'      => 'route',
    'uses'    => 'Controller@me'
]);
```
## Licence

[View the licence in this repo.](https://github.com/aaronlord/laroute/blob/master/LICENSE)
