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
    public static function textTooLong($count = 4096, $field = 'Message'): self
    {
        return new static("{$field} text is too long, A {$count} character limited string should be provided.");
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

    /**
     * Thrown when number of buttons in message exceeds.
     *
     * @return static
     */
    public static function messageButtonsLimitExceeded(): self
    {
        return new static('You cannot attach more than one button of type "CTA URL" or more than three buttons of type "Interactive Reply"');
    }
}
