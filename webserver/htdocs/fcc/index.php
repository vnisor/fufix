<?php
session_start();
require_once "inc/vars.inc.php";
require_once "inc/functions.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>fufix control center</title>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<alink href="../css/roboto.min.css" rel="stylesheet">
<link href="../css/material.min.css" rel="stylesheet">
<link href="../css/ripples.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/"><?php echo $MYHOSTNAME ?></a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="/rc">Webmail</a></li>
				<li><a href="/pfadmin">Postfixadmin</a></li>
				<li><a href="/fcc">fufix control center</a></li>
				<li><a href="#" onclick="logout.submit()">
<?php
if (isset($_SESSION['fufix_cc_loggedin']) && $_SESSION['fufix_cc_loggedin'] == "yes") {
    echo "Logout";
}
else {
    echo "";
}
?>
</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div><!--/.container-fluid -->
</nav>

<form action="/fcc/" method="post" id="logout"><input type="hidden" name="logout"></form>
<div class="container">

<?php
require_once "inc/triggers.inc.php";
if (isset($_SESSION['fufix_cc_loggedin']) && $_SESSION['fufix_cc_loggedin'] == "yes") {
?>
<h1><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Configuration</h1>

<div class="panel panel-default">
<div class="panel-heading">Attachments</div>
<div class="panel-body">
<form method="post">
<div class="form-group">
	<p>Provide a list of dangerous file types. Please take care of the formatting.</p>
	<input class="form-control" type="text" id="ext" name="ext" value="<?php echo return_fufix_config("extlist") ?>">
	<p><pre>Format: ext1|ext1|ext3
Enter "DISABLED" to disable this feature.</pre></p>
	<div class="radio">
		<label>
		<input type="radio" name="vfilter" id="vfilter_reject_button" value="reject" <?php if (!return_fufix_config("vfilter")) { echo "checked"; } ?>>
		Reject attachments with a dangerous file extension
		</label>
	</div>
	<div class="radio">
		<label>
		<input type="radio" name="vfilter" id="vfilter_scan_button" value="filter" <?php echo return_fufix_config("vfilter") ?>>
		Scan attachments with ClamAV and/or upload to VirusTotal
		</label>
	</div>
	<hr>
	<div class="row">
		<div class="col-sm-6">
			<small>
			<h4>ClamAV</h4>
			<div class="checkbox">
					<label>
					<input name="clamavenable" type="checkbox" <?php echo return_fufix_config("cavenable") ?>>
					Use ClamAV to scan mail
					</label>
			</div>
			<p>
			<ul class="nav nav-pills">
				<li><a href="?av_dl">Download quarantined items<span class="badge"><?php echo_sys_info("positives"); ?></span></a></li>
			</ul></p>
			<p>Clean directory <code>/opt/vfilter/clamav_positives/</code> to reset counter.</p>
			<p>Senders of infected messages are informed about failed delivery.</p>
			</small>
		</div>
		<div class="col-sm-6">
			<small>
			<h4>VirusTotal Uploader</h4>
			<div class="checkbox">
					<label>
					<input name="virustotalenable" type="checkbox" <?php echo return_fufix_config("vtenable") ?>>
					Use the "VirusTotal Uploader" feature
					</label>
			</div>
			<p>Scan dangerous attachments via VirusTotal Public API.</p>
			<p><b>File handling and limitations</b> (<a href="https://www.virustotal.com/de/documentation/public-api/" target="_blank">VirusTotal Public API v2.0</a>)
			<ul>
				<li>Files up to 200M will be hashed. If a previous scan result was found, it will be attached.</em></li>
				<li>Files smaller than 32M will be uploaded if no previous scan result was found.</em></li>
			</ul>
			</p>
			<div class="checkbox">
					<label>
					<input name="virustotalcheckonly" type="checkbox"  <?php echo return_fufix_config("vtupload") ?>>
					Do <b>not</b> upload files to VirusTotal but check for a previous scan report. This also requires an API key!
					</label>
			</div>
			<label for="vtapikey">VirusTotal API Key, 64 char. alphanumeric (<a href="https://www.virustotal.com/documentation/virustotal-community/#retrieve-api-key" target="_blank">?</a>)</label>
			<p><input class="form-control" id="vtapikey" type="text" name="vtapikey" pattern="[a-zA-Z0-9]{64}" value="<?php echo return_fufix_config("vtapikey"); ?>"></p>
			</small>
		</div>
		<div class="col-sm-12">
		<h4>Filter Log (newest)</h4>
		<p><pre><?php echo_sys_info("vfilterlog", "20"); ?></pre></p>
		</div>
	</div>
	<br /><button type="submit" class="btn btn-primary btn-sm">Apply</button>
</div>
</form>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">Sender Blacklist</div>
<div class="panel-body">
<form method="post">
<div class="form-group">
	<p>Specify a list of senders or domains to blacklist access:</p>
	<textarea class="form-control" rows="6" name="sender"><?php return_fufix_config("senderaccess") ?></textarea>
	<br /><button type="submit" class="btn btn-primary btn-sm">Apply</button>
</div>
</form>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">Privacy</div>
<div class="panel-body">
<form method="post">
<div class="form-group">
	<p>This option enables a PCRE table to remove "User-Agent", "X-Enigmail", "X-Mailer", "X-Originating-IP" and replaces "Received: from" headers with localhost/127.0.0.1.</p>
	<div class="checkbox">
	<label>
	<input type="hidden" name="anonymize_">
	<input name="anonymize" type="checkbox" <?php echo return_fufix_config("anonymize") ?>>
		Anonymize outgoing mail
	</label>
	</div>
	<button type="submit" class="btn btn-primary btn-sm">Apply</button>
</div>
</form>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">DKIM Signing</div>
<div class="panel-body">
<p>Default behaviour is to sign with relaxed header and body canonicalization algorithm.</p>
<p><strong>DKIM signing will not be used when when "Anonymize outgoing mail" is enabled.</strong></p>
<form method="post" action="index.php">
<h4>Active keys</h4>
<?php opendkim_table() ?>
<h4>Add new key</h4>
<div class="form-group">
	<div class="row">
		<div class="col-md-4">
			<strong>Domain</strong>
			<input class="form-control" id="dkim_domain" name="dkim_domain" placeholder="example.org">
		</div>
		<div class="col-md-4">
			<strong>Selector</strong>
			<input class="form-control" id="dkim_selector" name="dkim_selector" placeholder="default">
		</div>
		<div class="col-md-4">
			<br /><button type="submit" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Add</button>
		</div>
	</div>
</div>
</form>
</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">Message Size</div>
<div class="panel-body">
	<form class="form-inline" method="post">
	<p>Current message size limitation: <strong><?php echo return_fufix_config("maxmsgsize"); ?>MB</strong></p>
	<p>This changes values in PHP, Nginx and Postfix. Services will be reloaded.</p>
	<div class="form-group">
		<input type="number" class="form-control" id="maxmsgsize" name="maxmsgsize" placeholder="in MB" min="1" max="250">
	</div>
	<button type="submit" class="btn btn-primary btn-sm">Set</button>
	</form>

</div>
</div>

<br />
<h1><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Maintenance</h1>

<div class="panel panel-default">
<div class="panel-heading">FAQ</div>
<div class="panel-body">

<p data-toggle="collapse" style="cursor:help;" data-target="#dnsrecords"><strong>DNS Records</strong></p>
<div id="dnsrecords" class="collapse out">
<p>Below you see a list of <em>recommended</em> DNS records.</p>
<p>While some are mandatory for a mail server (A, MX), others are recommended to build a good reputation score (TXT/SPF) or used for auto-configuration of mail clients (A: "autoconfig" and SRV records).</p>
<p>In this automatically generated DNS zone file snippet, a simple TXT/SPF record is used to only allow THIS server (the MX) to send mail for your domain. Every other server is disallowed ("-all"). Please refer to <a href="http://www.openspf.org/SPF_Record_Syntax" target="_blank">openspf.org</a>.</p>
<p>It is <strong>highly recommended</strong> to create a DKIM TXT record with the <em>DKIM Signing</em> utility tool above and install the given TXT record to your nameserver, too.</p>
<pre>
; ================
; Example forward zone file
; ================

[...]
_imaps._tcp         IN SRV     0 1 993 <?php echo $MYHOSTNAME; ?>.
_imap._tcp          IN SRV     0 1 143 <?php echo $MYHOSTNAME; ?>.
_submission._tcp    IN SRV     0 1 587 <?php echo $MYHOSTNAME; ?>.
@                   IN MX 10   <?php echo $MYHOSTNAME_0, "\n"; ?>
@                   IN TXT     "v=spf1 mx -all"
autoconfig          IN A       <?php echo $IP, "\n"; ?>
<?php echo str_pad($MYHOSTNAME_0, 20); ?>IN A       <?php echo $IP, "\n"; ?>

; !!!!!!!!!!!!!!!!
; Do not forget to set a PTR record in your Reverse DNS configuration!
; Your IPs PTR should point to <?php echo $MYHOSTNAME, "\n"; ?>
; !!!!!!!!!!!!!!!!
</pre>
</div>

<p data-toggle="collapse" style="cursor:help;" data-target="#commontasks"><strong>Example usage of <em>doveadm</em> for common tasks regarding Dovecot.</strong></p>
<div id="commontasks" class="collapse out">
<pre>
; Searching for inbox messages saved in the past 3 days for user "Bob.Cat":
doveadm search -u bob.cat@domain.com mailbox inbox savedsince 2d

; ...or search Bobs inbox for subject "important":
doveadm search -u bob.cat@domain.com mailbox inbox subject important

; Delete Bobs messages older than 100 days?
doveadm expunge -u bob.cat@domain.com mailbox inbox savedbefore 100d

; From Wiki: Move jane's messages - received in September 2011 - from her INBOX into her archive.
doveadm move -u jane Archive/2011/09 mailbox INBOX BEFORE 2011-10-01 SINCE 01-Sep-2011

; Visit http://wiki2.dovecot.org/Tools/Doveadm
</pre></div>

<p data-toggle="collapse" style="cursor:help;" data-target="#changevfiltermsg"><strong>VirusTotal message presets</strong></p>
<div id="changevfiltermsg" class="collapse out">
<pre>
; The vfilter is installed into /opt/vfilter
; You should not change any file here unless you know what you are doing
;
; Find and edit message presets here:
nano /opt/vfilter/replies
</pre></div>

<p data-toggle="collapse" style="cursor:help;" data-target="#backupmail"><strong>Backup mail</strong></p>
<div id="backupmail" class="collapse out">
<pre>
; If you want to create a backup of Bobs maildir to /var/mailbackup, just go ahead and create the backup destination with proper rights:
mkdir /var/mailbackup
chown vmail:vmail /var/mailbackup/

; Afterwards you can start a full backup:
dsync -u bob.cat@domain.com backup maildir:/var/mailbackup/

; Visit http://wiki2.dovecot.org/Tools/Dsync
</pre></div>

<p data-toggle="collapse" style="cursor:help;" data-target="#debugging"><strong>Debugging</strong></p>
<div id="debugging" class="collapse out">
<pre>
; Pathes to important log files:
/var/log/mail.log
/opt/vfilter/log/vfilter.log
/var/log/syslog
/var/log/nginx/error.log
/var/www/mail/rc/logs/errors
/var/log/php5-fpm.log
</pre></div>

</div>
</div>

<div class="panel panel-default">
<div class="panel-heading">System Information</div>
<div class="panel-body">
<p>This is a very simple system information function. Please be aware that a high RAM usage is what you want on a server.</p>
<div class="row">
	<div class="col-md-6">
		<h4>Disk usage (/var/vmail) - <?php echo_sys_info("maildisk"); ?>%</h4>
		<div class="progress">
		  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?php echo_sys_info("maildisk"); ?>"
		  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo_sys_info("maildisk"); ?>%">
		  </div>
		</div>
	</div>
	<div class="col-md-6">
		<h4>RAM usage - <?php echo_sys_info("ram"); ?>%</h4>
		<div class="progress">
		  <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="<?php echo_sys_info("ram"); ?>"
		  aria-valuemin="0" aria-valuemax="100" style="width:<?php echo_sys_info("ram"); ?>%">
		  </div>
		</div>
	</div>
</div>
<h4>Mail queue</h4>
<pre>
<?php echo_sys_info("mailq"); ?>
</pre>
</div>
</div>

<?php
} else {
?>
<div class="panel panel-default">
<div class="panel-heading">Login</div>
<div class="panel-body">
<form class="form-signin" method="post">
	<input name="login_user" type="email" id="inputEmail" class="form-control" placeholder="pfadmin@domain.tld" required autofocus>
	<input name="pass_user" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
	<p>You can login with any superadmin created in <b><a href="../pfadmin">Postfixadmin</a></b>.</p>
	<input type="submit" class="btn btn-success" value="Login">
</form>
</div>
</div>

<?php
}
?>
<p><b><a href="../">&#8592; go back</a></b></p>
</div> <!-- /container -->

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="../js/ripples.min.js"></script>
<script src="../js/material.min.js"></script>
<script>
$(document).ready(function() {
	$.material.init();
});
</script>

</body>
</html>
