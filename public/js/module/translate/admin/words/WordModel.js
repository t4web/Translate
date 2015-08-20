define([],
    function () {
        'use strict';

        return T4webApp.Model.extend({
            namespace: 'Translate\\Words\\WordsModel',

            urlRoot: "/admin/translate/ajax/word/save",

            id: null,

            defaults: {
                id: 0,
                langId: 0,
                key: '',
                translate: ''
            },

            initialize: function () {
                this.__debug('initialize');
            },

            validate: function(attrs, options) {
                if (attrs.end < attrs.start) {
                    return "can't end before it starts";
                }
            }

        });
    }
);
