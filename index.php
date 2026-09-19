<?php
include 'conf.php';
include 'summary.php';

$db = new SQLite3($db_path);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<meta name="color-scheme" content="light dark">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
		<title><?=$site_title?></title>
	</head>
	<body>
		<header class="container">
			<h1><?=$site_title?></h1>
			<a href="/">Home</a>
			<p><?=summary($db, $video_recharge)?></p>
		</header>
		<main class="container">
			<ul>
				<li><a href="/player.php">View playlist</a></li>
				<li><a href="/submit.php">Add to playlist</a></li>
				<li><a href="/download_db.php">Download database (SQLite)</a></li>
				<li><a href="/source_code.zip">Download source code (ZIP)</a></li>
				<li><a href="/download_video_list_script.php">Script to get video titles from database (Python)</a></li>
			</ul>
		</main>
	</body>
</html>
