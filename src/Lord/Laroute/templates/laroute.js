(function () {

    var laroute = (function () {

        var routes = {

            routes : $ROUTES$,

            route : function (name, parameters, route) {
                route = route || this.getByName(name);

                if ( ! route ) {
                    return undefined;
                }

                return this.toRoute(route, parameters);
            },

            toRoute : function (route, parameters) {
                uri = this.replaceNamedParameters(route.uri, parameters);
                qs  = this.getRouteQueryString(parameters);
                return '/' + uri.replace(/^\/?/, '') + qs;
            },

            replaceNamedParameters : function (uri, parameters) {
                return uri.replace(/\{(.*?)\??\}/g, function(match, key) {
                    if (parameters.hasOwnProperty(key)) {
                        value = parameters[key];
                        delete parameters[key];
                        return value;
                    }
                });
            },

            getRouteQueryString : function (parameters) {
                qs = [];
                for (var key in parameters) {
                    if (parameters.hasOwnProperty(key)) {
                        qs.push(key + '=' + parameters[key]);
                    }
                }

                if (qs.length < 1) {
                    return '';
                }

                return '?' + qs.join('&');
            },

            getByName : function (name) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                        return this.routes[key];
                    }
                }
            },

            getByAction : function(action) {
                for (var key in this.routes) {
                    if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                        return this.routes[key];
                    }
                }
            }
        };

        var getLinkAttributes = function(attributes) {
            if ( ! attributes) {
                return '';
            }

            attrs = [];
            for (var key in attributes) {
                if (attributes.hasOwnProperty(key)) {
                    attrs.push(key + '="' + attributes[key] + '"');
                }
            }

            return attrs.join(' ');
        };

        return {
            // Generate a url for a given controller action.
            // $NAMESPACE$.action('HomeController@getIndex', [params = {}])
            action : function (name, parameters) {
                parameters = parameters || {};

                return routes.route(name, parameters, routes.getByAction(name));
            },

            // Generate a url for a given named route.
            // $NAMESPACE$.route('routeName', [params = {}])
            route : function (route, parameters) {
                parameters = parameters || {};

                return routes.route(route, parameters);
            },

            // Generate a html link to the given url.
            // $NAMESPACE$.link_to('foo/bar', [title = url], [attributes = {}])
            link_to : function (url, title, attributes) {
                url        = '/' + url.replace(/^\/?/, '');
                title      = title || url;
                attributes = getLinkAttributes(attributes);

                return '<a href="' + url + '" ' + attributes + '>' + title + '</a>';
            },

            // Generate a html link to the given route.
            // $NAMESPACE$.link_to_route('route.name', [title=url], [parameters = {}], [attributes = {}])
            link_to_route : function (route, title, parameters, attributes) {
                uri = this.route(route, parameters);

                title      = title || uri;
                parameters = parameters || {};
                attributes = attributes || {};

                return this.link_to(uri, title, attributes);
            },

            // Generate a html link to the given controller action.
            // $NAMESPACE$.link_to_action('HomeController@getIndex', [title=url], [parameters = {}], [attributes = {}])
            link_to_action : function(action, title, parameters, attributes) {
                uri = this.action(action, parameters);

                title      = title || uri;
                parameters = parameters || {};
                attributes = attributes || {};

                return this.link_to(uri, title, attributes);
            }

        };

    }).call(this);

    window.$NAMESPACE$ = laroute;

}).call(this);

