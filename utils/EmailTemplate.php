<?php

namespace App\utils;

class EmailTemplate
{

    public static function welcome($recipient, $confirmationlink)
    {
        return '<p>Dear ' . $recipient . ',</p>
          <div>Thank you for registering with us. To complete your registration,<br/><br/>
              <a style="padding: 10px; border:none;background-color:#4caf50;color:white;margin: 10px auto;" href="' . $confirmationlink . '">Click here</a>
              </div>
              <br/>
              <p>to confirm your account and complete your profile for approval and verification</p>
              <p>If the above doesn\'t work, use the link below <br/><br/>
                ' . $confirmationlink . '
              </p><br/>
              <p>This Link will expire in 24 hours</p>
    
          <p>Thanks,<br/>SkipChores Team</p>';
    }
}
