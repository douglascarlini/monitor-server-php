<?php

require "src/config.php";

require "src/Telegram.php";
require "src/Icons.php";

$log_path = "/root/error.log";
$bot = new Telegram(API_KEY, ADMIN_CHAT_ID);

# CHECK IF MAIN PROGRAM IS RUNNING EVERY 5 MIN
# IF IT'S NOT RUNNING SEND ERROR TO ADMIN CHAT

while (true) {

    $msg = Icons::ALERT . " " . NAME . " Monitor stopped!";
    $num = (int)shell_exec("ps aux | grep \"php index.php\" | wc -l");
    $txt = shell_exec("if [ -f {$log_path} ]; then cat {$log_path}; else echo {$msg}; fi");

    if ($num < 3) {
        $bot->send($txt);
        exit;
    }

    sleep(5);
}
