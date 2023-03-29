<?php

class Monitor
{
    private const NUL_CHAR = "\u{2591}";
    private const BAR_CHAR = "\u{2588}";

    private $values = [];

    public function set_value($name, $value)
    {
        return $this->values[$name] = $value;
    }

    public function get_value($name)
    {
        return isset($this->values[$name]) ? $this->values[$name] : 0;
    }

    public function disk($disk)
    {
        $text = shell_exec(sprintf(Bash::DISK_USAGE, $disk));
        $value = (int)str_replace('%', '', $text);
        return $value;
    }

    public function mem()
    {
        $text = shell_exec(Bash::MEM_USAGE);
        return (int)str_replace('%', '', $text);
    }

    public function cpu()
    {
        $text = shell_exec(Bash::CPU_USAGE);
        return (int)str_replace('%', '', $text);
    }

    public function net()
    {
        $text = shell_exec(Bash::NET_USAGE);
        return (int)str_replace('%', '', $text);
    }

    public static function bar($value, $max = 100, $crit = 90, $warn = 70, $size = 10)
    {
        $percent = $value * 100 / $max;
        $total = (int)(($value * $size) / $max);
        $bar = str_repeat(self::BAR_CHAR, $total);
        $icon = ($percent > $crit) ? Icons::ALERT : (($percent > $warn) ? Icons::WARNING : Icons::OK);
        return $bar . str_repeat(self::NUL_CHAR, ($size - $total)) . " {$icon}";
    }
}
