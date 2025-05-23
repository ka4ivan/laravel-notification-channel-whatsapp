<?php

namespace NotificationChannels\Whatsapp\Components\ButtonCtaUrl;

abstract class Header implements \JsonSerializable
{
    protected string $type;

    protected function __construct(string $type)
    {
        $this->type = $type;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    abstract public function toArray(): array;
}
