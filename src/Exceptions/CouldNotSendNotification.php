<?php

namespace NotificationChannels\Whatsapp\Exceptions;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;

class CouldNotSendNotification extends \Exception
{
    public static function whatsappRespondedWithAnExceptionError($response)
    {
        $error = Arr::get($response, 'error');

        return new static("The communication with endpoint failed. Reason: {$error}");
    }

    public static function serviceRespondedWithAnError($response)
    {
        return new static("Descriptive error message.");
    }

    /**
     * Thrown when there's no page token provided.
     *
     * @return static
     */
    public static function whatsappTokenNotProvided(string $message): self
    {
        return new static($message);
    }

    public static function whatsappRespondedWithAnError(ClientException $exception): self
    {
        if ($exception->hasResponse()) {
            $result = json_decode($exception->getResponse()->getBody(), false);

            return new static("Whatsapp responded with an error `{$result->error->code} - {$result->error->type} {$result->error->message}`");
        }

        return new static('Whatsapp responded with an error');
    }

    /**
     * Thrown when we're unable to communicate with Whatsapp.
     *
     * @return static
     */
    public static function couldNotCommunicateWithWhatsapp(\Exception $exception): self
    {
        return new static('The communication with Whatsapp failed. Reason: '.$exception->getMessage());
    }
}
