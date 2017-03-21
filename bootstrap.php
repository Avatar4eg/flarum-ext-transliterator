<?php
namespace Avatar4eg\Transliterator;

use Illuminate\Contracts\Events\Dispatcher;

return function (Dispatcher $events) {
    $events->subscribe(Listener\AddAdminParseApi::class);
    $events->subscribe(Listener\TransliterateUrl::class);
    $events->subscribe(Listener\AddClientAssets::class);
};