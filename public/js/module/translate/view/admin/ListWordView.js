define([], function() {
    'use strict';

    return T4webApp.View.extend({
        namespace: 'Translate\\View\\Admin\\ListWordView',

        el: 'table tr',
        changed: false,

        events: {
            'click .save-word': 'save'
        },

        initialize: function () {
            this.__debug('initialize');

            this.listenTo(this.model, 'sync', this.modelSaved);
            this.listenTo(this.model, 'error', this.modelErrors);
        },

        save: function (e) {
            this.__debug('save');

            this.$element = $(e.target).closest('tr');
            var data = this.$element.find(':input').serializeObject();

            this.model.save(data);
        },

        modelSaved: function () {
            this.__debug('modelSaved');

            $.notify({
                title: '<strong>Изменение перевода!</strong>',
                message: 'Перевод сохранен.'
            }, {type: 'success'});

            this.$el.find('ul.help-block').remove();
            this.$el.find('.has-error').removeClass('has-error');
        },

        modelErrors: function (model, response, options) {
            this.__debug('modelErrors');

            var _this = this;

            this.$el.find('ul.help-block').remove();
            this.$el.find('.has-error').removeClass('has-error');

            var errors = response.responseJSON.errors;

            $.each(errors, function(attr, values) {
                var el = _this.$element.find('[name=' + attr + ']');

                el.parent().addClass('has-error');

                var messages = '<ul class="help-block">';
                $.each(values, function(key, error) {
                    messages += '<li>' + error + '</li>';
                });
                messages += '</ul>';

                $(messages).insertAfter(el);
            });
        }

    });

});