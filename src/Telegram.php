<?php

class TelegramStats
{
    public $time = "0h";
    public $rate = "...";
}

class Telegram
{
    public $stats;
    private $token;
    private $chat_id;
    private $started;
    private $count = 0;

    private const API_SEND_URL = "https://api.telegram.org/bot%s/sendMessage?chat_id=%s&parse_mode=html&text=%s";
    private const API_EDIT_URL = "https://api.telegram.org/bot%s/editMessageText?chat_id=%s&parse_mode=html&message_id=%s&text=%s";

    public function __construct($token, $chat_id)
    {
        $this->stats = new TelegramStats();
        $this->chat_id = $chat_id;
        $this->token = $token;
    }

    public function send($text, $title = null)
    {
        $this->status();
        if (!$this->started) $this->started = time();
        if (!is_null($title)) $text = "<b>{$title}:</b>\n\n{$text}";
        $url = sprintf(self::API_SEND_URL, $this->token, $this->chat_id, urlencode($text));

        return $this->get_id(file_get_contents($url));
    }

    public function edit($id, $text, $title = null)
    {
        $this->status();
        if (!is_null($title)) $text = "<b>{$title}:</b>\n\n{$text}";
        $url = sprintf(self::API_EDIT_URL, $this->token, $this->chat_id, $id, urlencode($text));

        return $this->get_id(file_get_contents($url));
    }

    public function get_id($result)
    {
        try {
            $data = json_decode($result);
            if ($data) {
                return $data->result->message_id;
            } else {
                throw new Exception("{$result}");
            }
        } catch (Exception $ex) {
            echo "[ERROR] {$ex->getMessage()}\n";
        }
    }

    public function status()
    {
        $this->count += 1;
        if ($this->started) {
            $elapsed = time() - $this->started;
            if ($elapsed > 0) {
                $this->stats->rate = ($this->count / $elapsed) * 60;
                $this->stats->rate = number_format($this->stats->rate, 2) . "/min";
                $this->stats->time = number_format(($elapsed / 60) / 60, 2) . "/h";
            }
        }
    }
}
