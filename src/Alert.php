<?php

class Alert
{
    private static $names = [];

    public static function send($bot, $name, $value, $server, $limit = 90)
    {
        $name = strtoupper($name);
        if ($value > $limit) {
            if (!isset(self::$names[$name])) {
                $date = date("Y-m-d H:i:s");
                $text = Icons::ALERT . "[<b>{$date}</b>] <b>{$value}%</b> of";
                $text .= " <b>{$name}</b> on <b>{$server}</b>";
                Alert::$names[$name] = true;
                // $bot->send($text);
            }
        } else {
            unset(Alert::$names[$name]);
        }
    }
}
