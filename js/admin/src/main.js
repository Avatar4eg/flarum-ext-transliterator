import app from 'flarum/app';

import TransliteratorSettingsModal from 'avatar4eg/transliterator/components/TransliteratorSettingsModal';

app.initializers.add('avatar4eg-transliterator', () => {
    app.extensionSettings['avatar4eg-transliterator'] = () => app.modal.show(new TransliteratorSettingsModal());
});