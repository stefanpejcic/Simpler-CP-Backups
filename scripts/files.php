<?php
//get cPanel user
$cpanel_user = getenv('REMOTE_USER');

//set backup folder path
$backup_folder = "/home/{$cpanel_user}/unlimited/Files/";

//get user's home directory
$user_home_dir = exec("eval echo ~{$cpanel_user}");

//set number of parallel processes
$parallel_processes = 4;

//build rsync command
$rsync_command = "rsync -r --delete --progress --human-readable --exclude='.cpanel' {$user_home_dir} {$backup_folder}";

//split directories into multiple processes
$dirs = array_diff(scandir($user_home_dir), array('..', '.'));
$dir_chunks = array_chunk($dirs, ceil(count($dirs)/$parallel_processes));

//start parallel processes
foreach($dir_chunks as $dir_chunk) {
    $cmd = $rsync_command . ' ' . implode(' ', array_map('escapeshellarg', $dir_chunk));
    $pid = shell_exec(sprintf('%s > /dev/null 2>&1 & echo $!', $cmd));
}
?>
