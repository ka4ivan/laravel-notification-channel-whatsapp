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
        return new static('Whatsapp notification recipient ID or Phone Number was not provided. Please refer usage docs.');
    }

    /**
     * Thrown when the message text is not provided.
     *
     * @return static
     */
    public static function textTooLong(): self
    {
        return new static('Message text is too long, A 4096 character limited string should be provided.');
    }

    /**
     * Thrown when enough data is not provided.
     *
     * @return static
     */
    public static function dataNotProvided(): self
    {
        return new static('Your message was missing critical information');
    }
}
