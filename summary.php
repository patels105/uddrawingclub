<?php
function summary($db, $video_recharge) {
	$total = $db->querySingle("SELECT COUNT(*) FROM Playlist WHERE flagged=FALSE;");

	$new = $db->querySingle("SELECT COUNT(*) FROM Playlist WHERE new=TRUE AND flagged=FALSE;");

	$recharging = $db->querySingle("SELECT COUNT(*) FROM Playlist WHERE unixepoch('now') - lastplayed < ".$video_recharge." AND flagged=FALSE;");



	return $total." total, ".$new." new, ".$recharging." recharging";
}
?>
