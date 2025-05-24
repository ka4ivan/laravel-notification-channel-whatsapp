<?php

namespace NotificationChannels\Whatsapp;

use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

class WhatsappMessage extends Message
{
    /**
     * @const int Maximum length for body message.
     */
    private const MAXIMUM_LENGTH = 4096;

    protected string $type = 'text';

    /** @var string Notification Text. */
    public string $text;

    /** @var bool */
    public bool $previewUrl = true;

    /**
     * @throws CouldNotCreateMessage
     */
    public function __construct(string $text = '')
    {
        if ('' !== $text) {
            $this->text($text);
        }
    }

    /**
     * @return static
     * @throws CouldNotCreateMessage
     */
    public static function create(string $text = ''): self
    {
        return new static($text);
    }

    /**
     * Notification text.
     *
     * @return $this
     * @throws CouldNotCreateMessage
     */
    public function text(string $text): self
    {
        if (mb_strlen($text) > self::MAXIMUM_LENGTH) {
            throw CouldNotCreateMessage::textTooLong();
        }

        $this->text = $text;

        return $this;
    }

    /**
     * Link Preview https://developers.facebook.com/docs/whatsapp/cloud-api/messages/text-messages
     *
     * @return $this
     */
    public function previewUrl(bool $active = true): self
    {
        $this->previewUrl = $active;

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
        $message['text']['preview_url'] = $this->previewUrl;
        $message['text']['body'] = $this->text;

        return $message;
    }
}
