<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Builder Language file
 */

/**** Common text used in all modules Start ****/
$lang['application_name'] = "<b>Bu</b>ilder";
$lang['application_name_text'] = "Builder";
$lang['flashdata_success'] = 'Success!';
$lang['flashdata_error'] = 'Error!';
$lang['OR'] = "OR";
$lang['loading'] = "Loading...";
$lang['newsite_default_title'] = "My New Site";
$lang['modal_close'] = "Close";
$lang['modal_areyousure'] = "Are you sure?";
$lang['modal_cancelclose'] = "Cancel & Close";
$lang['modal_delete'] = "Delete";
$lang['back'] = "back";
$lang['cancel'] = "Cancel";
$lang['imsure'] = "Yes, I'm sure";
$lang['loading_site_data'] = "Loading site data...";
$lang['loading_saving_data'] = "Saving data...";
$lang['admin_permission_error'] = "You need to be admin to do this.";

/**** Common text used in all modals End ****/



/**** Asset Module Start ****/

// asset/controllers/Asset.php
$lang['asset_imageUploadAjax_error1_heading'] = "Ouch! Something went wrong:";
$lang['asset_imageUploadAjax_error1_message'] = "Something went wrong when trying to upload your image, please see the details below:<br>";

$lang['asset_imageUploadAjax_success1_heading'] = "All set!";
$lang['asset_imageUploadAjax_success1_message'] = "Your image was uploaded successfully and can now be found under the 'My Images' tab.";

$lang['asset_slim_filesize_error'] = "The file you are trying to upload is too large, the maximum file size is $0 MB";
$lang['asset_slim_filetype_error'] = "Invalid file type, expects: $0";
$lang['asset_slim_toosmall_error'] = "Image is too small, minimum size is: $0 pixels";
$lang['asset_slim_no_data_error'] = 'No data posted';
$lang['asset_slim_no_images_error'] = 'No images found';
$lang['asset_slim_no_imagedata_error'] = "No image data";
$lang['asset_slim_disk_space_error'] = "You have reached the limit of images you're allowed to upload. Please consider upgrading to different plan.";
$lang['asset_slim_unkown_error'] = "An unknown error occurred";
$lang['asset_slim_upload_success'] = "Your image was uploaded";
$lang['asset_slim_button_cancel'] = "Cancel";
$lang['asset_slim_button_confirm'] = "Confirm";
$lang['asset_slim_button_remove_title'] = "Remove";
$lang['asset_slim_button_edit_title'] = "Edit";
$lang['asset_slim_button_upload_title'] = "Upload";

$lang['asset_open_image'] = "Click to open image";

$lang['asset_dimensions_label'] = "Image Dimensions:";
$lang['asset_dimensions_by'] = "By";
$lang['asset_dimensions_aspect_ratio'] = "Fix aspect ratio";
$lang['asset_dimensions_button'] = "Update dimensions";
$lang['asset_dimensions_button_loading'] = "Updating image...";
$lang['asset_dimensions_button_confirm'] = "Image resized!";

$lang['asset_imagedelete_button'] = "Delete image";
$lang['asset_imagedelete_button_loading'] = "Working...";
$lang['asset_imagedelete_areyousure'] = "Are you sure?";
$lang['asset_imagedelete_areyousure_yes'] = "Yes";
$lang['asset_imagedelete_areyousure_no'] = "No";

$lang['asset_resize_error'] = "We could not resize this image.";
$lang['asset_resize_success'] = "Your image has been resized.";

// asset/views/images.php
$lang['images_heading'] = "My Image Library";
$lang['images_uploadimages'] = "Upload Image(s)";
$lang['images_button_selectimage'] = "Select Image";
$lang['images_button_change'] = "Change";
$lang['images_button_remove'] = "Remove";
$lang['images_button_upload'] = "Upload Image";

$lang['images_error_heading'] = "Ouch! Something went wrong";
$lang['images_success_heading'] = "Success!";
$lang['images_success_message'] = "Your image was uploaded successfully!";

$lang['images_tab_myimages'] = "My Images";
$lang['images_tab_otherimages'] = "Other Images";

$lang['images_button_view'] = "view";
$lang['images_button_delete'] = "delete";

$lang['images_message_noimages'] = "You currently have no images uploaded. To upload images, please use the upload panel on your left.";
$lang['images_label_admin'] = "Admin";
$lang['images_label_view'] = "view";

$lang['modal_deleteimage_message'] = "Deleting this image is permanent and <b>can not be undone</b>. Are you sure you want to continue?";
$lang['modal_deleteimage_button_cancel'] = "Cancel";
$lang['modal_deleteimage_button_delete'] = "Permanently delete image";

/**** Asset Module End ****/



/**** Auth Module Start ****/

// auth/controllers/Auth.php
$lang['auth_index_title'] = "User Login.";
$lang['auth_index_validation_error'] = "The login was unsuccessful.";
$lang['auth_index_login_error'] = "The login was unsuccessful.";

$lang['auth_alogin_disabled'] = "Auto login has not been enabled";
$lang['auth_alogin_missing_token'] = "The auto login token is missing or empty";
$lang['auth_alogin_bad_token'] = "The auto login token is not valid";
$lang['auth_alogin_bad_hash'] = "The hash string is either missing or the provided hash string is invalid";
$lang['auth_alogin_missing_referer'] = "The referer header is missing or can't be read; for security reasons we can not log you in";

$lang['auth_register_title'] = "User registration.";
$lang['auth_register_user_limit_error'] = "Unfortunately we already reached our customer limit. Sorry for the inconvenience.";
$lang['auth_register_form_errors'] = "Some fields were not filled out properly: ";
$lang['auth_register_captcha_error'] = "Captcha validation mismatch.";
$lang['auth_register_email_exist_error'] = "User email already exists.";
$lang['auth_register_stripe_base_error'] = "Customer create has some problem. Please try again.";
$lang['auth_register_stripe_exception_error'] = "Customer create has some problem. Please try again.";
$lang['auth_register_user_success'] = "User registered successfully. <br>Please pay the package price.";
$lang['auth_register_free_user_success'] = "User registered successfully. <br>Please click below link for login to your account.";

$lang['auth_payment_stripe_title'] = "User registration.";
$lang['auth_payment_stripe_payment_success'] = "Payment successful. <br>Please click below link for login to your account. Be sure to check your email for a payment confirmation.";
$lang['auth_payment_stripe_payment_base_error'] = "Payment has some issues. <br>Please check your payment details and submit again.<br>";
$lang['auth_payment_stripe_payment_exception_error'] = "Payment has some issues. <br>Please check your payment details and submit again.<br>";

$lang['auth_payment_confirm_title'] = "User registration.";

$lang['auth_free_user_confirm_title'] = "User registration.";

$lang['auth_payment_card_update_title'] = "User registration.";
$lang['auth_payment_card_update_success'] = "Card update successful. <br>You can login.";
$lang['auth_payment_card_update_base_error'] = "Card update has some issues. <br>Please check your card details and submit again.<br>";
$lang['auth_payment_card_update_exception_error'] = "Card update has some issues. <br>Please check your card details and submit again.<br>";

$lang['auth_activate_key_error'] = "Activation key is invalid or expired.";

$lang['auth_forgot_title'] = "Forgot password.";
$lang['auth_forgot_email_error'] = "We cant find your email address.";
$lang['auth_forgot_status_error'] = "Your account is not active.";
$lang['auth_forgot_message1'] = "<strong>A password reset has been requested for this email account</strong><br>";
$lang['auth_forgot_message2'] = "<strong>Please click:</strong> ";
$lang['auth_forgot_success'] = "A password reset link has been sent to your email address. Please check your email.";
$lang['auth_forgot_error'] = "There is an error occurred, please try again later.";

$lang['auth_reset_password_title'] = "Reset password.";
$lang['auth_reset_password_url_error'] = "Can't reset password, invalid reset link.";
$lang['auth_reset_password_invalid_key_error'] = "Password reset key is invalid or expired.";
$lang['auth_reset_password_update_error'] = "There was a problem updating your password.";
$lang['auth_reset_password_update_success'] = "Your password has been updated. You may now login.";

// auth/views/forgot.php
$lang['forgot_password_subheading'] = "Please enter your Email so we can send you an email to reset your password.";
$lang['forgot_password_input_email_placeholder'] = "Your email address";
$lang['forgot_password_submit_btn'] = "Submit";
$lang['forgot_backlink'] = "back to login";

// auth/views/login.php
$lang['login_input_email_placeholder'] = "Your email address";
$lang['login_input_password_placeholder'] = "Your password";
$lang['login_remember_me'] = "Remember me";
$lang['login_button_login'] = "Log me in";
$lang['login_lost_password'] = "Lost your password?";
$lang['login_signup_heading'] = "Join <b>today</b>";
$lang['login_button_signup'] = "SIGN UP NOW";

// auth/views/payment_card_update.php
$lang['payment_card_update_heading'] = "Card Update";
$lang['payment_card_update_input_card_number_placeholder'] = "Card Number";
$lang['payment_card_update_select_card_month_placeholder'] = "Select Expire Month";
$lang['payment_card_update_select_card_year_placeholder'] = "Select Expire Year";
$lang['payment_card_update_input_card_cvc_placeholder'] = "CVC Number";
$lang['payment_card_update_button'] = "Update Card Details";
$lang['payment_card_update_backlink'] = "back to login";

// auth/views/payment_confirm.php
$lang['payment_confirm_backlink'] = "back to login";

// auth/views/payment_stripe.php
$lang['payment_stripe_heading'] = "Signup";
$lang['payment_stripe_input_card_number_placeholder'] = "Card Number";
$lang['payment_stripe_select_card_month_placeholder'] = "Select Expire Month";
$lang['payment_stripe_select_card_year_placeholder'] = "Select Expire Year";
$lang['payment_stripe_input_card_cvc_placeholder'] = "CVC Number";
$lang['payment_stripe_button'] = "Create Account";
$lang['payment_stripe_upgrade_button'] = "Upgrade now";
$lang['payment_stripe_backlink'] = "back to login";

// auth/views/register.php
$lang['register_heading'] = "Sign up";
$lang['register_input_first_name_placeholder'] = "First name *";
$lang['register_input_last_name_placeholder'] = "Last name *";
$lang['register_input_email_placeholder'] = "Email *";
$lang['register_input_password_placeholder'] = "Password *";
$lang['register_input_password_confirm_placeholder'] = "Confirm password *";
$lang['register_select_package'] = "Choose a package";
$lang['register_input_captcha_placeholder'] = "Enter the letters from the image";
$lang['register_create_account_button'] = "Create Account";
$lang['register_backlink'] = "back to login";

// auth/views/reset_password.php
$lang['reset_password_subheading'] = "Please enter your new password.";
$lang['reset_password_input_password_placeholder'] = "Please enter your new password.";
$lang['reset_password_input_re_password_placeholder'] = "Please re-enter your new password.";

// auth/views/email/confirmation.tpl.php
$lang['email_confirmation_heading'] = "Dear %s";
$lang['email_confirmation_sub_heading'] = "Your payment was successful and your account is activated. %s";
$lang['email_confirmation_link'] = "Click here for login";

// auth/views/email/forgot_password.tpl.php
$lang['email_forgot_password_heading'] = "Reset Password for %s";
$lang['email_forgot_password_sub_heading'] = "Please click this link to %s.";
$lang['email_forgot_password_link'] = "Reset Your Password";

// auth/views/email/free_user_confirmation.tpl.php
$lang['email_free_user_confirmation_heading'] = "Dear %s";
$lang['email_free_user_confirmation_sub_heading'] = "Your free account is activated successfully. %s";
$lang['email_free_user_confirmation_link'] = "Click here for login";

// auth/views/email/new_password.tpl.php
$lang['email_new_password_heading'] = "New Password for %s";
$lang['email_new_password_sub_heading'] = "Your password has been reset to: %s";

// auth/views/email/payment_failed.tpl.php
$lang['email_payment_failed_heading'] = "Dear %s";
$lang['email_payment_failed_sub_heading'] = "Your payment (amount %s) is failed. Please update your card details. %s";
$lang['email_payment_failed_link'] = "Click here for update your card details.";

/**** Auth Module End ****/



/**** Migrate Module Start ****/

// migrate/controllers/Migrate.php
$lang['migrate_success'] = "Your database has been updated to the latest version.";
$lang['migrate_failure'] = "Your database has not been updated to the latest version. please contact owner.";

/**** Migrate Module End ****/



/**** Package Module Start ****/

// package/controllers/Package.php
$lang['package_admin_permission_error'] = "You need to be admin to do this.";

$lang['package_create_form_validation_error_heading'] = "Ouch! Something went wrong:";
$lang['package_create_form_validation_error_message'] = "Something went wrong when trying to cerate the new package, please see the errors below:<br><br>";
$lang['package_create_statement_descriptor'] = "Subscription.";
$lang['package_create_stripe_base_error_heading'] = "Ouch! Something went wrong:";
$lang['package_create_stripe_base_error_message'] = "Something went wrong when trying to update the package, please see the errors below:<br><br>";
$lang['package_create_stripe_exception_error_heading'] = "Ouch! Something went wrong:";
$lang['package_create_stripe_exception_error_message'] = "Something went wrong when trying to update the package, please see the errors below:<br><br>";
$lang['package_create_success_heading'] = "Hooray!";
$lang['package_create_success_message'] = "The new package was created successfully!";

$lang['package_update_form_validation_error_heading'] = "Ouch! Something went wrong:";
$lang['package_update_form_validation_error_message'] = "Something went wrong when trying to update the package, please see the errors below:<br><br>";
$lang['package_update_stripe_base_error_heading'] = "Ouch! Something went wrong:";
$lang['package_update_stripe_base_error_message'] = "Something went wrong when trying to update the package, please see the errors below:<br><br>";
$lang['package_update_stripe_exception_error_heading'] = "Ouch! Something went wrong:";
$lang['package_update_stripe_exception_error_message'] = "Something went wrong when trying to update the package, please see the errors below:<br><br>";
$lang['package_update_success_heading'] = "Hooray!";
$lang['package_update_success_message'] = "The package was updated successfully!";

