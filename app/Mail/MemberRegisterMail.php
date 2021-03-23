<?php

namespace App\Mail;

use App\Models\Front\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MemberRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;

    public function __construct($member)
    {
        $this->member = $member;
    }

    public function build()
    {
        return $this->subject('Your Verified Email Registration')
                    ->view('ecommerce.email.registration')
                    ->with([
                        'member' => $this->member
                    ]);
    }
}
