<?php

namespace NotificationChannels\Whatsapp;

use Illuminate\Support\Arr;
use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

class WhatsappMessage implements \JsonSerializable
{
    /** @var string Recipient's ID (Phone Number). */
    public $recipientId;

    /** @var string Notification Text. */
    public $text;

    /** @var bool */
    protected $hasText = false;

    /** @var bool */
    public $previewUrl = true;

    /**
     * @var string Currently only "individual" value is supported.
     */
    protected string $recipientType = 'individual';

    /**
     * @var string Currently only "whatsapp" value is supported.
     */
    protected string $messagingProduct = 'whatsapp';

    /**
     * @var string Type of message object.
     */
    protected $type;

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
        if (mb_strlen($text) > 4096) {
            throw CouldNotCreateMessage::textTooLong();
        }

        $this->text = $text;
        $this->hasText = true;

        return $this;
    }

    /**
     * Recipient's Whatsapp ID (Phone Number).
     *
     * @return $this
     */
    public function to($recipientId): self
    {
        $this->recipientId = $recipientId;

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
     * Determine if user id is not given.
     */
    public function toNotGiven(): bool
    {
        return !isset($this->recipientId);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     *
     * @throws CouldNotCreateMessage
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Returns message payload for JSON conversion.
     *
     * @throws CouldNotCreateMessage
     */
    public function toArray(): array
    {
        if ($this->hasText) {
            $this->type = 'text';

            return $this->textMessageToArray();
        }

        throw CouldNotCreateMessage::dataNotProvided();
    }

    /**
     * Returns message for simple text message.
     */
    protected function textMessageToArray(): array
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
