<?php
include 'conf.php';

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"".basename($db_path)."\""); 
readfile($db_path);
?>