$lang['package_delete_stripe_invalid_request_error'] = "No such plan.";
$lang['package_delete_stripe_base_error'] = "Facing issue with stripe, <br>";
$lang['package_delete_stripe_exception_error'] = "Facing issue with stripe, <br>";
$lang['package_delete_success'] = "The package has been deleted.";
$lang['package_delete_error'] = "We couldn't remove the package. Please reload and try again.";

$lang['package_toggle_status_inactive'] = "The package has been deactivated; user can not register under this package.";
$lang['package_toggle_status_active'] = "The package has been activated; user can now register under this package.";

// package/views/package_details.php
$lang['package_details_price_label'] = "Price : ";
$lang['package_details_input_name_placeholder'] = "Package Name";
$lang['package_details_input_sites_number_placeholder'] = "Number of Sites";
$lang['package_details_input_pages_number_placeholder'] = "Number of Pages";
$lang['package_details_input_hosting_option_placeholder'] = "Hosting Option";
$lang['package_details_input_price_placeholder'] = "Price";
$lang['package_details_input_currency_placeholder'] = "Currency";
$lang['package_details_input_subscription_placeholder'] = "Interval";
$lang['package_details_button_udpate_package'] = "Update Package";
$lang['package_details_button_delete_package'] = "Delete Package";
$lang['package_details_button_disable_package'] = "Disable";
$lang['package_details_button_enable_package'] = "Enable";
$lang['package_details_ribbon_label'] = "disabled";

// package/views/package_details_form.php
$lang['package_details_form_input_name_placeholder'] = "Package Name";
$lang['package_details_form_input_sites_number_placeholder'] = "Number of Sites";
$lang['package_details_form_input_hosting_option_placeholder'] = "Hosting Option";
$lang['package_details_form_input_price_placeholder'] = "Price";
$lang['package_details_form_input_currency_placeholder'] = "Currency";
$lang['package_details_form_input_subscription_placeholder'] = "Interval";
$lang['package_details_form_button_udpate_package'] = "Update Package";

// package/views/package_table.php
$lang['package_table_package'] = "Package";
$lang['package_table_price'] = "Price";
$lang['package_table_currency'] = "Currency";
$lang['package_table_interval'] = "Interval";
$lang['package_table_id'] = "ID";
$lang['package_table_status'] = "Status";

// package/views/packages.php
$lang['package_heading'] = "Packages";
$lang['package_button_addnew'] = "Add New Package";
$lang['package_empty_paypal_api_heading'] = "PayPal API details are missing";
$lang['package_empty_paypal_api_message'] = "Your PayPal <b>API details</b> have not yet been provided. You can only create free packages. To create paid package, please enter these <b>API details</b> through Settings > Payment Settings. Once entered, you can start adding paid packages.";
$lang['package_empty_stripe_key_heading'] = "Stripe keys are missing";
$lang['package_empty_stripe_key_message'] = "Your Stripe <b>Secret key</b> and <b>Publishable key</b> have not yet been provided. You can only create free packages. To create paid package, please enter these keys through Settings > Payment Settings. Once entered, you can start adding paid packages.";
$lang['package_empty_package_heading'] = "No packages found";
$lang['package_empty_package_message'] = "You don't have any package, please add one by clicking the <b>Add New Package</b> button.";

$lang['package_modal_newpackage_heading'] = "Create a new package";
$lang['package_modal_newpackage_loadertext'] = "Creating new package...";
$lang['package_modal_newpackage_name'] = "Package name";
$lang['package_modal_newpackage_number_of_sites'] = "Number of sites";
$lang['package_modal_newpackage_hosting_option'] = "Hosting Option";
$lang['package_modal_newpackage_placeholder_hosting_option'] = "Choose one or more options";
$lang['package_modal_newpackage_hosting_subfolder'] = "Sub-Folder";
$lang['package_modal_newpackage_hosting_subdomain'] = "Sub-Domain";
$lang['package_modal_newpackage_hosting_custom_domain'] = "Custom Domain";
$lang['package_modal_newpackage_export_site'] = "Export Site";
$lang['package_modal_newpackage_ftp_publish'] = "FTP Publish";
$lang['package_modal_newpackage_edit_sourcecode'] = "Edit HTML code";
$lang['package_modal_newpackage_pages_number'] = "Number of pages";
$lang['package_modal_newpackage_pages_number_option'] = "Maximum allowed number of pages on one website. Leave blank for unlimited number.";
$lang['package_modal_newpackage_disk_space'] = "Upload space (in MB)";
$lang['package_modal_newpackage_placeholder_disk_space'] = "Disk Space in MB";
$lang['package_modal_newpackage_templates'] = "Templates";
$lang['package_modal_newpackage_blocks'] = "Blocks";
$lang['package_modal_newpackage_nocategory'] = "No category";
$lang['package_modal_newpackage_blocksenable'] = "Limit block access";
$lang['package_modal_newpackage_price'] = "Price";
$lang['package_modal_newpackage_currency'] = "Currency";
$lang['package_modal_newpackage_subscription'] = "Interval";
$lang['package_modal_newpackage_status'] = "Status";
$lang['package_modal_newpackage_status_active'] = "Active";
$lang['package_modal_newpackage_status_inactive'] = "Inactive";
$lang['package_modal_newpackage_button_create'] = "Create Package";
$lang['package_modal_newpackage_subscription_weekly'] = "Weekly";
$lang['package_modal_newpackage_subscription_monthly'] = "Monthly";
$lang['package_modal_newpackage_subscription_yearly'] = "Yearly";
$lang['package_modal_newpackage_subscription_threemonths'] = "Every 3 months";
$lang['package_modal_newpackage_subscription_sixmonths'] = "Every 6 months";

$lang['package_modal_edit_heading'] = "Edit package";
$lang['package_modal_edit_package_button_update'] = "Update Package";
$lang['package_modal_delete_package_message'] = "Deleting this package will result in all associated data being deleted and <b>can not be undone</b>. Are you sure you want to continue?";
$lang['package_modal_delete_package_delete_in_stripe'] = "Also it will delete the plan from stripe but it's subscribers will remain in this plan unless you change their plan. For more information <a href='https://stripe.com/docs/subscriptions/plans#changing-and-deleting-plans' target='_blank'>Click Here</a>";
$lang['package_create_error1_heading'] = "Ouch! Something went wrong:";
$lang['package_create_error1_message'] = "Something went wrong when trying to cerate the new package, please see the errors below:<br><br>";

/**** Package Module End ****/

/**** Integrations Module Start ****/

// integrations/views/integrations.php
$lang['integrations_heading'] = "Integrations";
$lang['integrations_label_not_configured'] = "Not configured";
$lang['integrations_label_configured'] = "Configured";
$lang['integrations_button_configure'] = "Configure integration";
$lang['integrations_button_update'] = "Update integration";
$lang['integrations_head_configure'] = "Configure";
$lang['integrations_head_integration'] = "integration";
$lang['integrations_button_cancel'] = "Cancel";
$lang['integrations_button_save'] = "Save integration";
$lang['integrations_verifier'] = "Enter your verifier code below, Please";
$lang['integration_list_id_select'] = "Select your List ID below, Please";
$lang['integrations_verifier_error'] = "Your credentials aren't correct, Please enter correct credentials";
$lang['integrations_verifier_save'] = "Verify";
$lang['mail_api_integrations_success'] = "Integration has been configured successfully";
$lang['mail_api_update_integrations_success'] = "Integration has been updated successfully";
$lang['mail_api_integrations_error'] = "Integration hasn't been configured";
$lang['mail_api_integrations_error_heading'] = "Ouch! Something went wrong:";
$lang['mail_api_integrations_success_heading'] = "Hooray!";
$lang['mail_api_aut_integrations_error'] = "Not configured, invalid credentials";
/**** Integrations Module End ****/

/**** Settings Module Start ****/

// settings/controllers/Settings.php
$lang['settings_update_error'] = "There were some issues with your data and we could not save your data right now, please see the details below:<br><br>";
$lang['settings_update_success'] = "Your settings were saved successfully!";
$lang['settings_update_invalid_key_error'] = "Your settings were saved successfully! But the License Key is invalid, Please re-check your License key. If your license key is correct but you still get this message, please contact with support@bloxby.com";

// settings/views/settings.php
$lang['settings_heading'] = "Settings";

$lang['settings_tab_application_settings'] = "Application Settings";
$lang['settings_tab_payment_settings'] = "Payment Settings";
$lang['settings_tab_update_settings'] = "Update Settings";
$lang['settings_tab_white_label'] = "White label";
$lang['settings_tab_integration'] = "Integration";

$lang['settings_warning_heading'] = "Be careful please!";
$lang['settings_warning_message'] = "Please be cautious when making changes to the settings below. Unless you know what you're doing, don't make changes to any of these.";

$lang['settings_requiredfields'] = "* required fields, can not be empty!";
$lang['settings_button_update'] = "Update Settings";

$lang['settings_confighelp_heading'] = "Config help";
$lang['settings_confighelp_message'] = "Click any of the setting boxes will display details about that setting in this box here :)";

$lang['settings_update_whitelabel_error1_message'] = "Something went wrong when trying to upload your image, please see the details below:<br>";

$lang['settings_confirmation_are_you_sure'] = "Are you sure?";
$lang['settings_confirmation_yes'] = "Yes";
$lang['settings_confirmation_no'] = "Cancel";

$lang['settings_button_reset_logo'] = "Reset white-label logo";

$lang['settings_heading_logo'] = "Logo";
$lang['settings_label_logo_image'] = "Logo image";
$lang['settings_fileinput_select'] = "Select file";
$lang['settings_fileinput_change'] = "Change";
$lang['settings_fileinput_remove'] = "Remove";
$lang['settings_label_logo_text'] = "Logo text";

$lang['settings_button_reset_colors'] = "Reset white-label colors";

$lang['settings_heading_colors'] = "Colors";
$lang['settings_colors_tab_general'] = "General";
$lang['settings_colors_tab_sites'] = "Sites";
$lang['settings_colors_tab_pagebuilder'] = "Page Builder";
$lang['settings_colors_tab_images'] = "Images";
$lang['settings_colors_tab_templates'] = "Templates";
$lang['settings_colors_tab_elements'] = "Elements";
$lang['settings_colors_tab_packages'] = "Packages";
$lang['settings_colors_tab_users'] = "Users";

$lang['settings_colors_general_general'] = "General";
$lang['settings_colors_general_general_background_color'] = "background color";
$lang['settings_colors_general_general_page_h1_color'] = "page h1 color";
$lang['settings_colors_general_general_dashed_border_color'] = "dashed border color";

$lang['settings_colors_general_navigation'] = "Main navigation";
$lang['settings_colors_general_navigation_background_color'] = "background color";
$lang['settings_colors_general_navigation_logo_text_color'] = "logo text color";
$lang['settings_colors_general_navigation_link_text_color'] = "link text color";
$lang['settings_colors_general_navigation_active_link_background_color'] = "active link background color";
$lang['settings_colors_general_navigation_active_link_text_color'] = "active link text color";
$lang['settings_colors_general_navigation_dropdown_background_color'] = "dropdown background color";
$lang['settings_colors_general_navigation_dropdown_arrow_color'] = "dropdown arrow color";
$lang['settings_colors_general_navigation_dropdown_link_text_color'] = "dropdown link text color";

$lang['settings_colors_general_dropdown_selectors'] = "Dropdown selectors";
$lang['settings_colors_general_dropdown_selectors_dropdown_button_background_color'] = "dropdown button background color";
$lang['settings_colors_general_dropdown_selectors_dropdown_background_color'] = "dropdown background color";
$lang['settings_colors_general_dropdown_selectors_dropdown_link_text_color'] = "dropdown link text color";
$lang['settings_colors_general_dropdown_selectors_dropdown_active_background_color'] = "dropdown active background color";
$lang['settings_colors_general_dropdown_selectors_dropdown_hover_background_color'] = "dropdown hover background color";
$lang['settings_colors_general_dropdown_selectors_dropdown_hover_text_color'] = "dropdown hover text color";

$lang['settings_colors_general_fields'] = "Fields";
$lang['settings_colors_general_fields_field_border_color'] = "field border color";
$lang['settings_colors_general_fields_focused_field_border_color'] = "focused field border color";

$lang['settings_colors_general_tabs'] = "Tabs";
$lang['settings_colors_general_tabs_tab_border_color'] = "tab border color";
$lang['settings_colors_general_tabs_tab_background_color'] = "tab background color";
$lang['settings_colors_general_tabs_tab_text_color'] = "tab text color";
$lang['settings_colors_general_tabs_tab_active_hover_text_color'] = "active/hover tab text color";

$lang['settings_colors_general_buttons'] = "Buttons";
$lang['settings_colors_general_buttons_primary_background_color'] = '<span class="label label-primary">primary</span> background color</label>';
$lang['settings_colors_general_buttons_primary_hover_background_color'] = '<span class="label label-primary">primary</span> hover background color';
$lang['settings_colors_general_buttons_primary_text_color'] = '<span class="label label-primary">primary</span> text color';
$lang['settings_colors_general_buttons_info_background_color'] = '<span class="label label-info">info</span> background color</label>';
$lang['settings_colors_general_buttons_info_hover_background_color'] = '<span class="label label-info">info</span> hover background color</label>';
$lang['settings_colors_general_buttons_info_text_color'] = '<span class="label label-info">info</span> text color</label>';
$lang['settings_colors_general_buttons_default_background_color'] = '<span class="label label-default">default</span> background color</label>';
$lang['settings_colors_general_buttons_default_hover_background_color'] = '<span class="label label-default">default</span> hover background color';
$lang['settings_colors_general_buttons_default_text_color'] = '<span class="label label-default">default</span> text color';
$lang['settings_colors_general_buttons_danger_background_color'] = '<span class="label label-danger">danger</span> background color</label>';
$lang['settings_colors_general_buttons_danger_hover_background_color'] = '<span class="label label-danger">danger</span> hover background color';
$lang['settings_colors_general_buttons_danger_text_color'] = '<span class="label label-danger">danger</span> text color';
$lang['settings_colors_general_buttons_inverse_background_color'] = '<span class="label label-inverse">inverse</span> background color</label>';
$lang['settings_colors_general_buttons_inverse_hover_background_color'] = '<span class="label label-inverse">inverse</span> hover background color';
$lang['settings_colors_general_buttons_inverse_text_color'] = '<span class="label label-inverse">inverse</span> text color';

