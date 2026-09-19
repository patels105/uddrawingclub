<?php
include 'conf.php';

header('Location: /player.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$videoid = $_POST['videoid'];

	error_log('Error with videoId "'.$videoid.'"');
	
	$db = new SQLite3($db_path);

	$statement = $db->prepare("UPDATE Playlist SET error=TRUE WHERE videoid=:videoid;");
	$statement->bindValue(':videoid', $videoid);
	$statement->execute();

}
