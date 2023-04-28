<?php
//get cPanel user
$cpanel_user = getenv('REMOTE_USER');

//include the cPanel API module
include_once('/usr/local/cpanel/php/cpanel.php');

//create a new cPanel instance
$cpanel = new CPANEL();

//get list of databases for cPanel user
$db_list = $cpanel->uapi('Mysql', 'list_databases', array('cpanel_username' => $cpanel_user));

//iterate through databases and create backups
foreach($db_list['data'] as $db){
    $db_name = $db['Db'];
    $backup_file = "/home/{$cpanel_user}/unlimited/DB/{$db_name}.sql.gz";
    $cpanel->uapi('Mysql', 'backup_database', array('name' => $db_name, 'cpanel_account' => $cpanel_user, 'backup_file' => $backup_file));
}
?>
