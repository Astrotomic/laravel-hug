<?php

namespace Astrotomic\Hug\Laravel\Concerns;

use Astrotomic\Hug\Huggable as HuggableContract;
use Astrotomic\Hug\Laravel\Exceptions\SelfHugException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;

/**
 * @mixin \Astrotomic\Hug\Huggable
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Notifications\Notifiable
 */
trait Huggable
{
    public function hug(HuggableContract $huggable): void
    {
        if (! $huggable instanceof Model) {
            throw new InvalidArgumentException();
        }

        if (! array_key_exists(Notifiable::class, class_uses_recursive($huggable))) {
            throw new InvalidArgumentException();
        }

        if ($this->is($huggable)) {
            throw new SelfHugException();
        }

        $huggable->notify(
            $this->makeHugNotification($huggable)
        );
    }

    protected function makeHugNotification(HuggableContract $huggable): Notification
    {
        return app(
            config('hug.notification'),
            [
                'hugger' => $this,
                'huggable' => $huggable,
            ]
        );
    }
}
