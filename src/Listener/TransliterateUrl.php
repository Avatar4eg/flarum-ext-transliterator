<?php
namespace Avatar4eg\Transliterator\Listener;

use Flarum\Event\DiscussionWillBeSaved;
use Illuminate\Contracts\Events\Dispatcher;
use DirectoryIterator;
use Transliterator;

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
            $event->discussion->setAttribute('slug', self::transliterate($event->discussion->getAttribute('title')));
        }
    }

    /**
     * @param string $str
     * @param array $options
     * @return string
     */
    public static function transliterate($str, array $options = []) {
        // Check if Internationalization extension loaded
        $intlReady = extension_loaded('intl');

        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        // Default options
        $defaults = [
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => [],
            'transliterate' => true,
        ];

        // Merge options
        $options = array_merge($defaults, $options);

        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            if ($intlReady) {
                $transliterator = Transliterator::create('Any-Latin; Latin-ASCII');
                $str = $transliterator->transliterate($str);
            } else {
                $char_map = self::getMappers();
                $str = str_replace(array_keys($char_map), $char_map, $str);
            }
        }

        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ?: mb_strlen($str, 'UTF-8')), 'UTF-8');

        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        // Lowercase
        $str = $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;

        return $str === '' ? 'n-a' : $str;
    }

    public static function getMappers()
    {
        $char_map = [];
        foreach (new DirectoryIterator(__DIR__ . '/../../mapper') as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $char_map += include $file->getRealPath();
            }
        }
        return $char_map;
    }
}