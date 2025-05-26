<?php

namespace NotificationChannels\Whatsapp\Components\ButtonReply;

class ButtonReplyImageHeader extends Header
{
    protected string $link;
    protected string $id;

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

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function toArray(): array
    {
        $button = [];
        $button['type'] = $this->type;

        if ($this->link) {
            $button['image']['link'] = $this->link;
        } elseif ($this->id) {
            $button['image']['id'] = $this->id;
        }

        return $button;
    }
}
