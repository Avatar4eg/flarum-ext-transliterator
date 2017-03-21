<?php
namespace Avatar4eg\Transliterator\Listener;

use Avatar4eg\Transliterator\Api\Controller\ParseSlugController;
use Flarum\Event\ConfigureApiRoutes;
use Illuminate\Events\Dispatcher;

class AddAdminParseApi
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(ConfigureApiRoutes::class, [$this, 'configureApiRoutes']);
    }

    /**
     * @param ConfigureApiRoutes $event
     */
    public function configureApiRoutes(ConfigureApiRoutes $event)
    {
        $event->post('/parse-slug', 'avatar4eg.transliterator.parse-slug', ParseSlugController::class);
    }
}
