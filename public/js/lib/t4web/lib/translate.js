(function($) {
    'use strict';

    var translate = {

        locale: 'en',

        messages: [],

        initialize: function (options) {
            options || (options = {});
            _.extend(this, options);
        },

        /**
         *
         * @param messages
         */
        load: function(messages) {
            this.messages = messages;
        },

        /**
         * Translate namespace: public properties and methods
         *
         * @param messageID
         * @returns {*}
         */
        _: function (message, locale) {

            var locale = (locale) ? locale : this.getLocale();

            if (this.messages[locale] == undefined) {
                loadMessages(locale);
            }
            if (this.messages[locale] && this.messages[locale].hasOwnProperty(message)) {
                message = this.messages[locale][message];
            }

            return message;
        },

        /**
         * Get translate locale
         *
         * @returns {string}
         */
        getLocale: function() {
            return this.locale;
        },

        /**
         * Set translate locale
         *
         * @param locale
         */
        setLocale: function(locale) {
            this.locale = locale;
        },

        /**
         * Get all messages
         *
         * @returns {*}
         */
        getMessages: function() {
            return this.messages;
        }
    };

    var loadMessages = function(locale) {
        var _this = translate;

        if(_this.messages[locale] == undefined) {
            _this.messages[locale] = [];
        }
    };

    $.translate = translate;
})($);
