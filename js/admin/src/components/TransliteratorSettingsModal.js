import SettingsModal from 'flarum/components/SettingsModal';
import Button from 'flarum/components/Button';

export default class TransliteratorSettingsModal extends SettingsModal {
    className() {
        return 'TransliteratorSettingsModal Modal--small';
    }

    title() {
        return app.translator.trans('avatar4eg-transliterator.admin.settings.modal_title');
    }

    submitButton() {
        return [
            Button.component({
                type: 'submit',
                className: 'Button Button--primary TransliteratorSettingsModal-parse',
                loading: this.loading,
                children: app.translator.trans('avatar4eg-transliterator.admin.settings.parse_button')
            })
        ];
    }

    onsubmit(e) {
        e.preventDefault();

        this.loading = true;

        app.request({
            method: 'POST',
            url: app.forum.attribute('apiUrl') + '/parse-slug'
        }).then(
            this.onsaved.bind(this),
            this.loaded.bind(this)
        );
    }
}
