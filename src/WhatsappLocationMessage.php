<?php

namespace NotificationChannels\Whatsapp;

use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

class WhatsappLocationMessage extends Message
{
    protected string $type = 'location';

    /** @var string */
    public string $latitude = '';

    /** @var string */
    public string $longitude = '';

    /** @var string */
    public string $name = '';

    /** @var string */
    public string $address = '';

    /**
     * @throws CouldNotCreateMessage
     */
    public function __construct(string $latitude = '', string $longitude = '')
    {
        if ('' !== $latitude) {
            $this->latitude($latitude);
        }
        if ('' !== $longitude) {
            $this->longitude($longitude);
        }
    }

    /**
     * @return static
     * @throws CouldNotCreateMessage
     */
    public static function create($latitude, $longitude): self
    {
        return new static($latitude, $longitude);
    }

    /**
     * @return $this
     */
    public function latitude(string $latitude = ''): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return $this
     */
    public function longitude(string $longitude = ''): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return $this
     */
    public function name(string $name = ''): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return $this
     */
    public function address(string $address = ''): self
    {
        $this->address = $address;

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
        $message['location']['latitude'] = $this->latitude;
        $message['location']['longitude'] = $this->longitude;
        $message['location']['name'] = $this->name;
        $message['location']['address'] = $this->address;

        return $message;
    }
}