$lang['settings_colors_general_modals'] = "Modals";
$lang['settings_colors_general_modals_modal_header_background_color'] = "modal header background color";
$lang['settings_colors_general_modals_modal_header_text_color'] = "modal header text color";
$lang['settings_colors_general_modals_modal_body_background_color'] = "modal body background color";
$lang['settings_colors_general_modals_modal_body_text_color'] = "modal body text color";
$lang['settings_colors_general_modals_modal_footer_background_color'] = "modal footer background color";
$lang['settings_colors_general_modals_modal_footer_text_color'] = "modal footer text color";
$lang['settings_colors_general_modals_modal_panel_dashed_border_color'] = "modal panel dashed border color";
$lang['settings_colors_general_modals_modal_panel_background_color'] = "modal panel background color";
$lang['settings_colors_general_modals_modal_panel_text_color'] = "modal panel text color";

$lang['settings_colors_general_alerts'] = "Alerts";
$lang['settings_colors_general_alerts_alert_success_background_color'] = "alert success background color";
$lang['settings_colors_general_alerts_alert_success_border_color'] = "alert success border color";
$lang['settings_colors_general_alerts_alert_success_text_color'] = "alert success text color";
$lang['settings_colors_general_alerts_alert_danger_background_color'] = "alert danger background color";
$lang['settings_colors_general_alerts_alert_danger_border_color'] = "alert danger border color";
$lang['settings_colors_general_alerts_alert_danger_text_color'] = "alert danger text color";
$lang['settings_colors_general_alerts_alert_warning_background_color'] = "alert warning background color";
$lang['settings_colors_general_alerts_alert_warning_border_color'] = "alert warning border color";
$lang['settings_colors_general_alerts_alert_warning_text_color'] = "alert warning text color";

$lang['settings_colors_sites_site_grid'] = "Site grid";
$lang['settings_colors_sites_alert'] = '<h4>Please note</h4>
												  	<p>
												  		Colors applied to the "site grid" will be automatically applied to the "<b>template grid</b>" as well.
												  	</p>';
$lang['settings_colors_sites_site_grid_site_border_color'] = "site border color";
$lang['settings_colors_sites_site_grid_site_background_color'] = "site background color";
$lang['settings_colors_sites_site_grid_site_text_color'] = "site text color";
$lang['settings_colors_sites_site_grid_window_top_bottom_background_color'] = "window top/bottom background color";
$lang['settings_colors_sites_site_grid_window_top_bottom_text_color'] = "window top/bottom text color";
$lang['settings_colors_sites_site_grid_window_top_red_button'] = "window top red button";
$lang['settings_colors_sites_site_grid_window_top_yellow_button'] = "window top yellow button";
$lang['settings_colors_sites_site_grid_window_top_green_button'] = "window top green button";
$lang['settings_colors_sites_site_grid_dashed_border_color'] = "dashed border color";
$lang['settings_colors_sites_site_grid_public_site_background_color'] = "public site background color";
$lang['settings_colors_sites_site_grid_public_site_border_color'] = "public site border color";
$lang['settings_colors_sites_site_grid_public_site_text_color'] = "public site text color";
$lang['settings_colors_sites_site_grid_public_site_dashed_border_color'] = "public site dashed border color";

$lang['settings_colors_sites_new_site_modal'] = "New site modal";
$lang['settings_colors_sites_new_site_modal_sidebar_background_color'] = "sidebar background color";
$lang['settings_colors_sites_new_site_modal_sidebar_button_text_color'] = "sidebar button text color";
$lang['settings_colors_sites_new_site_modal_sidebar_active_hover_background_color'] = "sidebar active/hover background color";
$lang['settings_colors_sites_new_site_modal_sidebar_active_hover_text_color'] = "sidebar active/hover text color";
$lang['settings_colors_sites_new_site_modal_sidebar_selected_template_border_color'] = "selected template border color";

$lang['settings_colors_pagebuilder_topnavbar'] = "Top nav bar";
$lang['settings_colors_pagebuilder_topnavbar_top_background_color'] = "top bar background color";
$lang['settings_colors_pagebuilder_topnavbar_top_button_background_color'] = "top bar button background color";
$lang['settings_colors_pagebuilder_topnavbar_top_button_text_color'] = "top bar button text color";
$lang['settings_colors_pagebuilder_topnavbar_top_button_active_background_color'] = "top bar button active background color";
$lang['settings_colors_pagebuilder_topnavbar_gridview_toggle_deactivated_left_background_color'] = "gridview toggle de-activated left background color";
$lang['settings_colors_pagebuilder_topnavbar_gridview_toggle_deactivated_right_background_color'] = "gridview toggle de-activated right background color";
$lang['settings_colors_pagebuilder_topnavbar_gridview_toggle_deactivated_icon_color'] = "gridview toggle de-activated icon color";
$lang['settings_colors_pagebuilder_topnavbar_gridview_toggle_activated_left_background_color'] = "gridview toggle activated left background color";
$lang['settings_colors_pagebuilder_topnavbar_gridview_toggle_activated_right_background_color'] = "gridview toggle activated right background color";
$lang['settings_colors_pagebuilder_topnavbar_gridview_toggle_activated_icon_color'] = "gridview toggle activated icon color";

$lang['settings_colors_pagebuilder_sidebar'] = "Sidebar";
$lang['settings_colors_pagebuilder_sidebar_background_color'] = "sidebar background color";
$lang['settings_colors_pagebuilder_sidebar_link_icon_color'] = "sidebar link/icon color";
$lang['settings_colors_pagebuilder_sidebar_link_icon_active_hover_color'] = "sidebar link/icon active/hover color";
$lang['settings_colors_pagebuilder_sidebar_link_icon_active_hover_background_color'] = "sidebar button active/hover background color";
$lang['settings_colors_pagebuilder_sidebar_slideout_background_color'] = "sidebar slide-out background color";
$lang['settings_colors_pagebuilder_sidebar_slideout_header_text_color'] = "sidebar slide-out header text color";
$lang['settings_colors_pagebuilder_sidebar_slideout_button_text_color'] = "sidebar slide-out button text color";
$lang['settings_colors_pagebuilder_sidebar_slideout_button_hover_active_text_color'] = "sidebar slide-out button hover/active text color";
$lang['settings_colors_pagebuilder_sidebar_slideout_button_hover_active_background_color'] = "sidebar slide-out button hover/active background color";
$lang['settings_colors_pagebuilder_sidebar_dashed_border_color'] = "sidebar dashed border color";

$lang['settings_colors_pagebuilder_canvas'] = "Canvas";
$lang['settings_colors_pagebuilder_canvas_background_color'] = "canvas background color";
$lang['settings_colors_pagebuilder_canvas_window_bar_background_color'] = "window bar background color";
$lang['settings_colors_pagebuilder_canvas_window_bar_title_text_color'] = "window bar title text color";
$lang['settings_colors_pagebuilder_canvas_window_bar_red_button'] = "window bar red button color";
$lang['settings_colors_pagebuilder_canvas_window_bar_yellow_button'] = "window bar yellow button color";
$lang['settings_colors_pagebuilder_canvas_window_bar_green_button'] = "window bar green button color";

$lang['settings_colors_pagebuilder_frametoolbar'] = "Frame toolbar";
$lang['settings_colors_pagebuilder_frametoolbar_left_background_color'] = "frame toolbar left background color";
$lang['settings_colors_pagebuilder_frametoolbar_left_text_color'] = "frame toolbar left text color";
$lang['settings_colors_pagebuilder_frametoolbar_checkbox_checked_background_color'] = "frame toolbar checkbox checked background color";
$lang['settings_colors_pagebuilder_frametoolbar_button_background_color'] = "frame toolbar button background color";
$lang['settings_colors_pagebuilder_frametoolbar_button_icon_color'] = "frame toolbar button icon color";
$lang['settings_colors_pagebuilder_frametoolbar_button_hover_background_color'] = "frame toolbar button hover background color";
$lang['settings_colors_pagebuilder_frametoolbar_button_hover_icon_color'] = "frame toolbar button hover icon color";

$lang['settings_colors_pagebuilder_detaileditor'] = "Detail editor";
$lang['settings_colors_pagebuilder_detaileditor_background_color'] = "background color";
$lang['settings_colors_pagebuilder_detaileditor_title_text_color'] = "title text color";
$lang['settings_colors_pagebuilder_detaileditor_close_icon_color'] = "close icon color";
$lang['settings_colors_pagebuilder_detaileditor_breadcrumb_background_color'] = "breadcrumb background color";
$lang['settings_colors_pagebuilder_detaileditor_breadcrumb_text_color'] = "breadcrumb text color";
$lang['settings_colors_pagebuilder_detaileditor_breadcrumd_linkicon_text_color'] = "breadcrumb link/icon text color";
$lang['settings_colors_pagebuilder_detaileditor_tab_text_color'] = "tab text color";
$lang['settings_colors_pagebuilder_detaileditor_active_tab_background_color'] = "active tab background color";
$lang['settings_colors_pagebuilder_detaileditor_active_tab_border_color'] = "active tab border color";
$lang['settings_colors_pagebuilder_detaileditor_label_text_color'] = "label text color";
$lang['settings_colors_pagebuilder_detaileditor_parallax_warning_border_color'] = "parallax warning border color";
$lang['settings_colors_pagebuilder_detaileditor_parallax_warning_background_color'] = "parallax warning background color";
$lang['settings_colors_pagebuilder_detaileditor_parallax_warning_text_color'] = "parallax warning text color";
$lang['settings_colors_pagebuilder_detaileditor_parallax_warning_close_icon'] = "parallax warning close icon color";
$lang['settings_colors_pagebuilder_detaileditor_dashed_border_color'] = "dashed border color";
$lang['settings_colors_pagebuilder_detaileditor_image_tab_toggle_link'] = "image tab - toggle link color";

$lang['settings_colors_images_grid'] = "Images grid";
$lang['settings_colors_images_add_background_color'] = 'Add Image" background color';
$lang['settings_colors_images_add_text_color'] = '"Add Image" text color';
$lang['settings_colors_images_add_dashed_border_color'] = '"Add Image" dashed border color';
$lang['settings_colors_images_add_hover_background_color'] = '"Add Image" hover background color';
$lang['settings_colors_images_add_hover_text_color'] = '"Add Image" hover text color';
$lang['settings_colors_images_background_color'] = "image background color";
$lang['settings_colors_images_border_color'] = "image border color";
$lang['settings_colors_images_hover_background_color'] = "image hover background color";
$lang['settings_colors_images_hover_border_color'] = "image hover border color";

$lang['settings_colors_images_edit_panel'] = "Images edit panel";
$lang['settings_colors_images_edit_panel_background_color'] = "edit panel background color";
$lang['settings_colors_images_edit_panel_text_color'] = "edit panel text color";
$lang['settings_colors_images_edit_panel_image_link_text_color'] = "image link text color";
$lang['settings_colors_images_edit_panel_dashed_border_color'] = "dashed border color";
$lang['settings_colors_images_edit_panel_delete_link_color'] = '"Delete image" link text color';

$lang['settings_colors_templates_category_modal'] = "Template category modal";
$lang['settings_colors_templates_alert'] = '<h4>Please note</h4>
												  	<p>
												  		Colors applied to the "template categories modal" will be automatically applied to the "<b>block categories modal</b>" and "<b>components categories modal</b>" as well.
												  	</p>';
$lang['settings_colors_templates_category_modal_table_border_color'] = "categories table border color";
$lang['settings_colors_templates_category_modal_table_background_color'] = "categories table background color";
$lang['settings_colors_templates_category_modal_table_text_color'] = "categories table text color";
$lang['settings_colors_templates_category_modal_table_heading_background_color'] = "categories table heading background color";
$lang['settings_colors_templates_category_modal_table_heading_text_color'] = "categories table heading text color";

$lang['settings_colors_elements_blocks_grid'] = "Elements - Blocks grid";
$lang['settings_colors_elements_blocks_grid_border_color'] = "block grid border color";
$lang['settings_colors_elements_blocks_grid_background_color'] = "block grid background color";
$lang['settings_colors_elements_blocks_grid_hover_border_color'] = "block grid hover border color";
$lang['settings_colors_elements_blocks_grid_hover_background_color'] = "block grid hover background color";

$lang['settings_colors_elements_components_grid'] = "Elements - Components grid";
$lang['settings_colors_elements_components_grid_border_color'] = "components grid border color";
$lang['settings_colors_elements_components_grid_background_color'] = "components grid background color";
$lang['settings_colors_elements_components_grid_hover_border_color'] = "components grid hover border color";
$lang['settings_colors_elements_components_grid_hover_background_color'] = "components grid hover background color";

$lang['settings_colors_packages_table'] = "Packages table";
$lang['settings_colors_packages_table_border_color'] = "packages table border color";
$lang['settings_colors_packages_table_background_color'] = "packages table background color";
$lang['settings_colors_packages_table_text_color'] = "packages table text color";
$lang['settings_colors_packages_table_heading_background_color'] = "packages table heading background color";
$lang['settings_colors_packages_table_heading_text_color'] = "packages table heading text color";

