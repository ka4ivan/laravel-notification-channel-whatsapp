<?php

namespace NotificationChannels\Whatsapp;

use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

class WhatsappLocationRequestMessage extends Message
{
    const MAXIMUM_LENGTH = 1024;

    protected string $type = 'interactive';

    /** @var string */
    public string $body = '';

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
    public static function create($body): self
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
            throw CouldNotCreateMessage::textTooLong(self::MAXIMUM_LENGTH);
        }

        $this->body = $body;

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
        $message['interactive']['type'] = 'location_request_message';
        $message['interactive']['body']['text'] = $this->body;
        $message['interactive']['action']['name'] = 'send_location';

        return $message;
    }
}
