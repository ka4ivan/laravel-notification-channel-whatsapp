<?php

namespace NotificationChannels\Whatsapp\Components\ButtonCtaUrl;

class ButtonCtaUrlImageHeader extends Header
{
    protected string $link;

    public function __construct(string $link)
    {
        parent::__construct('image');

        if ('' !== $link) {
            $this->link($link);
        }
    }

    public static function create(string $link = ''): self
    {
        return new static($link);
    }

    public function link(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'image' => [
                'link' => $this->link,
            ],
        ];
    }
}
