<?php

namespace NotificationChannels\Whatsapp\Components\ButtonCtaUrl;

use NotificationChannels\Whatsapp\Enums\ButtonType;

/**
 * Class Button.
 */
class ButtonCtaUrl implements \JsonSerializable
{
    /** @var string Button Type */
    protected $type = ButtonType::CTA_URL;
    protected $displayText = '';
    protected $url = '';

    /**
     * Button Constructor.
     *
     * @param string $displayText
     * @param string $url
     */
    public function __construct(
        string $displayText,
        string $url,
    )
    {
        $this->displayText = $displayText;
        $this->url = $url;
    }

    /**
     * Create a button.
     *
     * @param string|null $displayText
     * @param string|null $url
     * @return static
     */
    public static function create(
        string $displayText = null,
        string $url = null,
    ): self
    {
        return new static(
            $displayText,
            $url,
        );
    }

    public function displayText(string $displayText): self
    {
        $this->displayText = $displayText;

        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;

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
        $button['name'] = $this->type;
        $button['parameters']['display_text'] = $this->displayText;
        $button['parameters']['url'] = $this->url;

        return $button;
    }
}
