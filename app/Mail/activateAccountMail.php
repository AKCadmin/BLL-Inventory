<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivateAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @return void
     */
    public function __construct($data)
    {
        
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $htmlContent = "
            <h1>{$this->data['title']}</h1>
            <p>{$this->data['body']}</p>
            <p><a href='{$this->data['url']}'>Activate your account</a></p>
        ";

        return $this->subject($this->data['title'])
                    ->html($htmlContent);
    }
}
