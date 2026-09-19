<?php
include 'conf.php';
include 'summary.php';

$db = new SQLite3($db_path);

// Get a random new video
$result = $db->querySingle("SELECT videoid FROM Playlist WHERE new=TRUE AND Flagged = FALSE ORDER BY timestamp LIMIT 1;");

// If found, mark it as not new
if ($result) {
	$statement = $db->prepare("UPDATE Playlist SET new=FALSE WHERE videoid=:videoid AND Flagged = FALSE;");
	$statement->bindValue(':videoid', $result);
	$statement->execute();
}
// If not found, get a random video that was not played in the last $video_recharge seconds
else {
	$result = $db->querySingle("SELECT videoid FROM Playlist WHERE (unixepoch('now') - lastplayed > ".$video_recharge." OR lastplayed IS NULL) AND Flagged = FALSE ORDER BY Random() LIMIT 1;");

	// If not found, get a random video regardless or last played
	if (!$result) {
		$result = $db->querySingle("SELECT videoid FROM Playlist WHERE Flagged = FALSE ORDER BY Random() LIMIT 1;");
	}
}

$statement = $db->prepare("UPDATE Playlist SET lastplayed=unixepoch('now') WHERE videoid=:videoid;");
$statement->bindValue(':videoid', $result);
$statement->execute();
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
		<form action  ="/flag.php" method="POST">
		<input type="hidden" name="videoid" value="<?=$result?>">
			<input type="submit" class="secondary" style="width: auto;" value="Flag video">
		</form>
	</header>
	<main class="container">

		<div id="player"></div>

		<?php if (isset($submit_qrcode)) { ?>
		<div style="float: right;">
			<h2>Add more songs</h2>
			<a href="/submit.php">
			<img src="<?=$submit_qrcode?>">
			</a>
		</div>
		<?php } ?>
	</main>

    <script>
	var tag = document.createElement('script');

	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	var player;
	function onYouTubeIframeAPIReady() {
		player = new YT.Player('player', {
			height: '390',
			width: '640',
			videoId: '<?=$result?>',
			playerVars: {
				'playsinline': 1
			},
			events: {
				'onReady': onPlayerReady,
				'onStateChange': onPlayerStateChange,
				'onError': onPlayerError
			}
		});
	}

	function onPlayerReady(event) {
		event.target.playVideo();
	}

	function onPlayerStateChange(event) {
		if (event.data == YT.PlayerState.ENDED) {
			location.reload()
		}
	}

	function onPlayerError(event) {
		const xhttp = new XMLHttpRequest();
		xhttp.open("POST", "/report.php");
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send('videoid=<?=$result?>');
		location.reload()
	}
    </script>
  </body>
</html>
