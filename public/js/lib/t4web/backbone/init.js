define(
    [
        'lib/t4web/lib/debug',
        'lib/t4web/backbone/Router',
        'validation'
    ],
    function (Debug, Router) {
        T4webApp.Router = Backbone.Router.extend(Router);
        T4webApp.View = Backbone.View.extend(Debug);
        T4webApp.Model = Backbone.Model.extend(Debug);
        T4webApp.Collection = Backbone.Collection.extend(Debug);
        T4webApp.Event = {};

        _.extend(T4webApp.Event, Backbone.Events);

        // Backbone.Validation
        _.extend(Backbone.Validation.validators, {
            terms: function (value, attr, customValue, model) {
                var el = $('[name="' + attr + '"]');

                if (el.is(':checked') === false) {
                    return Backbone.Validation.messages.terms;
                }
            },
            phone: function (value, attr, customValue, model) {
                if(!value) {
                    return;
                }

                if (!value.toString().match(Backbone.Validation.patterns['phonePattern'])) {
                    return Backbone.Validation.messages.phonePattern;
                }
                var el = $('[name="' + attr + '"]');

                require([
                    'module/users/view/user/ValidationPhoneView'
                ], function(ValidationPhoneView) {
                    new ValidationPhoneView({phone: value});

                    T4webApp.Event.on('phone:confirmed', function() {
                        el.attr('disabled', true);
                    });

                });

            }
        });

        _.extend(Backbone.Validation.patterns, {
            phonePattern: /^\+38 \(\d{3}\) \d{3}-\d{4}$/
        });

        _.extend(Backbone.Validation.callbacks, {
            valid: function (view, attr, selector) {
                var $el = view.$('[name=' + attr + ']');
                var $group = $el.closest('.form-group');
                if (!$group.length) {
                    $group = $el.closest('.input-group');
                }

                $group.removeClass('has-error');
                $group.find('.input-error').remove();
            },
            invalid: function (view, attr, error, selector) {
                var $el = view.$('[name=' + attr + ']');
                var $group = $el.closest('.form-group');
                if (!$group.length) {
                    $group = $el.closest('.input-group');
                }
                $group.find('.input-error').remove();

                if (attr == 'terms') {
                    $group.append('<div class="input-error"><div>');
                } else {
                    $('<div class="input-error"><div>').insertAfter($el);
                }

                $group.addClass('has-error');
                $group.find('.input-error').html(error);
            }
        });

    });
