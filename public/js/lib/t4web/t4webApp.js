
(function(factory) {
    var root = (typeof self == 'object' && self.self == self && self) ||
        (typeof global == 'object' && global.global == global && global);

    root.T4webApp = factory(this, {}, {});

}(function(root, T4webApp) {

    T4webApp = {
        namespace: 'T4webApp',
        version: 1.00,

        config: {
            debug: {
                isDebug: false
            }
        },

        app: {},

        initialize: function (options) {
            options || (options = {});
            _.extend(this, options);
        }
    };

    $.fn.serializeObject = function () {
        "use strict";
        var a = {}, b = function (b, c) {
            var d = a[c.name];
            "undefined" != typeof d && d !== null ? $.isArray(d) ? d.push(c.value) : a[c.name] = [d, c.value] : a[c.name] = c.value
        };
        return $.each(this.serializeArray(), b), a
    };

    return T4webApp;
}));

define(['lib/t4web/lib/debug', 'lib/t4web/config/app.config'], function(Debug, Config) {
    _.extend(T4webApp.config, Config);
    _.extend(Debug, Config.debug);
    _.extend(T4webApp, Debug);
});