$lang['settings_colors_user_grid'] = "User grid";
$lang['settings_colors_user_border_color'] = "user border color";
$lang['settings_colors_user_top_background_color'] = "user top background color";
$lang['settings_colors_user_top_bottom_border_color'] = "user top bottom border color";
$lang['settings_colors_user_top_heading_text_color'] = "user top heading text color";
$lang['settings_colors_user_top_link_text_color'] = "user top link text color";
$lang['settings_colors_user_top_link_icon_color'] = "user top link icon color";

$lang['settings_heading_api'] = "Public API";
$lang['settings_integration_api_on'] = "Activate REST api";
$lang['settings_integration_api_whitelist_enabled'] = "Enable IP white list";
$lang['settings_integration_api_whitelist'] = "IP white list";
$lang['settings_integration_api_keys'] = "Public keys";
$lang['settings_heading_alogin'] = "Automated login";
$lang['settings_heading_alogin_on'] = "Activate automated login";
$lang['settings_heading_alogin_key'] = "Hash key";

$lang['elements_dir'] = "Elements directory";
$lang['images_dir'] = "Image directory";
$lang['images_uploadDir'] = "Image upload directory";
$lang['upload_max_size'] = "Maximum upload Filesize";
$lang['export_pathToAssets'] = "Assets included in the export";
$lang['export_fileName'] = "The export file name";
$lang['language'] = "Application language";
$lang['google_api'] = "Google Maps API key";
$lang['custom_fonts'] = "Google Fonts";
$lang['show_locked_blocks'] = "Show disabled blocks as blocked";


$lang['stripe_secret_key'] = "Stripe secret key";
$lang['stripe_publishable_key'] = "Stripe publishable key";
$lang['stripe_test_mode'] = "Stripe test mode";
$lang['paypal_api_username'] = "PayPal API username";
$lang['paypal_api_password'] = "Paypal API password";
$lang['paypal_api_signature'] = "Paypal API signature";
$lang['paypal_test_mode'] = "PayPal test mode";
$lang['payment_gateway'] = "Payment gateway";

$lang['auto_update'] = "Auto update";
$lang['license_key'] = "License key";
$lang['overwrite_blocks'] = "Overwrite blocks";
/*
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
$lang[''] = "";
*/

/**** Settings Module End ****/



/**** Shared Module Start ****/

// shared/views/alert.php
$lang['application_link'] = 'Builder';
$lang['alert_page_title'] = "Builder Alert";

// shared/views/ftplist.php
$lang['ftplist_uponelevel'] = "Up one level";

// shared/views/modal_account.php
$lang['account_myaccount'] = "My Account";
$lang['account_tab_account'] = "Account";
$lang['account_tab_membership'] = "My Membership";
$lang['account_tab_settings'] = "Settings";

$lang['account_label_first_name'] = "First name";
$lang['account_label_last_name'] = "Last name";
$lang['account_button_updatedetails'] = "Update Details";
$lang['account_label_username'] = "User name";
$lang['account_label_password'] = "Password";

$lang['account_label_package'] = "Package";
$lang['account_label_package_option'] = "Choose a package";
$lang['account_button_update_package'] = "Update Package";
$lang['account_button_cancel_package'] = "Cancel Subscription";
$lang['account_label_package_months'] = "month(s)";

// shared/views/modal_sitesettings.php
$lang['sitesettings_sitesettings'] = "Site Settings";
$lang['sitesettings_button_savesettings'] = "Save Settings";

// shared/views/my404.php
$lang['my404_header'] = "404, Not Found!";
$lang['my404_message'] = "Oops! You have requested the page that is no longer there.";

// shared/views/myimages
$lang['myimages_newimage_label'] = "<b>+</b><strong>Add Image</strong><br>max size: %sMB";

// shared/views/nav.php
$lang['nav_toggle_navigation'] = "Toggle Navigation";
$lang['nav_sites'] = "Sites";
$lang['nav_imagelibrary'] = "Images";
$lang['nav_membership'] = "My Membership";
$lang['nav_users'] = "Users";
$lang['nav_settings'] = "Settings";
$lang['nav_integrations'] = "Integrations";
$lang['nav_packages'] = "Packages";
$lang['nav_templates'] = "Templates";
$lang['nav_builder_elements'] = "Elements";
$lang['nav_blocks'] = "Blocks";
$lang['nav_components'] = "Components";
$lang['nav_browser'] = "Block files";
$lang['nav_greeting'] = "Hi,";
$lang['nav_myaccount'] = "My Account";
$lang['nav_logout'] = "Logout";
$lang['nav_goback_sites'] = "Exit the builder";
$lang['nav_goback_users'] = "Back to Users";

// shared/views/pagedata.php
$lang['modal_pagesettings_pagetitle'] = "Page Title";
$lang['modal_pagesettings_pagedescription'] = "Page Meta Description";
$lang['modal_pagesettings_pagekeywords'] = "Page Meta Keywords";
$lang['modal_pagesettings_pageheaderincludes'] = "Header Includes";
$lang['modal_pagesettings_pagecss'] = "Page CSS";
$lang['modal_pagesettings_googlefonts'] = "Included Google fonts";
$lang['modal_pagesettings_googlefonts_placeholder'] = "Google fonts included on this page";
$lang['modal_pagesettings_placeholder_pagetitle'] = "Page title";
$lang['modal_pagesettings_placeholder_pagedescription'] = "Page meta description";
$lang['modal_pagesettings_placeholder_pagekeywords'] = "Page meta keywords";
$lang['modal_pagesettings_placeholder_pageadditional'] = "Additional code you'd like to include in the <head> section";
$lang['modal_pagesettings_placeholder_pagecss'] = "CSS applied specifically to this page";

// shared/views/sitedata.php
$lang['sitedata_sitedetails'] = "Site details";
$lang['sitedata_label_name'] = "Site name";
$lang['sitedata_label_globalcss'] = "Global CSS";
$lang['sitedata_label_globaljs'] = "Global header includes (including JS)";
$lang['sitedata_label_favicon'] = "Site favicon";
$lang['settings_update_favicon_error1_message'] = "Something went wrong when trying to upload your favicon, please see the details below:<br>";


$lang['sitedata_homepage_details'] = "Use as public site";
$lang['sitedata_label_home_page'] = "Use this site as main site";
$lang['sitedata_homepage_explanation'] = "With this setting you can choose to use this specific website as the public site for your site builder installation. When activated, visitors loading <b>%s</b> will be presented this website. For login to site builder installation you need to use <b>%sauth</b>";

$lang['sitedata_hostingdetails'] = "Publishing details";
$lang['sitedata_hosting_dropdown_choose'] = "Choose an option";
$lang['sitedata_hosting_dropdown_subfolder'] = "Sub folder";
$lang['sitedata_hosting_dropdown_subdomain'] = "Sub domain";
$lang['sitedata_hosting_dropdown_customdomain'] = "Custom domain";
$lang['sitedata_hosting_placeholder_sub_folder'] = "yoursite";
$lang['sitedata_hosting_placeholder_sub_domain'] = "yoursite";
$lang['sitedata_hosting_placeholder_custom_domain'] = "mydomainname.com";
$lang['sitedata_hosting_info_subfolder'] = "<p>Your site will be published on the domain + sub folder as specified above. Changes made to your site will be automatically published. If you wish to unpublish your site, simply empty the field above and click the \"Save Settings\" button. You can re-publish your site at any time.</p>";
$lang['sitedata_hosting_info_subdomain'] = "<p>Your site will be published on the sub domain as specified above. Changes made to your site will be automatically published. If you wish to unpublish your site, simply empty the field above and click the \"Save Settings\" button. You can re-publish your site at any time.</p>";
$lang['sitedata_hosting_info_customdomain'] = "<p>Your site will be published on the custom domain specified above. Please note for this to work, you will need to create an \"A\"-type DNS record for the domain and point to <b>%s</b></p><p>If you wish to unpublish your site, simply empty the field above and click the \"Save Settings\" button. You can re-publish your site at any time.</p>";
$lang['sitedata_hosting_info_customdomain_server'] = "the server hosting Bloxby";
$lang['sitedata_hosting_not_published_heading'] = "Site not published";
$lang['sitedata_hosting_not_published_message'] = "Your site is not currently published and can not be visited from the Internet.";
$lang['sitedata_hosting_published_heading'] = "Site published:";
$lang['sitedata_hosting_published_message'] = "Your site is currently published as shown below:";

$lang['sitedata_publishingdetails'] = "FTP Publishing details";
$lang['sitedata_label_ftp_sftp'] = "Choose FTP or sFTP";
$lang['sitedata_ftp'] = "FTP";
$lang['sitedata_sftp'] = "sFTP";
$lang['sitedata_label_remote_url'] = "Public URL";
$lang['sitedata_label_remote_url_placeholder'] = "Public URL, ie http://mysite.com";
$lang['sitedata_label_ftp_server'] = "FTP Server";
$lang['sitedata_label_ftp_user'] = "FTP User";
$lang['sitedata_label_ftp_password'] = "FTP Password";
$lang['sitedata_label_ftp_port'] = "FTP Port";
$lang['sitedata_label_ftp_port_placeholder'] = "21";
$lang['sitedata_label_ftp_path'] = "FTP Path";
$lang['sitedata_button_browse_server'] = "Browse Server";
$lang['sitedata_connecting_to_ftp'] = "connecting to ftp ...";
$lang['sitedata_button_test_ftp_connection'] = "Test Connection";
$lang['sitedata_testing_ftp_connection'] = "Testing Connection ...";

/**** Shared Module End ****/



/**** Sites Module Start ****/

// sites/controllers/sites.php
$lang['sites_index_title'] = "Dashboard";
$lang['sites_index_reach_site_number'] = "<a href=\"#accountModal\" data-toggle=\"modal\" data-open-tab=\"2\">Click here</a> to upgrade your plan.";
$lang['sites_index_almost_reach_site_number'] = "You have almost reach the allowed number of sites. <a href=\"#accountModal\" data-toggle=\"modal\" data-open-tab=\"2\">Click here</a> to upgrade your plan.";

$lang['sites_index_no_template_cat'] = "No specific category";

$lang['sites_deltemplate_success'] = "Template deleted successfully.";
$lang['sites_deltemplate_error1'] = "Could not delete template due to an invalid template ID. Refresh the page and try again.";

$lang['sites_create_site_exceed'] = "You have exceeded the number of sites for your package.";

$lang['sites_tsave_no_page_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_tsave_no_page_error_message'] = "Page templates should not be empty. Please add one or more blocks before saving.";
$lang['sites_tsave_template_save_success_heading'] = "Success!";
$lang['sites_tsave_template_save_success_message'] = "The template was saved successfully. The new template will be available from the left menu after reloading this page.";
$lang['sites_tsave_template_save_fail_heading'] = "Ouch!";
$lang['sites_tsave_template_save_fail_message'] = "The template good not be saved. Please reload the page and try again.";

$lang['sites_save_no_data_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_save_no_data_error_message'] = "The siteData is missing, please try again.";
$lang['sites_save_no_frame_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_save_no_frame_error_message'] = "There's noting to save. Try making some changes and saving again.";
$lang['sites_save_after_publish_success_heading'] = "Success!";
$lang['sites_save_after_publish_success_message'] = "The site has been saved successfully!";
$lang['sites_save_before_publish_success_heading'] = "Success!";
$lang['sites_save_before_publish_success_message'] = "The site has been saved successfully! You can now proceed with publishing your site.";

$lang['sites_save_error'] = "Error.";
$lang['sites_save_error_messge'] = "The changes could not be saved. Please try again. If this keeps happening, reload the builder and try saving your changes again.";

$lang['sites_site_template_sitename'] = "Temp site";

$lang['sites_site_could_not_load_error'] = "The site you're trying to load does not exist.";

$lang['sites_site_label_alltemplates'] = "All templates";

$lang['sites_siteAjax_siteID_missing_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjax_siteID_missing_error_message'] = "It appears the site ID is missing. Please refresh your page and try again.";
$lang['sites_siteAjax_site_save_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjax_site_save_error_message'] = "Something went wrong when loading the site data. Please refresh your page and try again.";

$lang['sites_siteAjaxUpdate_validation_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjaxUpdate_validation_error_message'] = "Something went wrong when saving the site data, please see the errors below:<br><br>";
$lang['sites_siteAjaxUpdate_sub_folder_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjaxUpdate_sub_folder_error_message'] = "Sub folder name already taken, please choose something else.";
$lang['sites_siteAjaxUpdate_sub_domain_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjaxUpdate_sub_domain_cloudflare_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjaxUpdate_sub_domain_cloudflare_error_content'] = "Cloudflare error: ";
$lang['sites_siteAjaxUpdate_sub_domain_error_message'] = "Sub domain name already taken, please choose something else.";
$lang['sites_siteAjaxUpdate_custom_domain_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjaxUpdate_custom_domain_error_message'] = "Domain name already taken, please choose something else.";
$lang['sites_siteAjaxUpdate_save_success_heading'] = "Yeah! All went well.";
$lang['sites_siteAjaxUpdate_save_ftp_success_message'] = "The site's details were saved successfully!";
$lang['sites_siteAjaxUpdate_save_ftp_error_message'] = "<b>The site's details were saved successfully</b>, however the provided FTP details could not be used to successfully establish a connection. Your site was not published via FTP.";
$lang['sites_siteAjaxUpdate_save_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_siteAjaxUpdate_save_error_message'] = "Something went wrong when saving the site data, please try again later.";

