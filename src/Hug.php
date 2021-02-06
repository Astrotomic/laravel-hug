<?php

namespace Astrotomic\Hug\Laravel;

use Astrotomic\Hug\Huggable as HuggableContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Hug extends Notification implements ShouldQueue
{
    use Queueable;

    /** @var \Astrotomic\Hug\Huggable|\Illuminate\Database\Eloquent\Model */
    public HuggableContract $hugger;

    /** @var \Astrotomic\Hug\Huggable|\Illuminate\Database\Eloquent\Model */
    public HuggableContract $huggable;

    public function __construct(HuggableContract $hugger, HuggableContract $huggable)
    {
        $this->hugger = $hugger;
        $this->huggable = $huggable;

        $this->connection = config('hug.connection');
        $this->queue = config('hug.queue');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param \Astrotomic\Hug\Huggable|mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return config('hug.via');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param \Astrotomic\Hug\Huggable|mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->greeting($this->translate('notification.mail.greeting'))
            ->subject($this->translate('notification.mail.subject'))
            ->line($this->translate('notification.mail.content'));
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     *
     * @param \Astrotomic\Hug\Huggable|mixed $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable): NexmoMessage
    {
        return (new NexmoMessage)
            ->content($this->translate('notification.nexmo.content'))
            ->unicode();
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param \Astrotomic\Hug\Huggable|mixed $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->content($this->translate('notification.slack.content'));
    }

    protected function translate(string $key, array $data = []): string
    {
        return trans(
            Str::start($key, 'hug::'),
            Arr::dot(array_merge([
                'hugger' => $this->hugger->getAttributes(),
                'huggable' => $this->huggable->getAttributes(),
            ], $data))
        );
    }
}