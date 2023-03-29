<?php

require "src/config.php";

require "src/Telegram.php";
require "src/Monitor.php";
require "src/Icons.php";
require "src/Alert.php";
require "src/Bash.php";

# DEFINITIONS

$id = 0;
$delay = 5;
$title = NAME;
$ast_limit = 8;

$mon = new Monitor();
$bot = new Telegram(API_KEY, CHAT_ID);

# MAIN PROGRAM

while (true) {

    $changes = false;

    # READ HARDWARE STATS

    $disk = $mon->disk(DISK);
    $diskBar = $mon->bar($disk, 100, 90, 60);
    Alert::send($bot, "disk", $disk, $title);

    $cpu = $mon->cpu();
    $cpuBar = $mon->bar($cpu, 100, 90, 50);
    Alert::send($bot, "cpu", $cpu, $title);

    $mem = $mon->mem();
    $memBar = $mon->bar($mem, 100, 90, 70);
    Alert::send($bot, "mem", $mem, $title);

    # READ ASTERISK STATS

    if (ASTERISK == "1") {
        $ast_f2b = (int)shell_exec(Bash::AST_F2B);
        $ast_in_use = (int)shell_exec(Bash::AST_IN_USE);
        $ast_unavailable = (int)shell_exec(Bash::AST_UNAVAILABLE);
        // if ($ast_in_use > $ast_limit) $bot->send(Icons::ALERT . " Limit <b>CALLS</b> alert on <b>{$title}</b>: {$ast_in_use}");
    }

    # CHECK CHANGES

    if (ASTERISK == "1") {
        $changes = $changes ? true : ($ast_unavailable != $mon->get_value("ast_unavailable"));
        $changes = $changes ? true : ($ast_in_use != $mon->get_value("ast_in_use"));
        $changes = $changes ? true : ($ast_f2b != $mon->get_value("ast_f2b"));
    }

    $changes = $changes ? true : ($disk != $mon->get_value("disk"));
    $changes = $changes ? true : ($cpu != $mon->get_value("cpu"));
    $changes = $changes ? true : ($mem != $mon->get_value("mem"));

    # SSD REPORT

    if ($changes) {

        # HARDWARE INFO

        $text = "HARDWARE:\n";

        $text .= "\nSSD: {$diskBar} {$disk}%";
        $text .= "\nCPU: {$cpuBar} {$cpu}%";
        $text .= "\nRAM: {$memBar} {$mem}%";

        $text .= "\n";

        # ASTERISK INFO

        if (ASTERISK == "1") {
            $text .= "\n<b>ASTERISK:</b>\n";
            $text .= "\nF2B: {$mon->bar($ast_f2b, 10)} {$ast_f2b}/10";
            $text .= "\nUSE: {$mon->bar($ast_in_use, 20)} {$ast_in_use}/20";
            $text .= "\nOFF: {$mon->bar($ast_unavailable)} {$ast_unavailable}/99";
            $text .= "\n";
        }

        # CALC RATE

        $text .= "\nDETAILS:\n";
        $text .= "\nRunning Time: {$bot->stats->time}";
        $text .= "\nTelegram Rate: {$bot->stats->rate}";
        $text .= "\nUpdated: " . date("Y-m-d H:i:s");

        # SEND REPORT

        if ($id == 0)
            $id = $bot->send("<pre>{$text}</pre>", $title);
        else
            $bot->edit($id, "<pre>{$text}</pre>", $title);
    }

    # UPDATE VALUES

    if (ASTERISK == "1") {
        $mon->set_value("ast_unavailable", $ast_unavailable);
        $mon->set_value("ast_in_use", $ast_in_use);
        $mon->set_value("ast_f2b", $ast_f2b);
    }

    $mon->set_value("disk", $disk);
    $mon->set_value("cpu", $cpu);
    $mon->set_value("mem", $mem);

    # DELAY
    sleep($delay);
}

$bot->send(Icons::ALERT . " <b>BOT PARADO!</b>");
