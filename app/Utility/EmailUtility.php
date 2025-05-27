<?php

namespace App\Utility;

use Illuminate\Support\Facades\Mail;

class EmailUtility
{
    public static function refundEmail($emailIdentifiers, $refund)
    {
        foreach ($emailIdentifiers as $identifier) {
            switch ($identifier) {
                case 'refund_request_email_to_admin':
                    $to = get_admin()->email;
                    break;
                case 'refund_request_email_to_customer':
                    $to = $refund->order->user->email;
                    break;
                case 'refund_request_email_to_seller':
                    $to = $refund->order->seller->email;
                    break;
                default:
                    $to = null;
                    break;
            }

            if ($to) {
                Mail::send('emails.refund_request', ['refund' => $refund], function ($message) use ($to, $identifier) {
                    $message->to($to);
                    $message->subject(translate($identifier));
                });
            }
        }
    }
}
