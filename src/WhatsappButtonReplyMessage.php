<?php

namespace NotificationChannels\Whatsapp;

use Illuminate\Support\Arr;
use NotificationChannels\Whatsapp\Components\ButtonReply\ButtonReply;
use NotificationChannels\Whatsapp\Components\ButtonReply\Header;
use NotificationChannels\Whatsapp\Enums\ButtonType;
use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

class WhatsappButtonReplyMessage extends Message
{
    /**
     * @const int Maximum length for body message.
     */
    private const MAXIMUM_LENGTH = 1024;

    protected string $type = 'interactive';

    protected ?Header $header = null;

    protected ?string $body = null;

    protected ?string $footer = null;

    /** @var array Buttons Reply */
    protected array $buttons = [];

    /**
     * @throws CouldNotCreateMessage
     */
    public function __construct(string $body = '')
    {
        if ('' !== $body) {
            $this->body($body);
        }
    }

    /**
     * @return static
     * @throws CouldNotCreateMessage
     */
    public static function create(string $body = ''): self
    {
        return new static($body);
    }

    /**
     * Notification body.
     *
     * @return $this
     * @throws CouldNotCreateMessage
     */
    public function body(string $body): self
    {
        if (mb_strlen($body) > self::MAXIMUM_LENGTH) {
            throw CouldNotCreateMessage::textTooLong();
        }

        $this->body = $body;

        return $this;
    }

    /**
     * @return $this
     */
    public function header(Header $header = null): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return $this
     */
    public function footer(string $footer = ''): self
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Add up to 3 reply buttons.
     *
     * @return $this
     *
     * @throws CouldNotCreateMessage
     */
    public function buttons(array $buttons = []): self
    {
        if (count($buttons) > 3) {
            throw CouldNotCreateMessage::messageButtonsLimitExceeded();
        }

        $this->buttons = $buttons;

        return $this;
    }


    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Returns message payload for JSON conversion.
     */
    public function toArray(): array
    {
        $message = [];
        $message['messaging_product'] = $this->messagingProduct;
        $message['recipient_type'] = $this->recipientType;
        $message['to'] = $this->recipientId;
        $message['type'] = $this->type;
        $message['interactive']['type'] = 'button';
        $message['interactive']['header'] = $this->header;
        $message['interactive']['body']['text'] = $this->body;
        $message['interactive']['footer']['text'] = $this->footer;
        $message['interactive']['action']['buttons'] = $this->buttons;

        return $message;
    }
}
