<?php

namespace NotificationChannels\Whatsapp;

use NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage;
use NotificationChannels\Whatsapp\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class WhatsappChannel
{

    /** @var Whatsapp */
    private $whatsapp;

    /**
     * FacebookChannel constructor.
     */
    public function __construct(Whatsapp $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return array
     * @throws CouldNotCreateMessage
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification): array
    {
        $message = $notification->toWhatsapp($notifiable);

        if (is_string($message)) {
            $message = WhatsappMessage::create($message);
        }

        if ($message->toNotGiven()) {
            if (!$to = $notifiable->routeNotificationFor('whatsapp')) {
                throw CouldNotCreateMessage::recipientNotProvided();
            }

            $message->to($to);
        }

        $response = $this->whatsapp->send($message->toArray());

        if (Arr::get($response, 'error')) {
            throw CouldNotSendNotification::whatsappRespondedWithAnExceptionError($response);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
