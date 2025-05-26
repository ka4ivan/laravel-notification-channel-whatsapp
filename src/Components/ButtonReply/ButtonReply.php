<?php

namespace NotificationChannels\Whatsapp\Components\ButtonReply;

use NotificationChannels\Whatsapp\Enums\ButtonType;
use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;

/**
 * Class Button.
 */
class ButtonReply implements \JsonSerializable
{
    /**
     * @const int Maximum length for body message.
     */
    private const MAXIMUM_LENGTH_ID = 256;
    private const MAXIMUM_LENGTH_TITLE = 20;

    /** @var string Button Type */
    protected $type = ButtonType::REPLY;
    protected $id = '';
    protected $title = '';

    /**
     * Button Constructor.
     *
     * @param string $id
     * @param string $title
     */
    public function __construct(
        string $id,
        string $title,
    )
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * Create a button.
     *
     * @param string|null $id
     * @param string|null $title
     * @return static
     */
    public static function create(
        string $id = null,
        string $title = null,
    ): self
    {
        return new static(
            $id,
            $title,
        );
    }

    /**
     * @throws CouldNotCreateMessage
     */
    public function id(string $id): self
    {
        if (mb_strlen($id) > self::MAXIMUM_LENGTH_ID) {
            throw CouldNotCreateMessage::textTooLong(self::MAXIMUM_LENGTH_ID, 'Button ID');
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @throws CouldNotCreateMessage
     */
    public function title(string $title): self
    {
        if (mb_strlen($title) > self::MAXIMUM_LENGTH_TITLE) {
            throw CouldNotCreateMessage::textTooLong(self::MAXIMUM_LENGTH_TITLE, 'Button title');
        }

        $this->title = $title;

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
     * Builds payload and returns an array.
     */
    public function toArray(): array
    {
        $button = [];
        $button['type'] = $this->type;
        $button['reply']['id'] = $this->id;
        $button['reply']['title'] = $this->title;

        return $button;
    }
}
