<?php

namespace NotificationChannels\Whatsapp;

class WhatsappReaction extends Message
{
    protected string $type = 'reaction';

    /** @var string Emoji reaction. Unicode escape sequence example: \uD83D\uDE00. Emoji example: 😀 */
    public string $emoji = '';

    /** @var string Whatsapp Message ID */
    public string $messageId = '';

    public function __construct(string $emoji = '', string $messageId = '')
    {
        if ('' !== $emoji) {
            $this->emoji($emoji);
        }
        if ('' !== $messageId) {
            $this->messageId($messageId);
        }
    }

    /**
     * @return static
     */
    public static function create(string $emoji = '', string $messageId = ''): self
    {
        return new static($emoji, $messageId);
    }

    /**
     * Emoji reaction. Unicode escape sequence example: \uD83D\uDE00. Emoji example: 😀
     *
     * @return $this
     */
    public function emoji(string $emoji): self
    {
        $this->emoji = $emoji;

        return $this;
    }

    /**
     * Whatsapp Message ID
     *
     * @return $this
     */
    public function messageId(string $messageId): self
    {
        $this->messageId = $messageId;

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
        $message['reaction']['message_id'] = $this->messageId;
        $message['reaction']['emoji'] = $this->emoji;

        return $message;
    }
}
