<?
include "db.php";
	if ($_POST['start']){
		$st=$db->query("SELECT id,Rating FROM players");
		while($player=$st->fetch()){
			$st1=$db->prepare("UPDATE players SET Prev_rating=? WHERE id=?");
			$st1->execute([$player['Rating'],$player['id']]);
		}
	}
	echo "Ok";
?>