define([], function() {
    'use strict';

    var Debug = {

        isDebug: false,
        namespace: '[Object]',

        __debug: function (msg, comment) {
            comment = comment || '';
            var _this = this;

            if (this.isDebug && console != null) {

                if (typeof msg['getResponseHeader'] === 'function') {
                    msg
                        .always(function () {
                            if (arguments[2] && arguments[2].message != '') return;
                            console['info'](comment, arguments);
                        })
                        .fail(function () {
                            var message = _this.ucfirst(arguments[1]) + ':  "' + arguments[2].message + '"';
                            console['error'](comment, message, arguments);
                        });
                    return;
                }

                if (typeof msg == 'object') {
                    console.log(msg);
                    return;
                }

                console.log(this.namespace + ':' + msg);
            }
        },

        ucfirst: function (str) {
            str += '';
            return str.charAt(0).toUpperCase() + str.substr(1);
        }
    };

    return Debug;
});