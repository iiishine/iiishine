<?php

if (!function_exists('app_set_message')) {
    /**
     * Add flash message to session.
     *
     * @param $text
     * @param string $type
     */
    function app_set_message($text, $type='info')
    {
        $messages = Session::get('larapp_messages', array());
        $messages[] = array(
            'text' => $text,
            'type' => $type,
        );

        Session::flash('larapp_messages', $messages);
    }
}
