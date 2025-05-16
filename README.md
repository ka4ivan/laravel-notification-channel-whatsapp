# Whatsapp Notifications Channel for Laravel

[![License](https://img.shields.io/packagist/l/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-notification-channel-whatsapp)
[![Build Status](https://img.shields.io/github/stars/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://github.com/ka4ivan/laravel-notification-channel-whatsapp)
[![Latest Stable Version](https://img.shields.io/packagist/v/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-notification-channel-whatsapp)
[![Total Downloads](https://img.shields.io/packagist/dt/ka4ivan/laravel-notification-channel-whatsapp.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-notification-channel-whatsapp)

This package makes it easy to send notifications using the [Whatsapp Messenger](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started) with Laravel.

## Contents

- [Installation](#installation)
    - [Setting up your Whatsapp Bot](#setting-up-your-whatsapp-bot)
    - [Set config](#set-config)
- [Usage](#usage)
    - [Available Message methods](#available-message-methods)
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

### Available Message methods
- `to($recipientId)`: (string) User (recipient) Whatsapp ID (Phone Number).
- `text('')`: (string) Notification message.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
