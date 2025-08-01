<?php

namespace NotificationChannels\Whatsapp;

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
     * Access token for authenticating with the Whatsapp API.
     *
     * @var string|null
     */
    protected ?string $accessToken = null;

    /**
     * Sender's Number ID
     *
     * @var string|null
     */
    protected ?string $numberId = null;

    /**
     * Whatsapp API version (e.g., '22.0')
     *
     * @var string|null
     */
    protected ?string $apiVersion = null;

    /**
     * Set the access token used for authenticating API requests.
     *
     * @param string|null $accessToken Whatsapp access token.
     * @return $this
     */
    public function setAccessToken(string $accessToken = null): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Set the sender`s number ID.
     *
     * @param string|null $numberId
     * @return $this
     */
    public function setNumberId(string $numberId = null): self
    {
        $this->numberId = $numberId;

        return $this;
    }

    /**
     * Set Default Graph API Version.
     *
     * @param string|null $apiVersion
     * @return $this
     */
    public function setApiVersion(string $apiVersion = null): self
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @return string|null
     */
    public function getNumberId(): ?string
    {
        return $this->numberId;
    }

    /**
     * @return string|null
     */
    public function getApiVersion(): ?string
    {
        return $this->apiVersion;
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
