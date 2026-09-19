<?php
$video_list_script_path = "get_video_list.py";

header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"".basename($video_list_script_path)."\""); 
readfile($video_list_script_path);
?>
