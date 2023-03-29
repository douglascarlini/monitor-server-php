<?php

# ERROR HANDLING

function error_handler($code, $text)
{
    $line = "[" . date("Y-m-d H:i:s") . "]{$code}:";
    $f = fopen("error.log", "a+");
    $line = "{$line} {$text}\n";
    fwrite($f, $line);
    fclose($f);
}

# LOAD ENV VARIABLES

function check_configs()
{
    try {
        define("NAME", getenv("NAME"));
        define("DISK", getenv("DISK"));
        define("API_KEY", getenv("API_KEY"));
        define("CHAT_ID", getenv("CHAT_ID"));
        define("ASTERISK", getenv("ASTERISK"));
        define("ADMIN_CHAT_ID", getenv("ADMIN_CHAT_ID"));

        if (!NAME || strlen(NAME) == 0) throw new Exception("NAME not defined");
        if (!DISK || strlen(DISK) == 0) throw new Exception("DISK not defined");
        if (!API_KEY || strlen(API_KEY) == 0) throw new Exception("API_KEY not defined");
        if (!CHAT_ID || strlen(CHAT_ID) == 0) throw new Exception("CHAT_ID not defined");
        if (!ASTERISK || strlen(ASTERISK) == 0) throw new Exception("ASTERISK not defined");
        if (!ADMIN_CHAT_ID || strlen(ADMIN_CHAT_ID) == 0) throw new Exception("ADMIN_CHAT_ID not defined");
    } catch (Exception $ex) {
        error_handler(0, $ex->getMessage());
        die($ex->getMessage() . "\n");
    }
}

# RUN

set_error_handler("error_handler");
$error = check_configs();
