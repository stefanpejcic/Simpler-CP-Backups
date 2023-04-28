<?php
//get cPanel user
$cpanel_user = getenv('REMOTE_USER');

//include the cPanel API module
include_once('/usr/local/cpanel/php/cpanel.php');

//create a new cPanel instance
$cpanel = new CPANEL();

//get list of email accounts for cPanel user
$email_list = $cpanel->uapi('Email', 'list_pops', array('cpanel_username' => $cpanel_user));

//iterate through email accounts and create backups
foreach($email_list['data'] as $email){
    $email_address = $email['email'];
    $backup_file = "/home/{$cpanel_user}/unlimited/Emails/{$email_address}.tar.gz";
    shell_exec("tar -czf {$backup_file} /etc/vmail/{$cpanel_user}/{$email_address}/ /home/{$cpanel_user}/mail/{$email_address}/");
}
?>
