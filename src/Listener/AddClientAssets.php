<?php 
namespace Avatar4eg\Transliterator\Listener;

use DirectoryIterator;
use Flarum\Event\ConfigureWebApp;
use Flarum\Event\ConfigureLocales;
use Illuminate\Contracts\Events\Dispatcher;

class AddClientAssets
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureWebApp::class, [$this, 'addAssets']);
        $events->listen(ConfigureLocales::class, [$this, 'addLocales']);
    }

    /**
     * @param ConfigureWebApp $event
     */
    public function addAssets(ConfigureWebApp $event)
    {
        if ($event->isAdmin()) {
            $event->addAssets([
                __DIR__ . '/../../js/admin/dist/extension.js',
            ]);
            $event->addBootstrapper('avatar4eg/transliterator/main');
        }
    }

    /**
     * @param ConfigureLocales $event
     */
    public function addLocales(ConfigureLocales $event)
    {
        foreach (new DirectoryIterator(__DIR__ .'/../../locale') as $file) {
            if ($file->isFile() && in_array($file->getExtension(), ['yml', 'yaml'], false)) {
                $event->locales->addTranslations($file->getBasename('.' . $file->getExtension()), $file->getPathname());
            }
        }
    }
}