$lang['sites_publish_ftp_no_site_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_publish_ftp_no_site_error_message'] = "It appears the site ID is missing OR incorrect. Please refresh your page and try again.";
$lang['sites_publish_ftp_noting_to_upload_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_publish_ftp_noting_to_upload_error_message'] = "It appears there are no assets selected for publication. Please select the assets you'd like to publish and try again.";
$lang['sites_publish_ftp_connection_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_publish_ftp_connection_error_message'] = "We can not establish a connection to your FTP server, this caused by faulty connection data (server, user, password and/or port number). Please verify your connection details and update if needed before trying again. If you keep getting this error, your FTP server could be down as well.";
$lang['sites_publish_ftp_tmp_not_writable_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_publish_ftp_tmp_not_writable_error_message'] = "It appears the /tmp folder is not writable. Please make sure the server can write to this folder.";

$lang['sites_publish_no_site_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_publish_no_site_error_message'] = "It appears the site ID is missing OR incorrect. Please refresh your page and try again.";
$lang['sites_publish_noting_to_upload_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_publish_noting_to_upload_error_message'] = "It appears there are no assets selected for publication. Please select the assets you'd like to publish and try again.";

$lang['sites_trash_no_site_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_trash_no_site_error_message'] = "The site ID is missing or corrupt. Please try reloading the page and then try deleting the site once more.";
$lang['sites_trash_success_heading'] = "All set!";
$lang['sites_trash_success_message'] = "The site was successfully deleted from the system.";

$lang['sites_updatePageData_no_site_error_heading'] = "Ouch! Something went wrong:";
$lang['sites_updatePageData_no_site_error_message'] = "The site ID is missing or corrupt. Please try reloading the page and then try deleting the site once more.";
$lang['sites_updatePageData_success_heading'] = "All set!";
$lang['sites_updatePageData_success_message'] = "The page settings were successfully updated.";

$lang['sites_deltempl_delete_success'] = "The page template was successfully deleted.";

$lang['sites_rpreview_error'] = "Missing data, revision could not be loaded.";

$lang['sites_deleterevision_delete_error'] = "Some data is missing, we can not delete this revision right now. Please try again later.";
$lang['sites_deleterevision_delete_success'] = "The revision was removed successfully.";

$lang['sites_restorerevision_error'] = "Error!, data missing.";

$lang['sites_connect_path_error_heading'] = "Error:";
$lang['sites_connect_path_error_message'] = "The path you have provided is not correct or you might not have the required permissions to access this path.";

$lang['sites_connect_details_error_heading'] = "Error:";
$lang['sites_connect_details_error_message'] = "The connection details you provided are not correct. Please update the details and try again";

$lang['sites_test_success_heading'] = "All good!";
$lang['sites_test_success_message'] = "The provided FTP details are all good and can be used to publish this site.";
$lang['sites_test_error_details_heading'] = "Error:";
$lang['sites_test_error_details_message'] = "The connection details (server, user name, password and/or port) you provided are not correct. Please update the details and try again.";
$lang['sites_test_error_path_heading'] = "Error:";
$lang['sites_test_error_path_message'] = "The path you have provided is not correct or you might not have the required permissions to access this path.";

$lang['sites_updateoriginal_block_error'] = "Something went wrong; it appears the data is incorrect:<br>";
$lang['sites_updateoriginal_block_error2'] = "Could not save block source code; file does not exist or you do not have sufficient permissions to modify this file. Make sure this block is still present and that it's template file is writable.";
$lang['sites_updateoriginal_block_success'] = "This blocks original source code has been updated";

$lang['sites_cloneoriginal_block_error'] = "Something went wrong; it appears the data is incorrect:<br>";
$lang['sites_cloneoriginal_block_error2'] = "Could not clone block; the template file could not be created. Please make sure the folder is writable";
$lang['sites_cloneoriginal_block_success'] = "The block was cloned successfully in the selected category";

$lang['sites_deleteoriginal_block_error'] = "Something went wrong; it appears the data is incorrect:<br>";
$lang['sites_deleteoriginal_block_error2'] = "Original block could not be deleted; it appears this block no longer exists.";
$lang['sites_deleteoriginal_block_success'] = "Block was deleted successfully.";

$lang['sites_clone_confirm'] = "The site was cloned successfully";

$lang['sites_changeowner_error'] = "Site owner could not be changed; some data is missing. Please reload the site and try again.";
$lang['sites_changeowner_error2'] = "The supplied new owner appears invalid; ownership could not be changed.";
$lang['sites_changeowner_success'] = "The owner of the site was successfully changed.";
$lang['sites_load_sites'] = "Loading...";

// sites/views/create.php
$lang['mainside_templates'] = "templates";
$lang['mainside_blocks'] = "blocks";
$lang['mainside_components'] = "components";
$lang['mainside_pages'] = "pages";
$lang['mainside_popups'] = "Popups";

$lang['templates_heading'] = "Templates";
$lang['choose_template'] = "Choose Template";

$lang['elements_heading'] = "Blocks";
$lang['no_fav_blocks_yet'] = "You do not have any saved blocks yet...";
$lang['comopnents_heading'] = "Components";
$lang['pages'] = "Pages";
$lang['pages_counter_text'] = "Pages:";
$lang['popups_heading'] = "Popups";

$lang['button_add_page'] = "Add Page";
$lang['button_publish_page'] = "Publish";
$lang['show_grid'] = "Show grid:";
$lang['actionbuttons_sitesettings'] = "Site Settings";
$lang['actionbuttons_pagesettings'] = "Page Settings";
$lang['actionbuttons_preview'] = "Preview";
$lang['actionbuttons_save_nothing'] = "Nothing to save";
$lang['actionbuttons_save'] = "Save now (!)";
$lang['actionbuttons_loading'] = "Saving...";
$lang['actionbuttons_versions'] = "Older versions";
$lang['actionbuttons_versions_tooltip_preview'] = "Preview Revision";
$lang['actionbuttons_versions_tooltip_delete'] = "Delete Revision";
$lang['actionbuttons_versions_tooltip_restore'] = "Restore Revision";
$lang['actionbuttons_savesite'] = "Save entire site";
$lang['actionbuttons_savepagetemplate'] = "Save page template";
$lang['actionbuttons_export'] = "Export";
$lang['actionbuttons_publish'] = "Upload";
$lang['actionbuttons_ftp_not_ready'] = "You can not publish your site right now. Please update your FTP details.";

$lang['create_all_good'] = "All good!";

$lang['create_tab_placeholder_linktext'] = "Link text";
$lang['link_tab_link_newtab'] = "Open link in new tab";

$lang['create_tab_panel_page'] = "Choose a page";
$lang['create_tab_panel_block'] = "Choose a block";

$lang['create_tab_panel_or'] = "or";
$lang['create_tab_panel_down'] = "&xvee;";
$lang['create_tab_panel_placeholder_link'] = "http://somewhere.com/somepage";

$lang['link_tab_link_active'] = "Link is active";

$lang['form_tab_email_data_to'] = "Email data to:";
$lang['form_tab_placeholder_emailaddress'] = "Email address";
$lang['form_tab_custom_action'] = "Custom action:";
$lang['form_tab_api_integration'] = "API Integration:";
$lang['form_tab_placeholder_confirmation'] = "Custom confirmation message";
$lang['form_tab_placeholder_action'] = "Action";
$lang['form_tab_api_integration_with_html_code'] = "Paste web form code (HTML)";
$lang['form_tab_placeholder_html_code'] = "HTML code";

$lang['map_tab_zoomlevel'] = "Map zoom level";
$lang['map_tab_address'] = "Your address";
$lang['map_tab_infomessage'] = "Info box message";
$lang['map_tab_blackandwhite'] = "Black & White";

$lang['slideshow_tab_autoplay'] = "Auto play:";
$lang['slideshow_tab_pauseonhover'] = "Pause on hover:";
$lang['slideshow_tab_effect'] = "Effect:";
$lang['slideshow_tab_effect_slide'] = "Slide";
$lang['slideshow_tab_effect_fade'] = "Fade";
$lang['slideshow_tab_interval'] = "Interval (in ms):";
$lang['slideshow_tab_navarrows'] = "Nav arrows:";
$lang['slideshow_tab_navarrows_inside'] = "Inside";
$lang['slideshow_tab_navarrows_outside'] = "Outside";
$lang['slideshow_tab_navarrows_none'] = "Hidden";
$lang['slideshow_tab_navindicators'] = "Nav indicators:";

$lang['label_building_mode'] = "Mode";
$lang['label_building_mode_elements'] = "Blocks";
$lang['label_building_mode_content'] = "Content";
$lang['label_building_mode_details'] = "Details";

$lang['countdown_tab_datetime'] = "Date/time";
$lang['countdown_tab_landing'] = "Landing";
$lang['countdown_tab_days'] = "days";
$lang['countdown_tab_hours'] = "hours";
$lang['countdown_tab_minutes'] = "minutes";
$lang['countdown_tab_warning'] = "Countdown timers will <b>not</b> function inside the page builder; use the preview functionality to see the countdown timer in action.";
$lang['countdown_tab_deadline_placeholder'] = "YYYY-MM-DD HH:MM:SS";
$lang['countdown_tab_deadline_redirect_label'] = "Redirect when countdown reaches 0";
$lang['countdown_tab_checkbox_daily_label'] = "Daily countdown";
$lang['countdown_tab_daily_reset'] = "Resets daily";
$lang['countdown_tab_reset_load'] = "Resets at every visit";
$lang['countdown_tab_reset_none'] = "Countdown continues on next visit";
$lang['countdown_tab_deadline_redirect_placeholder'] = "https://google.com";

$lang['mode_tooltip_elements'] = "Allows you to add, remove and re-order blocks on the canvas. You can also view and edit the block's source HTML";
$lang['mode_tooltip_content'] = "Allows you to edit written content on your pages. Editable elements will display a red dashed outline when hovering the mouse cursor over it.";
$lang['mode_tooltip_styling'] = "Allows you edit certain style attributes, images, links and videos. Editable Elements will display a red dashed outline when hovering the cursor over it.";

$lang['canvas_empty'] = "Build your page by dragging blocks onto the canvas";

$lang['popup_type_dropdown_entry'] = "Entry popup";
$lang['popup_type_dropdown_exit'] = "Exit popup";
$lang['popup_type_dropdown_regular'] = "Regular popup";
$lang['popup_dz_exit_popups'] = "Drop an entry popup here";
$lang['popup_dz_entry_popups'] = "Drop an exit popup here";
$lang['popup_dz_regular_popups'] = "Drop a regular popup here";

$lang['popup_delay'] = "Delay";
$lang['popup_delay_seconds'] = "seconds";
$lang['popup_delay_minutes'] = "minutes";

$lang['popup_reocurrance_dropdown_once'] = "Show popup on first visit only";
$lang['popup_reocurrance_dropdown_all'] = "Show popup on every visit";

$lang['input_id_lable'] = "ID";
$lang['input_name_lable'] = "Name";
$lang['input_placeholder_lable'] = "Placeholder";
$lang['input_type_lable'] = "Type";
$lang['input_type_email'] = "Email";
$lang['input_type_number'] = "Number";
$lang['input_type_password'] = "Password";
$lang['input_type_tel'] = "Tel";
$lang['input_type_text'] = "Text";
$lang['input_type_url'] = "Url";

$lang['detail_editor_heading'] = "Detail Editor";
$lang['detail_editor_label_editing'] = "editing";
$lang['detail_editor_tab_style'] = "Style";
$lang['detail_editor_tab_link'] = "Link";
$lang['detail_editor_tab_image'] = "Image";
$lang['detail_editor_tab_icons'] = "Icons";
$lang['detail_editor_tab_video'] = "Video";
$lang['detail_editor_tab_brand'] = "Brand";
$lang['detail_editor_tab_form'] = "Form";
$lang['detail_editor_tab_slideshow'] = "Slider";
$lang['detail_editor_tab_map'] = "Map";

$lang['detail_editor_tab_countdown'] = "Countdown";
$lang['detail_editor_tab_input'] = "Input";
$lang['detail_editor_tab_textarea'] = "Textarea";

$lang['enter_youtube_id'] = "Youtube Video ID";
$lang['video_placeholder_youtubeid'] = "Enter a Youtube video ID";
$lang['enter_vimeo_id'] = "Vimeo Video ID";
$lang['video_placeholder_vimeoid'] = "Enter a Vimeo video ID";

$lang['choose_a_page'] = "Choose a page";
$lang['choose_a_block'] = "Choose a block (one page sites)";

$lang['enter_image_path'] = "Enter image path";
$lang['upload_image'] = "Upload image";
$lang['select_image'] = "Select image";
$lang['change'] = "Change";
$lang['remove'] = "Remove";
$lang['open_image_library'] = "Open image library";
$lang['label_image_gallery'] = "Image gallery";
$lang['label_image_title'] = "Title attribute";
$lang['label_image_alt'] = "Alt attribute";
$lang['label_toggle_advanced'] = "Toggle advanced options";

$lang['choose_an_icon'] = "Choose an icon below";

$lang['the_changes_were_applied'] = "The changes were applied successfully!";

$lang['sidebuttons_apply_changes'] = "Apply changes";
$lang['sidebuttons_apply_clone'] = "Clone";
$lang['sidebuttons_apply_reset'] = "Reset";
$lang['sidebuttons_apply_remove'] = "Remove";

$lang['modalexport_export_your_markup'] = "Export your markup";
$lang['modalexport_doctype'] = "Doc type";
$lang['modalexport_placeholder_doctype'] = "Doc type";
$lang['modalexport_export_now'] = "Export Now";

$lang['modaldeleteblock_areyousure'] = "Are you sure you want to delete this block?";

$lang['modaldeleterevision_areyousure'] = "Are you sure you want to delete this revision?";

$lang['modalpublish_publishyoursite'] = "Upload your site";

$lang['modalpublish_success_heading'] = "Hooray!";
$lang['modalpublish_success_message'] = "Uploading has finished and all your selected pages and/or assets were successfully uploaded.";

$lang['modalpublish_error_heading'] = "Ouch! Something went wrong:";
$lang['modalpublish_error_message'] = "You already reached your user limit. Sorry for the inconvenience.";

