<?php

namespace NotificationChannels\Whatsapp;

use Illuminate\Support\Arr;
use NotificationChannels\Whatsapp\Components\ButtonCtaUrl\ButtonCtaUrl;
use NotificationChannels\Whatsapp\Components\ButtonCtaUrl\Header;
use NotificationChannels\Whatsapp\Enums\ButtonType;
use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

class WhatsappCtaUrlMessage extends Message
{
    /**
     * @const int Maximum length for body message.
     */
    private const MAXIMUM_LENGTH = 4096;

    protected string $type = 'interactive';

    protected ?Header $header = null;

    protected ?string $body = null;

    protected ?string $footer = null;

    /** @var ButtonCtaUrl CTA Button Button */
    protected ButtonCtaUrl $button;

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
     * Set button.
     *
     * @return $this
     */
    public function button(ButtonCtaUrl $button): self
    {
        $this->button = $button;

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
        $message['interactive']['type'] = ButtonType::CTA_URL;
        $message['interactive']['header'] = $this->header;
        $message['interactive']['body']['text'] = $this->body;
        $message['interactive']['footer']['text'] = $this->footer;
        $message['interactive']['action'] = $this->button;

        return $message;
    }
}
