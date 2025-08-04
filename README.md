# Whatsapp Notifications Channel for Laravel

[![License](https://img.shields.io/packagist/l/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-notification-channel-whatsapp)
[![Build Status](https://img.shields.io/github/stars/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://github.com/ka4ivan/laravel-notification-channel-whatsapp)
[![Latest Stable Version](https://img.shields.io/packagist/v/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-notification-channel-whatsapp)
[![Total Downloads](https://img.shields.io/packagist/dt/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-notification-channel-whatsapp)

This package makes it easy to send notifications using the [Whatsapp Messenger](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started) with Laravel.

## Contents

- [Installation](#installation)
    - [Setting up your WhatsApp Bot](#setting-up-your-whatsapp-bot)
    - [Set config](#set-config)
- [Usage](#usage)
    - [Types of messages](#types-of-messages)
      - [Text Message](#text-message)
      - [Reply Buttons Message](#reply-buttons-message)
      - [Call-To-Action (CTA URL) Message](#call-to-action-cta-url-message)
      - [Audio Message](#audio-message)
      - [Document Message](#document-message)
      - [Image Message](#image-message)
      - [Video Message](#video-message)
      - [Location Message](#location-message)
      - [Location Request Message](#location-request-message)
      - [Reaction](#reaction)
    - [Sending multiple messages](#sending-multiple-messages)
- [Contributing](#contributing)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require ka4ivan/laravel-notification-channel-whatsapp
```

### Setting up your Whatsapp Bot

This document describes the steps you must take to become a [Tech Provider Whatsapp](https://developers.facebook.com/docs/whatsapp/solution-providers/get-started-for-tech-providers)

### Set config
Next we need to add tokens to our Laravel configurations. Create a new Whatsapp section inside `config/services.php` and place the page token there:

```php
// config/services.php
'whatsapp' => [
    'access_token' => env('WHATSAPP_ACCESS_TOKEN', ''),
    'number_id' => env('WHATSAPP_NUMBER_ID', ''),
    'api_version' => env('WHATSAPP_API_VERSION', '22.0'),
],
```

## Usage

You can now use the Whatsapp channel in your `via()` method, inside the InvoicePaid class. The `to($recipientId)` Whatsapp ID (Phone Number) method defines the Whatsapp user, you want to send the notification to.


```php
use NotificationChannels\Whatsapp\WhatsappChannel;
use NotificationChannels\Whatsapp\WhatsappMessage;

use Illuminate\Notifications\Notification;

class ChannelConnected extends Notification
{
    public function via($notifiable)
    {
        return [WhatsappChannel::class];
    }

    public function toWhatsapp($notifiable)
    {

        return WhatsappMessage::create()
            ->to($notifiable->whatsapp_id) // Optional
            ->previewUrl(false) // Optional
            ->text('Congratulations, the communication channel is connected');
    }
}
```

The notification will be sent from your Whatsapp page, whose page token you have configured earlier. Here's a screenshot preview of the notification inside the chat window.

![image](https://github.com/user-attachments/assets/62cebb84-2d8e-47fe-ad3c-5a49be91c151)

#### Message Examples

##### Basic Text Message

```php
return WhatsappMessage::create('You have just paid your monthly fee! Thanks');
```

### Routing a message

You can either send the notification by providing with the page-scoped user id of the recipient to the `to($recipientId)` Whatsapp ID (Phone Number) method like shown in the above example or add a `routeNotificationForWhatsapp()` method in your notifiable model:

```php
/**
 * Route notifications for the Whatsapp channel.
 *
 * @return int
 */
public function routeNotificationForWhatsapp()
{
    return $this->whatsapp_id;
}
```

### Types of messages

#### Audio Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `mediaId('')`: (string) Whatsapp media ID (Only if using uploaded media)
- `link('')`: (string) Media link (Only if using hosted media (not recommended))

```php
WhatsappAudioMessage::create()
    ->link('audio url');
```

#### Call-To-Action (CTA URL) Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `body('')`: (string) Message body text.
- `header(null)`: (Header) Message header object. (ButtonCtaUrlImageHeader/ButtonCtaUrlTitleHeader)
- `footer('')`: (string) Message footer text.
- `button()`: (ButtonCtaUrl) Message button object.

```php
WhatsappCtaUrlMessage::create()
    ->header(ButtonCtaUrlTitleHeader::create()->title('header text'))
    ->body('body text')
    ->footer('footer text')
    ->button(ButtonCtaUrl::create()->displayText('button text')->url('button url'));
```

#### Document Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `caption('')`: (string) Notification caption.
- `filename('')`: (string) Document filename, with extension. The WhatsApp client will use an appropriate file type icon based on the extension.
- `mediaId('')`: (string) Whatsapp media ID (Only if using uploaded media)
- `link('')`: (string) Whatsapp media link (Only if using hosted media (not recommended))

```php
WhatsappDocumentMessage::create()
    ->caption('file caption')
    ->filename('file name')
    ->link('file url');
```

#### Image Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `caption('')`: (string) Notification caption.
- `mediaId('')`: (string) Whatsapp media ID (Only if using uploaded media)
- `link('')`: (string) Whatsapp media link (Only if using hosted media (not recommended))

```php
WhatsappImageMessage::create()
    ->caption('image caption')
    ->link('image url');
```

#### Location Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `latitude('')`: (string) The geographic latitude of the location (e.g., '50.4501').
- `longitude('')`: (string) The geographic longitude of the location (e.g., '30.5234').
- `name('')`: (string) The name or label of the location (e.g., 'Independence Square').
- `address('')`: (string) The full address (optional), displayed under the name (e.g., 'Khreshchatyk St, Kyiv, Ukraine').

```php
WhatsappLocationMessage::create('latitude', 'longitude')
    ->address('address text')
    ->name('name text');
```

#### Location Request Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `body('')`: (string) Message body text.

```php
WhatsappLocationRequestMessage::create('Location, pls');
```

#### Text Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `text('')`: (string) Notification message.
- `previewUrl(true)`: (boolean) [Link Preview](https://developers.facebook.com/docs/whatsapp/cloud-api/messages/text-messages).
- `setApiVersion($apiVersion)`: (string) Set Default Graph API Version.
- `setAccessToken($accessToken)`: (string) Set the access token used for authenticating API requests.
- `setNumberId($numberId)`: (string) Set the Whatsapp number ID for API requests.

```php
WhatsappMessage::create('Your order has been confirmed!');
```

#### Reaction
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `emoji('')`: (string) Emoji reaction. Unicode escape sequence example: \uD83D\uDE00. Emoji example: 😀
- `messageId('')`: (string) Whatsapp Message ID

```php
WhatsappReaction::create()
    ->emoji('😊')
    ->messageId('Whatsapp message ID');
```

#### Reply Buttons Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `body('')`: (string) Message body text.
- `header(null)`: (Header) Message header object. (ButtonCtaUrlImageHeader/ButtonCtaUrlTitleHeader)
- `footer('')`: (string) Message footer text.
- `buttons([])`: (array) Message buttons array [ButtonReply, ButtonReply, ButtonReply].

```php
WhatsappButtonReplyMessage::create()
    ->header(ButtonReplyTitleHeader::create()->title('header text'))
    ->body('body text')
    ->footer('footer text')
    ->buttons([
        ButtonReply::create()->id('button id 1')->title('button title 1'),
        ButtonReply::create()->id('button id 2')->title('button title 2'),
        ButtonReply::create()->id('button id 3')->title('button title 3'),
    ]);
```

#### Video Message
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `caption('')`: (string) Notification caption.
- `mediaId('')`: (string) Whatsapp media ID (Only if using uploaded media)
- `link('')`: (string) Whatsapp media link (Only if using hosted media (not recommended))

```php
WhatsappVideoMessage::create()
    ->caption('video caption')
    ->link('video url');
```

### Sending multiple messages
If you need to send multiple files (regardless of the message type)
```php
/**
 * @param $notifiable
 * @return \NotificationChannels\Whatsapp\Message|array
 * @throws \NotificationChannels\Whatsapp\Exceptions\CouldNotCreateMessage
 */
public function toWhatsApp($notifiable)
{
    $text = 'text';

    $files = $this->getFiles();

    if (!empty($files)) {
        $messages = [];

        $first = true;
        foreach ($files as $url => $name) {
            $message = WhatsappDocumentMessage::create()
                ->link($url)
                ->filename(Str::substr($name, -32));

            if ($first) {
                $message->caption($text);
                $first = false;
            }

            $messages[] = $message;
        }

        return $messages;
    }

    return WhatsappMessage::create()->text($text);
}
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