$lang['modalpublish_pendingchanges_heading'] = "You have pending changes";
$lang['modalpublish_pendingchanges_message'] = "It appears the latest changes to this site have not been saved yet. Before you can publish this site, you will need to save the last changes.";
$lang['modalpublish_pendingchanges_button_savechanges'] = "Save Changes";

$lang['modalpublish_siteassets'] = "Site assets";
$lang['modalpublish_asset'] = "Asset";

$lang['modalpublish_publishing'] = "Publishing...";
$lang['modalpublish_published'] = "Published";

$lang['modalpublish_sitepages'] = "Site pages";
$lang['modalpublish_page'] = "Page";

$lang['modalpublish_publish_now'] = "Publish Now";

$lang['modalresetblock_areyousure'] = "Are you sure you want to reset this block?";
$lang['modalresetblock_message'] = "All changes made to the content will be destroyed.";
$lang['modalresetblock_button_reset'] = "Reset Block";

$lang['modaldeletepage_areyousure'] = "Are you sure you want to delete this entire page?";
$lang['modaldeletepage_button_deletepage'] = "Delete Page";

$lang['modaldeleteelement_areyousure'] = "Are you sure you want to delete this element? Once deleted, it can not be restored.";
$lang['modaldeleteelement_button_deleteelement'] = "Delete";

$lang['sites_loading'] = "Loading Builder...";

$lang['modal_pendingchanges_areyousure'] = "You've got pending changes, if you leave this page your changes will be lost. Are you sure?";
$lang['modal_pendingchanges_button_stay'] = "Stay on this page!";
$lang['modal_pendingchanges_button_leave'] = "Leave the page";

$lang['modal_editcontent_updatecontent'] = "Update Content";

$lang['modal_preview_heading'] = "Site Preview";
$lang['modal_preview_message'] = "Please note that the preview will always show your latest changes, even if these have not been saved yet. <br><b>Links do not work in the preview; you can only preview the page you are currently working on.</b>";
$lang['modal_preview_button'] = "Open Preview";
$lang['modal_preview_button2'] = "Preview Changes";

$lang['modal_deltemplate_heading'] = "Remove page template?";
$lang['modal_deltemplate_message'] = "Are you sure you want to permanently delete this page template? Deleting this template <b>will not affect</b> any existing sites.";
$lang['modal_deltemplate_button'] = "Yes, delete template";

$lang['modal_imagelibrary_heading'] = "Image Library";
$lang['modal_imagelibrary_loadertext'] = "Uploading image...";
$lang['modal_imagelibrary_tab_myimages'] = 'My Images (<span>%s</span>MB of %sMB)';
$lang['modal_imagelibrary_tab_otherimages'] = "Other Images";
$lang['modal_imagelibrary_tab_search'] = "Free Stock Photos";
$lang['modal_imagelibrary_message_noimagesearchresults'] = "No images found";
$lang['modal_imagelibrary_message_nofurtherimagesearchresults'] = "No other images found";
$lang['modal_imagelibrary_message_noimages'] = "You currently have no images uploaded. To upload images, please use the upload panel on your left.";
$lang['modal_imagelibrary_button_selectimage'] = "Select Image";
$lang['modal_imagelibrary_button_change'] = "Change";
$lang['modal_imagelibrary_button_remove'] = "Remove";
$lang['modal_imagelibrary_button_upload'] = "Upload Image";
$lang['modal_imagelibrary_ribbon_admin'] = "Admin";
$lang['modal_imagelibrary_button_insert'] = "Use and Insert Image";
$lang['modal_imagelibrary_button_insertimage'] = "Insert Image";
$lang['modal_imagelibrary_restrictions_heading'] = "Image upload restrictions";
$lang['modal_imagelibrary_restrictions_message'] = "Please observer the following restrictions when uploading images:";
$lang['modal_imagelibrary_restrictions_filesize'] = "File size:";
$lang['modal_imagelibrary_restrictions_dimensions'] = "Dimensions:";
$lang['modal_imagelibrary_restrictions_filetypes'] = "File types:";
$lang['modal_imagelibrary_load_more'] = "Load more";

$lang['modal_pagesettings_header'] = "Page Settings for";
$lang['modal_pagesettings_loadertext'] = "Saving page settings...";

$lang['sites_framecover_button_clone'] = "Clone block in :";
$lang['sites_framecover_title_delete'] = "Delete this block from the canvas";
$lang['sites_framecover_title_reset'] = "Reset this block to its original state";
$lang['sites_framecover_title_source'] = "Edit this blocks source code";
$lang['sites_framecover_title_favourite'] = "Save this block as a template";
$lang['sites_framecover_title_favourite_saving'] = "Saving...";
$lang['sites_framecover_title_favourite_confirmed'] = "Saved!";
$lang['sites_framecover_title_drag'] = "Drag and drop this block";
$lang['sites_framecover_global'] = "Global";
$lang['sites_framecover_title_admin_delete'] = "Delete the original block.";
$lang['sites_framecover_title_admin_delete_working'] = "Deleting...";
$lang['sites_framecover_title_admin_delete_confirmed'] = "Deleted!";
$lang['sites_framecover_title_admin_save'] = "Save the current state of this block to the original.";
$lang['sites_framecover_title_admin_save_saving'] = "Saving...";
$lang['sites_framecover_title_admin_save_confirmed'] = "Saved!";

$lang['sites_parallax_info'] = "<b>Please note</b> the parallax functionality does not work inside the page builder; use the preview to see the parallax in action.";

$lang['sites_sourcecode_editor_button_save'] = "Save";
$lang['sites_sourcecode_editor_button_cancel'] = "Cancel";

// sites/views/sites.php
$lang['sites_page_title'] = "Sites | Site Builder Pro";
$lang['sites_header'] = "Sites";
$lang['sites_createnewsite'] = "Create new site";
$lang['sites_filterbyuser'] = "Filter by user";
$lang['sites_filterbyuserall'] = "All";

$lang['sites_sortby'] = "Sort by";
$lang['sites_sortby_creationdate'] = "Creation date";
$lang['sites_sortby_lastupdated'] = "Last updated";
$lang['sites_sortby_numberofpages'] = "Number of pages";
$lang['sites_search_placeholder'] = "Search for site name";

$lang['sites_details_owner'] = "Owner";
$lang['sites_details_pages'] = "Page(s)";
$lang['sites_details_createdon'] = "Created on";
$lang['sites_details_lasteditedon'] = "Last edited on";

$lang['sites_details_tooltip_edit'] = "Edit this site";
$lang['sites_details_tooltip_settings'] = "Edit the settings for this site";
$lang['sites_details_tooltip_clone'] = "Make a copy of this site";
$lang['sites_details_tooltip_delete'] = "Delete this site";

$lang['sites_published_on'] = "Site was published on ";

$lang['sites_empty_placeholder'] = "No preview available";

$lang['sites_sitehasnotbeenpublished'] = "Site has not been published";
$lang['sites_button_publishnow'] = "Publish Now";

$lang['sites_button_editthissite'] = "Edit This Site";
$lang['sites_button_settings'] = "Settings";
$lang['sites_button_clone'] = "Clone";
$lang['sites_button_delete'] = "Delete";

$lang['sites_deletesite_loadertext'] = 'Deleting site...';
$lang['sites_deletesite_areyousure'] = "Are you sure you want to delete this site?";
$lang['sites_deletesite_button_deleteforever'] = "Delete Forever";

$lang['sites_nosites_heading'] = "Well, hello there!";
$lang['sites_nosites_message'] = "It appears you might be new around these parts. At the moment, you don't have any sites to call your own just yet, so why not get started and build yourself one awesome little website?";
$lang['sites_nosites_button_confirm'] = "Yes, I want to build a website!";
$lang['sites_nosites_button_cancel'] = "Nah, maybe later";

$lang['sites_modal_newsite_header'] = "How would you like to start?";
$lang['sites_modal_newsite_launch'] = "Launch the builder";
$lang['sites_modal_newsite_empty'] = "Empty canvas";
$lang['sites_modal_newsite_scratch_heading'] = "Start from scratch";
$lang['sites_modal_newsite_scratch_heading2'] = "Use drag and drop to create your own design";

// sites/models/Sites_model.php
$lang['sites_model_mynewsite'] = "My new site";

/**** Sites Module End ****/

/**** Templates Module Start ****/

// templates/controllers/Templates.php
$lang['templates_templatedata_error1'] = "The template ID is missing or invalid.";
$lang['templates_templatedata_error2'] = "Requested template could not be found: ";
$lang['templates_templatedata_success'] = "Template data loaded.";

// templates/views/templates.php
$lang['templates_index_title'] = "Templates";
$lang['templates_header'] = "Templates";
$lang['templates_createnewsite'] = "Create template";
$lang['templates_search_placeholder'] = "Search for template name";
$lang['templates_nosites_heading'] = "Well, hello there!";
$lang['templates_nosites_message'] = "At the moment, you don't have any templates just yet. Would you like to create a new template?";
$lang['templates_nosites_button_confirm'] = "Yes, I want to build a template";
$lang['templates_nosites_button_cancel'] = "No thanks";
$lang['templates_details_tooltip_edit'] = "Edit this template";
$lang['templates_details_tooltip_delete'] = "Delete this template";
$lang['templates_label_choosecat'] = "Select a category";
$lang['templates_delete_confirmation_header'] = "Are you sure?";
$lang['templates_delete_confirmation_content'] = "Deleting this template can not be undone.";
$lang['templates_delete_confirmation_yes'] = "Yes";
$lang['templates_delete_confirmation_cancel'] = "No";
$lang['templates_showblocksincat'] = "Choose category";
$lang['templates_managecats'] = "Manage template categories";
$lang['templates_modal_categories_title'] = "Manage template categories";
$lang['templates_modal_categories_newcat_placeholder'] = "New category";
$lang['templates_modal_categories_newcat_adding'] = "Adding...";
$lang['templates_modal_categories_newcat_add'] = "Add";
$lang['templates_newcat_error1'] = "Could not create the category; invalid category name.";
$lang['templates_newcat_error2'] = "Could not create the category; please make sure the new category name is unique";
$lang['templates_newcat_success'] = "The new category has been created successfully.";
$lang['templates_deletecat_success'] = "The category was deleted successfully.";
$lang['templates_updateCategory_success'] = "The category was updated successfully.";
$lang['templates_updateCategory_error1'] = "The category could not be updated; critical data is missing. Reload the page and try again.";

// templates/views/modal_deletecategory.php
$lang['modal_deletetemplatecategory_heading'] = "Are you sure?";
$lang['modal_deletetemplatecategory_text'] = "Would you like to move templates currently in this category to another category? If so, please choose a category using the drop-down below.";
$lang['modal_deletetemplatecategory_button_remove'] = "Remove";
$lang['modal_deletetemplatecategory_button_cancel'] = "Cancel";
$lang['templates_setcategory_error1'] = "The category could not be saved due to missing or incorrect data. Please reload the page and try again.";
$lang['templates_setcategory_success'] = "The category for this template was updated.";
$lang['modal_deletetemplatecategory_nocat'] = "No particular category";

/**** Templates Module End ****/

/**** User Module Start ****/

// user/controllers/User.php
$lang['user_create_limit_error_heading'] = "Ouch! Something went wrong:";
$lang['user_create_limit_error_message'] = "You already reached your user limit. Sorry for the inconvenience.";
$lang['user_create_validation_error_heading'] = "Ouch! Something went wrong:";
$lang['user_create_validation_error_message'] = "Something went wrong when trying to cerate the new account, please see the errors below:<br><br>";
$lang['user_create_no_user_error_heading'] = "Ouch! Something went wrong:";
$lang['user_create_no_user_error_message'] = "The email address you're trying to use is already used by different account. Please choose a different email address";
$lang['user_create_stripe_base_error_heading'] = "Ouch! Something went wrong:";
$lang['user_create_stripe_base_error_message'] = "Something went wrong when trying to cerate the new account, please see the errors below:<br><br>";
$lang['user_create_stripe_exception_error_heading'] = "Ouch! Something went wrong:";
$lang['user_create_stripe_exception_error_message'] = "Something went wrong when trying to cerate the new account, please see the errors below:<br><br>";
$lang['user_create_success_heading'] = "Hooray!";
$lang['user_create_success_message'] = "The new account was created successfully!";

$lang['user_update_validation_error_heading'] = "Ouch! Something went wrong:";
$lang['user_update_validation_error_message'] = "Something went wrong when trying to cerate the new account, please see the errors below:<br><br>";
$lang['user_update_not_allowed_email_error_heading'] = "Ouch! Something went wrong:";
$lang['user_update_not_allowed_email_error_message'] = "The email address you're trying to use is already used by different account. Please choose a different email address";
$lang['user_update_cancel_stripe_base_error_heading'] = "Ouch! Something went wrong:";
$lang['user_update_cancel_stripe_base_error_message'] = "Something went wrong when trying to cancel the subscription, please see the errors below:<br><br>";
$lang['user_update_cancel_stripe_exception_error_heading'] = "Ouch! Something went wrong:";
$lang['user_update_cancel_stripe_exception_error_message'] = "Something went wrong when trying to cancel the subscription, please see the errors below:<br><br>";
$lang['user_update_empty_stripe_key_heading'] = "Stripe keys are missing";
$lang['user_update_empty_stripe_key_message'] = "Your Stripe <b>Secret key</b> and <b>Publishable key</b> have not yet been provided. Please enter these keys through Settings > Payment Settings. Once entered, you can start update to paid packages. <br><br>";
$lang['user_update_cancel_paypal_error_heading'] = "Ouch! Something went wrong:";
$lang['user_update_cancel_paypal_error_message'] = "Something went wrong when trying to cancel the subscription, please see the errors below:<br><br>";

$lang['user_update_success_heading'] = "Hooray!";
$lang['user_update_success_message'] = "The account was updated successfully!";

