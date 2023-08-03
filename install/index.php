<?php
require_once('includes/core_class.php');
$core = new Core();
$request_url = $core->server_scheme() . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$request = $core->get_domaininfo($request_url);
if ($request['subdomain'] == 'www')
{
	header('Location: ' . str_replace("www.", "", $request_url));
    die();
}

/** Sets which PHP errors are reported */
error_reporting(E_ERROR);
/** Configure PHP settings */
set_time_limit(300); // Set execution time to 300 sec.


/** Check all the files and folders permission */
$message = '';
/** Check folder permission */
if ( ! is_writable('../tmp'))
{
	$message .= './tmp folder is not writable. <br>';
}
if ( ! is_writable('../tmp/attachments'))
{
	$message .= './tmp/attachments folder is not writable. <br>';
}
if ( ! is_writable('../tmp/blockthumbs'))
{
	$message .= './tmp/blockthumbs folder is not writable. <br>';
}
if ( ! is_writable('../tmp/ftp'))
{
	$message .= './tmp/ftp folder is not writable. <br>';
}
if ( ! is_writable('../tmp/sitethumbs'))
{
	$message .= './tmp/sitethumbs folder is not writable. <br>';
}
if ( ! is_writable('../tmp/thumbs'))
{
	$message .= './tmp/thumbs folder is not writable. <br>';
}
if ( ! is_writable('../images'))
{
	$message .= './images folder is not writable. <br>';
}
if ( ! is_writable('../images/uploads'))
{
	$message .= './images/uploads folder is not writable. <br>';
}
if ( ! is_writable('../elements'))
{
	$message .= './elements folder is not writable. <br>';
}
/** Check files permission */
if ( ! is_writable('../index.php'))
{
	$message .= './index.php file is not writable. <br>';
}
if ( ! is_writable('../application/config/config.php'))
{
	$message .= './application/config/config.php file is not writable. <br>';
}
if ( ! is_writable('../application/config/database.php'))
{
	$message .= './application/config/database.php file is not writable. <br>';
}
if (file_exists('../.htaccess'))
{
	if ( ! is_writable('../.htaccess'))
	{
		$message .= './.htaccess file is not writable. <br>';
	}
}

/** If files need write permission show that or unset the message var */
if ($message != '')
{
	$message .= 'Please give the above fiel(s)/folder(s) write permission to operate the application properly.';
}
else
{
	unset($message);
}

$host = '';

if ( ! empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
{
    $host .= $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://';
    $host .= $_SERVER['HTTP_HOST'] . '/';
}
else
{
    $host .= ! empty($_SERVER['HTTPS']) ? "https://" : "http://";
    $host .= $_SERVER['HTTP_HOST'] . '/';
}

/** Only load the classes in case the user submitted the form */
if ($_POST)
{
	/** Load the classes and create the new objects */
	require_once('includes/core_class.php');
	require_once('includes/database_class.php');

	$core = new Core();
	$database = new Database();

	/** Validate the post data */
	if ($core->validate_post($_POST) == TRUE)
	{
		if ($database->create_tables($_POST) == FALSE)
		{
			$message = $core->show_message('error', "The database tables could not be created, please verify your settings.");
		}
		else if ($core->write_config($_POST) == FALSE)
		{
			$message = $core->show_message('error', "The index.php or config.php or database.php file could not be written, please chmod index.php, application/config/config.php and application/config/database.php file to 777");
		}

		sleep(10);

		/** Create admin user */
		$database->create_admin($_POST, $_POST['email'], $_POST['password_admin']);

		/** Copy _htaccess file as .htaccess */
		copy('../_htaccess', '../.htaccess');

		/** If no errors, redirect to auth page */
		if ( ! isset($message))
		{
                	header('Location: ../codeupdate/succesfullInstallation');
		}
	}
	else
	{
		$message = $core->show_message('error', 'Not all fields have been filled in correctly. <b>All fields below are required to install Bloxby.</b>');
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<title>Bloxby Installation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="../build/login.css" rel="stylesheet">

	<link rel="shortcut icon" href="../img/favicon.png">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
	<!--[if lt IE 9]>
	<script src="../js/html5shiv.js"></script>
	<script src="../js/respond.min.js"></script>
	<![endif]-->
	<style>
		body.login form .input-group .input-group-btn button.btn {
			height: 42px;
		}
		h5.smaller {
			font-size: 20px;
			margin-bottom: 20px;
		}
	</style>
</head>

<body class="login">

	<div class="container">

		<div class="row">

			<div class="col-md-4 col-md-offset-4">

				<h2 class="text-center">
					<b>Bloxby</b>
				</h2>

				<?php if (isset($message)) : ?>
					<div class="alert alert-danger">
						<button type="button" class="close fui-cross" data-dismiss="alert"></button>
						<?php echo $message; ?>
					</div>
				<?php endif; ?>

				<form role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

					<h5 class="smaller"><span class="fui-gear"></span> Database Configuration</h5>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-home"></span></button>
						</span>
						<input type="text" class="form-control" id="hostname" name="hostname" tabindex="1" autofocus value="<?php if (isset($_POST['hostname'])) { echo $_POST['hostname']; } else { echo "localhost"; } ?>" placeholder="Hostname">
					</div>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-user"></span></button>
						</span>
						<input type="text" class="form-control" id="username" name="username" tabindex="2" value="<?php if (isset($_POST['username'])) { echo $_POST['username']; } ?>" placeholder="Username">
					</div>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-lock"></span></button>
						</span>
						<input type="password" class="form-control" id="password" name="password" tabindex="3" value="<?php if (isset($_POST['username'])) { echo $_POST['password']; } ?>" placeholder="Password">
					</div>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-list"></span></button>
						</span>
						<input type="text" class="form-control" id="database" name="database" tabindex="4" value="<?php if (isset($_POST['database'])) { echo $_POST['database']; } ?>" placeholder="Database name">
					</div>

					<hr class="dashed light" style="margin-top: 40px; margin-bottom: 40px">

					<h5 class="smaller"><span class="fui-user"></span> Admin User Setup</h5>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-mail"></span></button>
						</span>
						<input type="text" class="form-control" id="email" name="email" tabindex="5" value="<?php if (isset($_POST['email'])) { echo $_POST['email']; } ?>" placeholder="Email address">
					</div>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-lock"></span></button>
						</span>
						<input type="password" class="form-control" id="password_admin" name="password_admin" tabindex="6" value="<?php if (isset($_POST['password_admin'])) { echo $_POST['password_admin']; } ?>" placeholder="Password">
					</div>

					<hr class="dashed light" style="margin-top: 40px; margin-bottom: 40px">

					<h5 class="smaller"><span class="fui-home"></span> URL Setup</h5>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-home"></span></button>
						</span>
						<input type="text" class="form-control" id="base_url" name="base_url" tabindex="7" value="<?php if (isset($_POST['base_url'])) { echo $_POST['base_url']; } else { echo $host; } ?>" placeholder="Base URL">
					</div>

					<button type="submit" class="btn btn-primary btn-embossed btn-block"><span class="fui-check"></span> Install <b>Bloxby</b></button>

					<br><br>

				</form>

			</div><!-- /.col-md-6 -->

		</div><!-- /.row -->

	</div><!-- /.container -->

	<script src="../build/login.bundle.js"></script>
</body>
</html>