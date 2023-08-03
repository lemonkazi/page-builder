<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// From address is used for all emails send by the script
$config['email_from_address'] = "info@example.com";

// From name is used for all emails send by the script
$config['email_from_name'] = "Builder";

// Subject for the email send to user when payment received
$config['email_confirmation_subject'] = "Builder: Confirmation email!";

// Subject for the email send to user when admin create an user from admin panel with paid plan
$config['email_activation_subject'] = "Builder: Activation email!";

// Subject for the email send to user when admin create an user from admin panel with free plan
$config['email_login_subject'] = "Builder: Account created!";

// Subject for the email send to user when password forgot
$config['email_forgot_password_subject'] = "Builder: Forgot Password!";

// Subject for the email send to user when admin send reset password email
$config['email_reset_password_subject'] = "Builder: Reset Password!";

// SentAPI email from address is used for all emails send by the script
$config['sent_email_from_address'] = "info@example.com";

// SentAPI email from name is used for all emails send by the script
$config['sent_email_from_name'] = "Builder";

// SentAPI email subject for the email send to user by the script
$config['sent_email_default_subject'] = "Builder: Mail from your site!";

$config['email_sub_cancel_subject'] = "Builder: Profile Cancelled!";

$config['sub_cancel_failed_subject'] = "Builder: Profile Cancellation Failed!";

// CoreUpdate URI
$config['autoupdate_uri'] = 'https://update.bloxby.com/updates.json';


// License Server API URI
$config['license_api'] = 'https://license.bloxby.com/api/';

$config['license_uri'] = 'https://license.bloxby.com/api/verify_key/';

// Screenshot API Key
$config['screenshot_api_key'] = "2e94ee";
$config['screenshot_secret'] = "lksejhfefghug75765";

// Screenshot folder for site thumbs
$config['screenshot_sitethumbs_folder'] = "tmp/sitethumbs/";
// Screenshot folder for site thumbs
$config['screenshot_blockhumbs_folder'] = "tmp/blockthumbs/";

// Upload path for block thumbnails
$config['block_thumbnail_upload_config']['upload_path'] = "./images/uploads"; // Used to store the uploaded file
$config['block_thumbnail_upload_config']['allowed_types'] = 'gif|jpg|png';
$config['block_thumbnail_upload_config']['max_size'] = 5000;
$config['block_thumbnail_upload_config']['max_width'] = 2000;
$config['block_thumbnail_upload_config']['max_height'] = 1000;

// Upload path for component thumbnails
$config['component_thumbnail_upload_config']['upload_path'] = "./images/uploads"; // Used to store the uploaded file
$config['component_thumbnail_upload_config']['allowed_types'] = 'gif|jpg|png';
$config['component_thumbnail_upload_config']['max_size'] = 5000;
$config['component_thumbnail_upload_config']['max_width'] = 2000;
$config['component_thumbnail_upload_config']['max_height'] = 1000;

// Google fonts
$config['google_font_api'] = "https://fonts.googleapis.com/css?family=";

// Upload settings for the file browser
$config['browser_upload_config']['allowed_types'] = 'gif|jpg|png|css|js|html|txt|htm|xhtml';
$config['browser_upload_config']['max_size'] = 5000;
$config['browser_upload_config']['max_width'] = 20000;
$config['browser_upload_config']['max_height'] = 10000;

// Upload settings for logo uploads
$config['logo_upload_config']['upload_path'] = './images/uploads/';
$config['logo_upload_config']['allowed_types'] = 'gif|jpg|png';
$config['logo_upload_config']['max_size'] = 20000;
$config['logo_upload_config']['max_width'] = 2000;
$config['logo_upload_config']['max_height'] = 1000;

// Upload settings for favicon uploads
$config['favicon_upload_config']['upload_path'] = './images/uploads/favicons';
$config['favicon_upload_config']['allowed_types'] = 'gif|ico|png';
$config['favicon_upload_config']['max_size'] = 20000;
$config['favicon_upload_config']['max_width'] = 2000;
$config['favicon_upload_config']['max_height'] = 1000;

// Cloned blocks go in... (within the configured elements folder)
$config['cloned_folder'] = "clones";

// Cloudflare integration; specify these in config_custom.php
$config['cloudflare']['x_auth_email'] = '';
$config['cloudflare']['x_auth_key'] = '';

// Max number of days

$config['max_number_of_days'] = 10;

/** Max number of revision limit */
$config['max_number_or_revision'] = 3;

/** Unsplash API key */
$config['unsplash_access_key'] = 'f851c9c285c3bf9c1ed5a0e852b7550fdfe5af9a733c2e0c0d9a78d6ac069048';
$config['unsplash_secret_key'] = 'fab381459c661289cc462173f19366f510619b71f4dfdd8e6433dc4d4720b58d';

$config['enable_popups'] = true;

$config['popup_wrapping_html'] = '<div class="modal fade" tabindex="-1" role="dialog" %s><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-body">%s</div></div></div></div>';
