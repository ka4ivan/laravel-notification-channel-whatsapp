<?php

namespace NotificationChannels\Whatsapp;

class WhatsappAudioMessage extends Message
{
    protected string $type = 'audio';

    /** @var string Whatsapp media ID */
    public string $mediaId;

    /** @var string Document URL */
    public string $link;

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Whatsapp media ID (Only if using uploaded media)
     *
     * @return $this
     */
    public function mediaId(string $mediaId = ''): self
    {
        $this->mediaId = $mediaId;

        return $this;
    }

    /**
     * Media link (Only if using hosted media (not recommended))
     *
     * @return $this
     */
    public function link(string $link = ''): self
    {
        $this->link = $link;

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

        if ($this->mediaId) {
            $message['document']['id'] = $this->mediaId;
        } elseif ($this->link) {
            $message['document']['link'] = $this->link;
        }

        return $message;
    }
}
