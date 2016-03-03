<?php

if (!function_exists('eventup_url')) {
    function eventup_url($filename, $eventId = null)
    {
        $eventup = App::make('Bigecko\YD\HGCommon\Api\Eventup');
        if (!is_null($eventId)) {
            $eventup->setEventId($eventId);
        }
        return $eventup->fullurl($filename);
    }
}

if (!function_exists('event_text')) {
    function event_text($key, $area=null, $vars = null)
    {
        if ($area == null) {
            $area = Config::get('eventpage.act_area');
        }
        $eventup = App::make('Bigecko\YD\HGCommon\Api\EventBackend');
        return $eventup->text($key, $area, $vars);
    }
}
