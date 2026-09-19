<?php
include 'conf.php';
include 'summary.php';

$db = new SQLite3($db_path);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
	// Extract id from video
	preg_match('/.*(?:youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#\&\?]*).*/', $_POST["url"], $matches);

	// Die if no valid id
	if (count($matches) <= 1) {
		header('Location: /submit.php?result=invalidlink');
		die();
	}

	$videoid = $matches[1];

	// Check to see if the video is flagged
	$statement = $db->prepare("SELECT flagged FROM Playlist WHERE videoid=:videoid;");
	$statement->bindValue(':videoid', $videoid);
	$existing_video = $statement->execute()->fetchArray();
	if ($existing_video && $existing_video['flagged']) {
		header('Location: /submit.php?result=flagged');
		die();
	}

	// Insert into database
	$statement = $db->prepare("INSERT into Playlist (videoid, new, timestamp) VALUES (:videoid, TRUE, :timestamp);");
	$statement->bindValue(':videoid', $videoid);
	$statement->bindValue(':timestamp', time());
	$statement->execute();

	header('Location: /submit.php?result=success');
	die();
}
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<meta name="color-scheme" content="light dark">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
		<title><?=$site_title?> Submit</title>
	</head>
	<body>
		<header class="container">
			<h1><?=$site_title?></h1>
			<a href="/">Home</a>
			<p><?=summary($db, $video_recharge)?></p>

			<?php if (isset($_GET['result'])) {
				echo '<p>';
				if ($_GET['result'] == "success") {
					echo "Video successfully submitted!";
				} else if ($_GET['result'] == 'flagged') {
					echo "That video has previously been flagged";
				} else if ($_GET['result'] == "invalidlink") {
					echo "Please input a valid youtube link.";
				}
				echo '</p>';
			} ?>

		</header>
		<main class="container">
			<form action  ="/submit.php" method="POST">
				<label for="url">
					Input a Youtube link to add it to the playlist.<br>
			<small>(Please do not add anything explicit.)</small>
				</label><br>
				<input type="url" name="url">
				<input type="submit" value="Submit Link">
			</form>
		</main>
	</body>
</html>
