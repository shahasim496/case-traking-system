<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;


    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Account Details Have Been Updated')
                    ->view('emails.user_updated')
                    ->with([
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                        'phone' => $this->user->phone,
                        'cnic' => $this->user->cnic,
                        'password' => $this->password,
                        
                        
                    ]);
    }
}
