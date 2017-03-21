'use strict';

System.register('avatar4eg/transliterator/components/TransliteratorSettingsModal', ['flarum/components/SettingsModal', 'flarum/components/Button'], function (_export, _context) {
    "use strict";

    var SettingsModal, Button, TransliteratorSettingsModal;
    return {
        setters: [function (_flarumComponentsSettingsModal) {
            SettingsModal = _flarumComponentsSettingsModal.default;
        }, function (_flarumComponentsButton) {
            Button = _flarumComponentsButton.default;
        }],
        execute: function () {
            TransliteratorSettingsModal = function (_SettingsModal) {
                babelHelpers.inherits(TransliteratorSettingsModal, _SettingsModal);

                function TransliteratorSettingsModal() {
                    babelHelpers.classCallCheck(this, TransliteratorSettingsModal);
                    return babelHelpers.possibleConstructorReturn(this, (TransliteratorSettingsModal.__proto__ || Object.getPrototypeOf(TransliteratorSettingsModal)).apply(this, arguments));
                }

                babelHelpers.createClass(TransliteratorSettingsModal, [{
                    key: 'className',
                    value: function className() {
                        return 'TransliteratorSettingsModal Modal--small';
                    }
                }, {
                    key: 'title',
                    value: function title() {
                        return app.translator.trans('avatar4eg-transliterator.admin.settings.modal_title');
                    }
                }, {
                    key: 'submitButton',
                    value: function submitButton() {
                        return [Button.component({
                            type: 'submit',
                            className: 'Button Button--primary TransliteratorSettingsModal-parse',
                            loading: this.loading,
                            children: app.translator.trans('avatar4eg-transliterator.admin.settings.parse_button')
                        })];
                    }
                }, {
                    key: 'onsubmit',
                    value: function onsubmit(e) {
                        e.preventDefault();

                        this.loading = true;

                        app.request({
                            method: 'POST',
                            url: app.forum.attribute('apiUrl') + '/parse-slug'
                        }).then(this.onsaved.bind(this), this.loaded.bind(this));
                    }
                }]);
                return TransliteratorSettingsModal;
            }(SettingsModal);

            _export('default', TransliteratorSettingsModal);
        }
    };
});;
'use strict';

System.register('avatar4eg/transliterator/main', ['flarum/app', 'avatar4eg/transliterator/components/TransliteratorSettingsModal'], function (_export, _context) {
    "use strict";

    var app, TransliteratorSettingsModal;
    return {
        setters: [function (_flarumApp) {
            app = _flarumApp.default;
        }, function (_avatar4egTransliteratorComponentsTransliteratorSettingsModal) {
            TransliteratorSettingsModal = _avatar4egTransliteratorComponentsTransliteratorSettingsModal.default;
        }],
        execute: function () {

            app.initializers.add('avatar4eg-transliterator', function () {
                app.extensionSettings['avatar4eg-transliterator'] = function () {
                    return app.modal.show(new TransliteratorSettingsModal());
                };
            });
        }
    };
});