$lang['user_send_password_reset_message1'] = "<strong>Admin send a password reset link.</strong><br>";
$lang['user_send_password_reset_message2'] = "<strong>Please click here:</strong> ";

$lang['user_send_password_reset_success_heading'] = "Hooray!";
$lang['user_send_password_reset_success_message'] = "Password Reset Email send successfully";

$lang['user_send_password_reset_error_heading'] = "Ouch! Something went wrong:";
$lang['user_send_password_reset_error_message'] = "Something went wrong when trying to send Password Reset Email, Please try again later";

$lang['user_details_update_validation_error_heading'] = "Ouch! Something went wrong:";
$lang['user_details_update_validation_error_message'] = "Something went wrong when trying to update your details, please see the errors below:<br><br>";
$lang['user_details_update_success_heading'] = "Hooray!";
$lang['user_details_update_success_message'] = "Your account details were updated successfully.";
$lang['user_details_update_error_heading'] = "Ouch! Something went wrong:";
$lang['user_details_update_error_message'] = "We weren't able to save your details just now. Please reload the page and try again.";

$lang['user_login_update_validation_error_heading'] = "Ouch! Something went wrong:";
$lang['user_login_update_validation_error_message'] = "Something went wrong when trying to update your details, please see the errors below:<br><br>";
$lang['user_login_update_success_heading'] = "Hooray!";
$lang['user_login_update_success_message'] = "Your account details were updated successfully.";
$lang['user_login_update_error_heading'] = "Ouch! Something went wrong:";
$lang['user_login_update_error_message'] = "We weren't able to save your details just now. Please reload the page and try again.";

$lang['user_toggle_status_user_limit_error'] = "You have reached your user limit. Please remove some user accounts before activating this user.";
$lang['user_toggle_status_inactive'] = "The user account has been deactivated; this user can not login and create web sites.";
$lang['user_toggle_status_active'] = "The user account has been activated; this user can now login and create web sites.";

$lang['user_delete_success'] = "The user account has been deleted, this user can no longer login and create web sites.";
$lang['user_delete_error'] = "Something went wrong, please try again later.";

$lang['user_package_update_success_heading'] = "Hooray!";
$lang['user_package_update_success_message'] = "Your subscription has been changed. You will soon be redirected to the payment page.";
$lang['user_package_update_free_free_success_heading'] = "Hooray!";
$lang['user_package_update_free_free_success_message'] = "Your subscription has been changed. You are now subscribed to a different free subscription.";
$lang['user_package_update_free_paid_success_heading'] = "Hooray!";
$lang['user_package_update_free_paid_success_message'] = "Your subscription has been changed. You will soon be redirected to the payment page.";
$lang['user_package_update_paid_free_success_heading'] = "Hooray!";
$lang['user_package_update_paid_free_success_message'] = "Your subscription has been changed. You are now subscribed to a free subscription.";
$lang['user_package_update_paid_paid_success_heading'] = "Hooray!";
$lang['user_package_update_paid_paid_success_message'] = "Your subscription has been changed. You will soon be redirected to payment page.";

$lang['user_package_update_stripe_base_error_heading'] = "Ouch! Something went wrong:";
$lang['user_package_update_stripe_base_error_message'] = "Something went wrong when trying to update the package, please see the errors below:<br><br>";
$lang['user_package_update_stripe_exception_error_heading'] = "Ouch! Something went wrong:";
$lang['user_package_update_stripe_exception_error_message'] = "Something went wrong when trying to update the package, please see the errors below:<br><br>";
$lang['user_package_update_error_heading'] = "Ouch! Something went wrong:";
$lang['user_package_update_error_message'] = "Subscription change failed. Please try again later.";
$lang['user_package_update_no_change_error_heading'] = "Ouch! Something went wrong:";
$lang['user_package_update_no_change_error_message'] = "Please choose a different subscription.";

$lang['user_payment_stripe_payment_success'] = "Payment successful. <br>Your new subscription is activated.";
$lang['user_payment_stripe_payment_base_error'] = "Payment failed. <br>Please check your payment details and submit again.<br>";
$lang['user_payment_stripe_payment_exception_error'] = "Payment failed. <br>Please check your payment details and submit again.<br>";

$lang['stripe_token_base_error'] = "Could not create security token. <br>There might be an error while creating a stripe token using the API. Please try again later.<br>";
$lang['stripe_token_exception_error'] = "Could not create security token. <br>There might be an error while creating  a stripe token using the API. Please try again later.<br>";

$lang['user_payment_paypal_getting_token_error'] = 'Ouch! Something went wrong while attempting to access Paypal. Please try again!';

$lang['user_payment_paypal_payment_success'] = "Payment successful. <br>Your new package is activated.";
$lang['user_payment_paypal_subscription_error'] = "Payment successful, but the subscription could not be created. Please contact the site owner to create the subscription.";

$lang['user_package_cancel_success_heading'] = "Hooray!";
$lang['user_package_cancel_success_message'] = "Your subscription has been canceled.";
$lang['user_package_cancel_stripe_base_error_heading'] = "Ouch! Something went wrong:";
$lang['user_package_cancel_stripe_base_error_message'] = "Something went wrong when trying to cancel the subscription, please see the errors below:<br><br>";
$lang['user_package_cancel_stripe_exception_error_heading'] = "Ouch! Something went wrong:";
$lang['user_package_cancel_stripe_exception_error_message'] = "Something went wrong when trying to cancel the subscription, please see the errors below:<br><br>";
$lang['user_package_cancel_error_heading'] = "Ouch! Something went wrong:";
$lang['user_package_cancel_error_message'] = "We were not able to cancel this subscription. Please try again later.";

// user/views/users.php
$lang['users_heading'] = "Users";

$lang['users_button_addnew'] = "Add New User";

$lang['users_tab_account'] = "Account";
$lang['users_tab_sites'] = "Sites";

$lang['users_nosites'] = "This user has not created any sites yet.";

$lang['users_button_edit'] = "Edit";
$lang['users_button_settings'] = "Settings";
$lang['users_button_delete'] = "Delete";

$lang['users_emailfield_placeholder'] = "Email address";
$lang['users_emailfield_password'] = "Password";
$lang['users_field_atoken'] = "Auto-login token";
$lang['users_field_atoken_regenerate'] = "Generate";
$lang['users_adminpermissions'] = "Admin permissions";
$lang['users_choose_a_packge'] = "Choose a package";
$lang['users_button_udpate'] = "Update Details";

$lang['users_button_sendpasswordreset'] = "Send Password Reset Email";
$lang['users_button_deleteaccount'] = "Delete Account";
$lang['users_button_disableaccount'] = "Disable";
$lang['users_button_enableaccount'] = "Enable";

$lang['users_modal_deleteuser_message'] = "Deleting this user account will result in all associated data being deleted (with the exception of externally uploaded sites) and <b>can not be undone</b>. Are you sure you want to continue?";

$lang['users_modal_newuser_heading'] = "Create a new user account";
$lang['users_modal_newuser_loadertext'] = "Creating new account...";
$lang['users_modal_newuser_firstname'] = "First name";
$lang['users_modal_newuser_lastname'] = "Last name";
$lang['users_modal_newuser_email'] = "Email";
$lang['users_modal_newuser_password'] = "Password";
$lang['users_modal_newuser_package'] = "Package";
$lang['users_modal_newuser_adminpermissions'] = "Admin permissions";
$lang['users_modal_newuser_sendnotification'] = "Send notification";
$lang['users_modal_newuser_button_create'] = "Create account";

// user/views/payment_stripe.php
$lang['users_payment_stripe_heading'] = "Pay with Stripe";
$lang['users_payment_stripe_message'] = "If there is any leftover from your previous subscription, we will refund that amount.";

// user/views/payment_paypal.php
$lang['users_payment_paypal_heading'] = "Pay with PayPal";
$lang['users_payment_paypal_message'] = "If there is any leftover from your previous subscription, we will refund that amount.";

// user/views/email/activation.tpl.php
$lang['email_activation_heading'] = 'Dear %s';
$lang['email_activation_sub_heading'] = 'Your account has been created, please pay subscription charge. %s';
$lang['email_activation_link'] = 'Click here to make a payment';

// user/views/email/login.tpl.php
$lang['email_login_heading'] = 'Dear %s';
$lang['email_login_sub_heading'] = 'Your account has been created. %s';
$lang['email_login_link'] = 'Click here to login';

// user/views/email/update.tpl.php
$lang['email_update_heading'] = 'Dear %s';
$lang['email_update_sub_heading'] = 'Your account has been updated to a paid package, please pay the subscription charge. %s';
$lang['email_update_link'] = 'Click here for payment';

/**** User Module End ****/



/**** Sent Module Start ****/

// sent/controllers/Sent.php
$lang['no_email_error_header_error'] = 'Ouch!';
$lang['no_email_error_content'] = "<small>We don't know where to send this data to; it appears the email or email ID is missing.";

$lang['error_button_go_back'] = "Go back";

$lang['empty_fields_error_header'] = 'Ouch!';
$lang['empty_fields_error_content'] = "<small>You can not submit an empty form. Please go back to the previous page and enter some values.</small>";

$lang['honey_error_header'] = 'Ouch!';
$lang['honey_error_content'] = "<small>Our API has identified the submitted data as being entered by a bot and can therefor not process the data.</small>";

$lang['validation_error_header'] = 'Ouch!';
$lang['validation_error_content'] = "<small>Something is not right with the data you've submitted, please see the details below:</small><br>";

$lang['file_error_header'] = 'Ouch!';
$lang['file_error_content'] = "<small>Something went wrong with the file you are trying to send. Please see the error(s) below:</small><br>";

$lang['after_error_header'] = 'Ouch!';
$lang['after_error_content'] = "<small>It seems  an invalid redirection URL has been specified. We can't not redirect you to</small><br>";

$lang['success_header'] = 'Success!';
$lang['success_content'] = "<small>Your message has been received and we will back to you as soon as possible.</small>";

$lang['success_integration_header'] = 'Success!';
$lang['success_integration_content'] = "<small>Email has been successfully added to the list.</small>";

$lang['failure_integration_header'] = 'Ouch!';
$lang['failure_integration_content'] = "<small>Email has been failed to add to the list.</small>";
$lang['email_not_exist'] = "Email address should not be blank.";
$lang['first_name_not_exist'] = "First Name should not be blank.";

$lang['error_header'] = 'Ouch!';
$lang['error_content'] = "<small>We can't submit the form data. Please return to the original form and resubmit the data from there.</small>";

/**** Sent Module End ****/



/**** Autoupdate Module Start ****/

// autoupdate/controllers/Autoupdate.php
$lang['autoupdate_index_php_module_error_heading'] = "The application is unable to update itself.";
$lang['autoupdate_index_php_module_error_content'] = '<p>There is an update available but certain needed php module(s) is/are not loaded. Without these modules, auto update is not possible. Please configure your server properly.</p>';
$lang['autoupdate_index_php_zip_error_content'] = '<p>PHP Zip module is not loaded.</p>';
$lang['autoupdate_index_php_curl_error_content'] = '<p>PHP cURL module is not loaded.</p>';

$lang['autoupdate_index_current_version_error_heading'] = "The application is unable to update itself.";
$lang['autoupdate_index_current_version_error_content'] = '<p>There is an update available but your Bloxby installation has not yet been updated to the previous version %s. Please make sure to update it version %s manually first.</p>';

$lang['autoupdate_index_error_heading'] = "The application is unable to update itself!";
$lang['autoupdate_index_error_content'] = '<p>There is an update available but your server might not properly configured. It seems your files and folders are owned by a different user than the web-server (apache/www-data) user.</p>';

$lang['autoupdate_index_invalid_error_heading'] = "The application is unable to update itself.";
$lang['autoupdate_index_invalid_error_content'] = "<p>There is an update available but your license key is invalid or no license key has been provided (you can enter your license key in the settings panel). Please re-check your license key and if you find it's correct then contact  support@bloxby.com</p>";

$lang['autoupdate_index_alert_heading'] = "There is an update available";
$lang['autoupdate_index_alert_content'] = '<p>There is an update available for your copy of Bloxby. This shouldn\'t take more than couple of minutes. If you would like to implement the update, we recommend making a backup of your files and database first.<br><br><div class="text-center"><a href="%s" class="btn btn-primary btn-block">Click here to update Bloxby</a></div></p>';

$lang['autoupdate_update_error_json_uri'] = '<p>Unable to get the update URI from server, please try again later. <a href="%s">Click here to proceed to the dashboard</a></p>';
$lang['autoupdate_update_header'] = 'Update Confirmation';
$lang['autoupdate_update_success_content'] = '<p>Update complete, please <a href="%s">click here to continue</a>.</p>';
$lang['autoupdate_update_error_content'] = '<p>The update could not be completed due to a file permission error. <a href="%s">Click here to proceed to the dashboard</a></p>';
$lang['autoupdate_update_no_update_content'] = '<p>Nothing to update, please <a href="%s">click here to continue</a>.</p>';

// autoupdate/views/error.php
$lang['autoupdate_site_link'] = '<a href="%s" style="font-size: 13; color: #337ab7">Click here to proceed to the dashboard (you can still update later at any time).</a>';

/**** Autoupdate Module End ****/



/**** Paypal SUbscription Module Start ****/

// subscription/views/subscription.php
$lang['pay_now_txt'] = 'Pay Now';

$lang['auth_payment_paypal_payment_success'] = "Payment successful. <br>Please login to your account. Be sure to check your email for a payment confirmation.";
$lang['auth_payment_paypal_payment_error'] = "Something went wrong when attempting payment. <br>Please try again.<br>";
$lang['auth_payment_paypal_subscription_error'] = "Payment successful, but the subscription could not be created. Please contact site owner to create the subscription for your package. <br>Please login to your account. <br>(Be sure to check your email for a payment confirmation.)";

$lang['paypal_payment_no_subscription'] = "Note: Your subscription has not been created yet. Please contact site owner to create it.";

