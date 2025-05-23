<?php

namespace NotificationChannels\Whatsapp;

use Illuminate\Support\Arr;
use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

abstract class Message implements \JsonSerializable
{
    /**
     * @var string Type of message object.
     */
    protected string $type;

    /** @var string Recipient's ID (Phone Number). */
    public $recipientId;

    /**
     * @var string Currently only "individual" value is supported.
     */
    protected string $recipientType = 'individual';

    /**
     * @var string Currently only "whatsapp" value is supported.
     */
    protected string $messagingProduct = 'whatsapp';

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
     * Return the type of message object.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Determine if user id is not given.
     */
    public function toNotGiven(): bool
    {
        return !isset($this->recipientId);
    }

    /**
     * Return the messaging product.
     *
     * @return string
     */
    public function messagingProduct(): string
    {
        return $this->messagingProduct;
    }

    /**
     * Return the recipient type.
     *
     * @return string
     */
    public function recipientType(): string
    {
        return $this->recipientType;
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
    abstract public function toArray(): array;
}
