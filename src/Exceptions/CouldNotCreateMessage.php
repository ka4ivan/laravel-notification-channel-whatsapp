<?php

namespace NotificationChannels\Whatsapp\Exceptions;

use GuzzleHttp\Exception\ClientException;

class CouldNotCreateMessage extends \Exception
{
    /**
     * Thrown when there is no user id or phone number provided.
     *
     * @return static
     */
    public static function recipientNotProvided(): self
    {
        return new static('Instagram notification recipient ID or Phone Number was not provided. Please refer usage docs.');
    }
}
