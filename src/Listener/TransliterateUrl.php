<?php
namespace Avatar4eg\Transliterator\Listener;

use Behat\Transliterator\Transliterator;
use Flarum\Event\DiscussionWillBeSaved;
use Illuminate\Contracts\Events\Dispatcher;

class TransliterateUrl
{
    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(DiscussionWillBeSaved::class, [$this, 'createProperSlug']);
    }

    /**
     * @param DiscussionWillBeSaved $event
     */
    public function createProperSlug(DiscussionWillBeSaved $event)
    {
        if (array_key_exists('title', $event->data['attributes'])) {
            $title = $event->discussion->getAttribute('title');
            $slug = Transliterator::transliterate($title);
            $event->discussion->setAttribute('slug', $slug);
        }
    }
}