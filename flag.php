<?php
include 'conf.php';

$db = new SQLite3($db_path);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$videoid = $_POST['videoid'];

	$statement = $db->prepare("UPDATE Playlist SET flagged=TRUE WHERE videoid=:videoid;");
	$statement->bindValue(":videoid", $videoid);
	$statement->execute();
}

header('Location: /player.php');
die();
