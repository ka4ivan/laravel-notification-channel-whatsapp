<?php

namespace NotificationChannels\Whatsapp\Components\ButtonReply;

class ButtonReplyTitleHeader extends Header
{
    protected string $title = '';

    public function __construct(string $title)
    {
        parent::__construct('text');

        if ('' !== $title) {
            $this->title($title);
        }
    }

    public static function create(string $title = ''): self
    {
        return new static($title);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'text' => $this->title,
        ];
    }
}
