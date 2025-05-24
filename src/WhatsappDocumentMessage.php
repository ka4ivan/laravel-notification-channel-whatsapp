<?php

namespace NotificationChannels\Whatsapp;

use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

class WhatsappDocumentMessage extends Message
{
    /**
     * @const int Maximum length for body message.
     */
    private const MAXIMUM_LENGTH = 1024;

    protected string $type = 'document';

    /** @var string Notification Caption. */
    public string $caption;

    /** @var string Whatsapp media ID */
    public string $mediaId;

    /** @var string Document URL */
    public string $link;

    /** @var string Document file name */
    public string $filename;

    /**
     * @throws CouldNotCreateMessage
     */
    public function __construct(string $caption = '')
    {
        if ('' !== $caption) {
            $this->caption($caption);
        }
    }

    /**
     * @return static
     * @throws CouldNotCreateMessage
     */
    public static function create(string $caption = ''): self
    {
        return new static($caption);
    }

    /**
     * Notification caption.
     *
     * @return $this
     * @throws CouldNotCreateMessage
     */
    public function caption(string $caption): self
    {
        if (mb_strlen($caption) > self::MAXIMUM_LENGTH) {
            throw CouldNotCreateMessage::textTooLong(self::MAXIMUM_LENGTH);
        }

        $this->caption = $caption;

        return $this;
    }

    /**
     * Document filename, with extension. The WhatsApp client will use an appropriate file type icon based on the extension.
     *
     * @return $this
     */
    public function filename(string $filename = ''): self
    {
        $this->filename = $filename;

        return $this;
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
     * Whatsapp media link (Only if using hosted media (not recommended))
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

        $message['document']['caption'] = $this->caption;
        $message['document']['filename'] = $this->filename;

        return $message;
    }
}