$lang['payment_fail_try_again'] = 'Unfortunately your payment is not completed. Please try again!';
$lang['paypal_getting_token_error'] = 'Ouch! Something went wrong while accessing paypal. Please try again!';

/* User/controllers/user.php*/

$lang['package_changed_from'] = 'Your package changed from';
$lang['package_changed_to'] = 'to';
$lang['package_changed_sucsess'] = 'Your account is updated to the paid package, please pay the subscription charge.';
$lang['click_below_to_pay'] = 'Click here to pay.';
$lang['refund_reason'] = 'This refund is issued due to a subscription change';

$lang['refund_failed_subject'] = 'Automatic refund failed';
$lang['refund_failed_message'] = 'A refund process failed when changing subscriptions. Details are as follows';
$lang['refund_failed_user_id'] = 'User ID';
$lang['refund_failed_name'] = 'User name';
$lang['refund_failed_trnsctn'] = 'Transaction ID';
$lang['refund_failed_amount'] = 'Refund amount';

/**** Paypal SUbscription Module end ****/



/**** Builder_elements Module Start ****/

// builder_elements/controllers/Builder_elements.php

$lang['builder_elements_blocks_pagetitle'] = "Manage Page Builder Blocks";
$lang['builder_elements_components_pagetitle'] = "Manage Page Builder Components";
$lang['builder_elements_addblock_validation_error_heading'] = "Something went wrong :(";
$lang['builder_elements_addblock_validation_error_message'] = "There was a problem with the submitted data. Please see the details below:<br>";
$lang['builder_elements_addblock_url_error_message'] = "The template URL you're trying to use does not seem to be a valid URL. Please correct the template URL before re-submitting the form.";
$lang['builder_elements_updateblock_validation_error_heading'] = "Something went wrong :(";
$lang['builder_elements_updateblock_validation_error_message'] = "There was a problem with the submitted data. Please see the details below:<br>";
$lang['builder_elements_validationurl_error_message'] = "The provided template URL does not seem to load properly. Please proceed with caution";
$lang['builder_elements_upload_error_heading'] = "Something went wrong :(";
$lang['builder_elements_upload_error_message'] = "There are some issues with the file you're trying to upload, see the details below:<br>";
$lang['builder_elements_validation_complete_heading'] = "Block updated";
$lang['builder_elements_validation_complete_message'] = "The block data has been updated successfully";
$lang['builder_elements_deleteblock_error_message'] = "Something went wrong when deleting the block. Please try again.";
$lang['builder_elements_addblock_complete_heading'] = "Block added";
$lang['builder_elements_addblock_complete_message'] = "The new block has been added.";
$lang['builder_elements_updatecomponent_validation_error_heading'] = "Something went wrong :(";
$lang['builder_elements_updatecomponent_validation_error_message'] = "There was a problem with the submitted data. Please see the details below:<br>";
$lang['builder_elements_deletecomponent_error_message'] = "Something went wrong when deleting the component. Please try again.";
$lang['builder_elements_addcomponent_validation_error_heading'] = "Something went wrong :(";
$lang['builder_elements_addcomponent_validation_error_message'] = "There was a problem with the submitted data. Please see the details below:<br>";
$lang['builder_elements_addcomponent_complete_heading'] = "Component added";
$lang['builder_elements_addcomponent_complete_message'] = "The new component has been added.";
$lang['builder_elements_upload_success_content'] = "Your file was uploaded successfully.";
$lang['builder_elements_del_file_data_missing'] = "Error - couldn't delete file as none was specified";
$lang['builder_elements_del_file_no_such_file'] = "Error - the file couldn't not be deleted as it does not exist on the server";
$lang['builder_elements_del_file_no_misc_error'] = "Error - the file couldn't be deleted. Please try again.";
$lang['builder_elements_add_page_error'] = "You have exceeded the maximum number of pages the maximum number of pages for this site";
$lang['builder_elements_edit_sourcecode_error'] = "You can't edit the source code";
$lang['builder_elements_del_file_success'] = "The file was deleted successfully.";
$lang['builder_elements_del_folder_success'] = "The folder was deleted successfully.";
$lang['builder_elements_del_folder_misc_error'] = "Folder not deleted. Please try again.";
$lang['builder_elements_add_folder_error'] = "File or folder could not be created. Please see the errors below: <br>";
$lang['builder_elements_add_folder_error2'] = "The file or folder could not be created since it already exists. Please enter a different folder name.";
$lang['builder_elements_add_folder_success'] = "The file or folder was created successfully.";
$lang['builder_elements_add_folder_error3'] = "The file or folder could not be created. Please try again.";

// builder_elements/views/blocks.php

$lang['builder_elements_blocks_heading'] = "Manage blocks";
$lang['builder_elements_managecats'] = "Manage block categories";
$lang['builder_elements_button_addnew'] = "Add new block";
$lang['builder_elements_showblocksincat'] = "Choose category";
$lang['builder_elements_block_url'] = "URL to HTML file";
$lang['builder_elements_block_edit'] = "Edit block markup";
$lang['builder_elements_button_save'] = "Save";
$lang['builder_elements_button_save_working'] = "Saving...";
$lang['builder_elements_button_cancel'] = "Cancel";
$lang['builder_elements_newcat_error_heading'] = "Ouch!";
$lang['builder_elements_newcat_error_content'] = "It appears some trivial data is missing. Please try again.";
$lang['builder_elements_newcat_error_content2'] = "Please provide a unique category name";
$lang['blocks_modal_categories_title'] = "Manage block categories";
$lang['blocks_modal_categories_newcat_placeholder'] = "New category";
$lang['blocks_modal_categories_newcat_add'] = "Add";
$lang['blocks_modal_categories_newcat_adding'] = "Adding...";
$lang['blocks_modal_block_title'] = "Edit block";
$lang['blocks_modal_block_addtitle'] = "Add block";
$lang['blocks_modal_block_cancel'] = "Cancel";
$lang['blocks_modal_block_save'] = "Save changes";
$lang['blocks_modal_block_saving'] = "Saving...";
$lang['blocks_modal_block_calc_height'] = "Calculating block height...";
$lang['blocks_modal_block_delete'] = "Delete block";
$lang['blocks_modal_block_delete_confirm'] = "Can not be undone. Confirm?";
$lang['blocks_modal_block_delete_confirm_yes'] = "Yes";
$lang['blocks_modal_block_delete_confirm_no'] = "No";
$lang['blocks_modal_addblock_create'] = "Create block";
$lang['blocks_modal_addblock_cancel'] = "Cancel";
$lang['blocks_modal_addblock_saving'] = "Creating block...";
$lang['builder_elements_newccat_error_heading'] = "Ouch!";
$lang['builder_elements_newccat_error_content'] = "It appears some trivial data is missing. Please try again.";
$lang['builder_elements_newccat_error_content2'] = "Please provide a unique category name";

// builder_elements/views/browser.php
$lang['builder_elements_browser_heading'] = "Block files browser";
$lang['builder_elements_browser_placeholder_search'] = "Search for a file...";
$lang['builder_elements_browser_label_browsing'] = "Browsing";
$lang['builder_elements_browser_placeholder_choosefile'] = "Choose a file to upload...";
$lang['builder_elements_browser_button_selectfile'] = "Select a file";
$lang['builder_elements_browser_button_change'] = "Change";
$lang['builder_elements_browser_button_remove'] = "Remove";
$lang['builder_elements_browser_button_upload'] = "Upload";
$lang['builder_elements_browser_empty'] = "No files found.";
$lang['builder_elements_browser_placeholder_addfolder'] = "Create file or folder";
$lang['builder_elements_browser_button'] = "Create";
$lang['builder_elements_browser_warning_invalidcharacter'] = "Please make sure the folder name contains only alphanumeric characters.";

// builder_elements/views/components.php

$lang['builder_elements_components_heading'] = "Manage components";
$lang['builder_elements_managecomponentcats'] = "Manage component categories";
$lang['builder_elements_button_addnewcomponent'] = "Add new component";
$lang['components_modal_categories_title'] = "Manage component categories";
$lang['components_modal_componen_title'] = "Edit component";
$lang['components_modal_componen_delete'] = "Delete component";
$lang['components_modal_componen_delete_confirm'] = "Can not be undone. Confirm?";
$lang['components_modal_componen_delete_confirm_yes'] = "Yes";
$lang['components_modal_componen_delete_confirm_no'] = "No";
$lang['components_modal_component_saving'] = "Saving...";
$lang['components_modal_components_save'] = "Save changes";
$lang['components_modal_component_cancel'] = "Cancel";
$lang['components_modal_component_addtitle'] = "Add component";
$lang['comopnents_modal_addcomponent_create'] = "Create component";
$lang['components_modal_addcomponent_cancel'] = "Cancel";

// builder_elements/views/modal_deleteblockcategory.php

$lang['modal_deleteblockscategory_heading'] = "Are you sure?";
$lang['modal_deleteblockscategory_text'] = "Would you like to move blocks currently in this category to another category? If so, please choose a category using the drop-down below.";
$lang['modal_deleteblockscategory_button_remove'] = "Remove";
$lang['modal_deleteblockscategory_button_cancel'] = "Cancel";

// builder_elements/views/partial_blockdetails.php

$lang['partial_blockdetails_label_category'] = "Block category:";
$lang['partial_blockdetails_label_template'] = "Choose an existing file:";
$lang['partial_blockdetails_label_fullheight'] = "Block uses full height of viewport";
$lang['partial_blockdetails_label_remakethumb'] = "Remake this block's thumbnail image?";
$lang['partial_blockdetails_help_fullheight'] = "Check this checkbox if you want this block to use the full height of the browser's viewport";
$lang['partial_blockdetails_help_remakethumb'] = "Recreating the thumbnail here forces the application to use standard dimensions, which may result in the image looking blurry or off. For a better screenshot, use the page builder itself to re-generate the thumbnail.";
$lang['partial_blockdetails_fileupload_select'] = "Select file";
$lang['partial_blockdetails_fileupload_change'] = "Change";
$lang['partial_blockdetails_fileupload_remove'] = "Remove";

// builder_elements/views/partial_componentdetails.php

$lang['partial_componentdetails_label_thumbnail'] = "Component thumbnail";
$lang['partial_componentdetails_fileupload_select'] = "Select file";
$lang['partial_componentdetails_fileupload_change'] = "Change";
$lang['partial_componentdetails_fileupload_remove'] = "Remove";
$lang['partial_componentdetails_label_category'] = "Component category";
$lang['partial_componentdetails_label_html'] = "Component markup";

// builder_elements/models/Blocks_model.php

$lang['blocks_model_all_category'] = "All blocks";
$lang['blocks_model_fav_category'] = "Saved blocks";

/**** Builder_elements Module end ****/


/**** Builder_elements Module Start ****/

// codeupdate/controller/Codeupdate.php

$lang['codeupdate_index_success'] = "All good! You can login now. <br>Please don't forget to <b>DELETE</b> the /install folder.";

/**** Builder_elements Module end ****/


/**** File_editor Module Start ****/

// file_editor/controllers/File_editor.php
$lang['file_editor_editor_message_file_saved'] = "The file was saved successfully.";
$lang['file_editor_editor_message_file_cantbesaved'] = "The file could not be saved. Please try again.";
$lang['file_editor_editor_message_file_doesnotexist'] = "File does not exist on server.";
$lang['file_editor_editor_message_file_missing'] = "Content or file name missing.";

// file_editor/views/editor.php
$lang['file_editor_button_label_close'] = "Close editor";
$lang['file_editor_button_label_confirm'] = "Save file";

$lang['file_editor_confirmation_close_title'] = "Close editor?";
$lang['file_editor_confirmation_close_title2'] = "Are you sure?";
$lang['file_editor_confirmation_close_content'] = "Your changes will NOT be saved";
$lang['file_editor_confirmation_close_content2'] = "This would overwrite the file's content and can not be undone.";
$lang['file_editor_confirmation_no'] = "No";
$lang['file_editor_confirmation_yes'] = "Yes";

/**** File_editor Module end ****/



/**** Front end items  ****/
$lang['front_end_spectrum_cancel'] = "Cancel";
$lang['front_end_spectrum_choose'] = "Choose";

$lang['styles']['padding-top'] = "Padding top";
$lang['styles']['padding-bottom'] = "Padding bottom";
$lang['styles']['padding-left'] = "Padding left";
$lang['styles']['padding-right'] = "Padding right";
$lang['styles']['margin-top'] = "Margin top";
$lang['styles']['margin-bottom'] = "Margin bottom";
$lang['styles']['margin-left'] = "Margin left";
$lang['styles']['margin-right'] = "Margin right";
$lang['styles']['height'] = "Height";
$lang['styles']['width'] = "Width";
$lang['styles']['color'] = "Text color";
$lang['styles']['font-size'] = "Font size";
$lang['styles']['font-weight'] = "Font weight";
$lang['styles']['text-transform'] = "Text transform";
$lang['styles']['text-align'] = "Text align";
$lang['styles']['background-color'] = "Background color";
$lang['styles']['background-color-overlay'] = "Background overlay";
$lang['styles']['background-position'] = "Background position";
$lang['styles']['parallax'] = "Parallax";
$lang['styles']['background-image'] = "Background image";
$lang['styles']['border-top-left-radius'] = "Top left radius";
$lang['styles']['border-top-right-radius'] = "Top right radius";
$lang['styles']['border-bottom-left-radius'] = "Bottom left radius";
$lang['styles']['border-bottom-right-radius'] = "Bottom right radius";
$lang['styles']['border-color'] = "Border color";
$lang['styles']['border-style'] = "Border style";
$lang['styles']['border-width'] = "Border width";
$lang['styles']['content'] = "Content";
$lang['styles']['btn-style'] = "Button style";
$lang['styles']['btn-size'] = "Button size";
$lang['styles']['btn-block'] = "Button block";