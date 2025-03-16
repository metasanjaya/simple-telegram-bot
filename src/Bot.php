<?php declare(strict_types=1);

namespace Bot;

final class Bot {
    public string $name;
    public string $username;
    public string $startMessage;
    public array $buttons;

    public function __construct(string $name, string $username, string $startMessage, array $buttons) {
        $this->name = $name;
        $this->username = $username;
        $this->startMessage = $startMessage;
        $this->buttons = $buttons;
    }
}
