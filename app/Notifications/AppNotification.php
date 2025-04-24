<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\User;
use Auth;
use Carbon\Carbon;

class AppNotification extends Notification
{
    use Queueable;

    private $sender_id;
    private $receiver_id;
    private $title;
    private $description;
    private $action;
    private $action_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($sender_id, $receiver_id, $title,$description,$action,$action_id)
    {
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->title = $title;
        $this->description = $description;
        $this->action = $action;
        $this->action_id = $action_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // return ['mail'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {

        return [

            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'title' => $this->title,
            'description' => $this->description,
            'action' => $this->action,
            'action_id' => $this->action_id,
            'notification_date' => Carbon::now()->format('Y-m-d'),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
