<?php

/*
|--------------------------------------------------------------------------
| CARCONNECT GMAIL OTP CONFIGURATION TEMPLATE
|--------------------------------------------------------------------------
| Replace with your actual credentials for local / production use.
*/

define('CARCONNECT_MAIL_USERNAME', 'your_email@gmail.com');
define('CARCONNECT_MAIL_APP_PASSWORD', 'your_gmail_app_password');
define('CARCONNECT_MAIL_FROM_NAME', 'CarConnect');
define('CARCONNECT_MAIL_HOST', 'smtp.gmail.com');
define('CARCONNECT_MAIL_PORT', 587);
define('CARCONNECT_OTP_EXPIRY_SECONDS', 300);
define('CARCONNECT_OTP_RESEND_SECONDS', 60);
define('CARCONNECT_OTP_MAX_ATTEMPTS', 5);

?>
