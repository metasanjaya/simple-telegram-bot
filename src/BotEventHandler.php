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
    protected ?Message $lastMessage = null;

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
        try {
            if (!is_null($this->lastMessage)) {
                // delete last message
                $this->lastMessage->delete(true);
            }
            
            $this->lastMessage = $this->replyChat($message->chatId);
            $message->delete(true);
        } catch(\Exception $e) {
            $this->logger("Error: ".$e->getMessage());
        }
    }

    // #[FilterCommand('start')]
    // public function startCmd(Message $message): void
    // {
    //     $this->logger("Command /start received from chat ".$message->chatId." with args: ".json_encode($message->commandArgs));

    //     $this->replyChat($message->chatId);
    //     $this->sleep(1);
    //     $message->delete(true);
    // }

    private function replyChat(int $chatId): Message
    {
        $startMessage = $this->bot->startMessage ?? "Hello! I'm a bot. You can use me to do things.";
        $rowButtons = [];

        foreach ($this->bot->buttons as $button) {
            $buttonClass = "keyboardButtonWebView";

            if (preg_match("/t\.me/", $button['url'])) {
                $buttonClass = "keyboardButtonUrl";
            }

            $rowButtons[] = [
                '_' => 'keyboardButtonRow',
                'buttons' => [
                    ['_' => $buttonClass, 'text' => $button['text'], 'url' => $button['url']]
                ]
            ];
        }

        return $this->sendMessage(
            peer: $chatId,
            message: $startMessage,
            parseMode: ParseMode::HTML,
            replyMarkup: [
                '_' => 'replyInlineMarkup',
                'rows' => $rowButtons
            ]
        );
    }
}