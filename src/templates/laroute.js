export default {

    absolute: $ABSOLUTE$,
    rootUrl: '$ROOTURL$',
    routes: $ROUTES$,
    prefix: '$PREFIX$',

    route: function (name, parameters, route) {
        route = route || this.getByName(name);

        if (!route) {
            return undefined;
        }

        return this.toRoute(route, parameters);
    },

    url: function (url, parameters) {
        parameters = parameters || [];

        var uri = url + '/' + parameters.join('/');

        return this.getCorrectUrl(uri);
    },

    method: function (name) {
        let route = this.getByName(name);

        return route.methods;
    },

    toRoute: function (route, parameters) {
        var uri = this.replaceNamedParameters(route.uri, parameters);
        var qs = this.getRouteQueryString(parameters);

        if (this.absolute && this.isOtherHost(route)) {
            return "//" + route.host + "/" + uri + qs;
        }

        return this.getCorrectUrl(uri + qs);
    },

    isOtherHost: function (route) {
        return route.host && route.host != window.location.hostname;
    },

    replaceNamedParameters: function (uri, parameters) {
        uri = uri.replace(/\{(.*?)\??\}/g, function (match, key) {
            if (parameters.hasOwnProperty(key)) {
                var value = parameters[key];
                delete parameters[key];
                return value;
            } else {
                return match;
            }
        });

        // Strip out any optional parameters that were not given
        uri = uri.replace(/\/\{.*?\?\}/g, '');

        return uri;
    },

    getRouteQueryString: function (parameters) {
        var qs = [];
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

    getByName: function (name) {
        for (var key in this.routes) {
            if (this.routes.hasOwnProperty(key) && this.routes[key].name === name) {
                return this.routes[key];
            }
        }
    },

    getByAction: function (action) {
        for (var key in this.routes) {
            if (this.routes.hasOwnProperty(key) && this.routes[key].action === action) {
                return this.routes[key];
            }
        }
    },

    getCorrectUrl: function (uri) {
        var url = this.prefix + '/' + uri.replace(/^\/?/, '');

        if (!this.absolute) {
            return url;
        }

        return this.rootUrl.replace('/\/?$/', '') + url;
    },

    request(name, parameters, data, route) {
        route = route || this.getByName(name);

        if ( ! route ) {
            return undefined;
        }

        return {
            url: this.toRoute(route, parameters),
            method: route.methods[0],
            data: data || {}
        };
    }
};
