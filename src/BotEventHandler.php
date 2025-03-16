<?php declare(strict_types=1);

namespace Bot;

use danog\MadelineProto\EventHandler\Attributes\Handler;
use danog\MadelineProto\EventHandler\Filter\FilterCommand;
use danog\MadelineProto\EventHandler\Message;
use danog\MadelineProto\EventHandler\SimpleFilter\Incoming;
use danog\MadelineProto\ParseMode;
use danog\MadelineProto\SimpleEventHandler;

use function Amp\File\read;

class BotEventHandler extends SimpleEventHandler {
    protected Bot $bot;

    public function setBot(Bot $bot) {
        $this->bot = $bot;
    }
    public function onStart(): void
    {
        $me = $this->getSelf();
        $config = json_decode(read("config.json"), true);
        $bot = new Bot(
            username: $me['username'],
            name: $me['first_name'],
            startMessage: $config["bots"][$me['username']]['startMessage'],
            buttons: $config["bots"][$me['username']]['buttons'],
        );

        $this->setBot($bot);

        $this->logger("The bot '@".$me['username']."' was started! ");
    }

    #[Handler]
    public function handleMessage(Incoming&Message $message): void
    {
        // delete all messages
        $message->delete(true);
    }

    #[FilterCommand('start')]
    public function startCmd(Message $message): void
    {
        $this->logger("Command /start received from chat ".$message->chatId." with args: ".json_encode($message->commandArgs));

        $startMessage = $this->bot->startMessage ?? "Hello! I'm a bot. You can use me to do things.";
        $rowButtons = [];

        foreach ($this->bot->buttons as $button) {
            $rowButtons[] = [
                '_' => 'keyboardButtonRow',
                'buttons' => [
                    ['_' => 'keyboardButtonWebView', 'text' => $button['text'], 'url' => $button['url']]
                ]
            ];
        }

        $this->sendMessage(
            peer: $message->chatId,
            message: $startMessage,
            parseMode: ParseMode::MARKDOWN,
            replyMarkup: [
                '_' => 'replyInlineMarkup',
                'rows' => $rowButtons
            ]
        );
    }
}