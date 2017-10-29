<?
session_start();
if (isset($_SESSEION['admin'])){
	include "db.php";
	if ($_POST['start']){
		$st=$db->query("SELECT id,Rating FROM players");
		while($player=$st->fetch()){
			$st1=$db->prepare("UPDATE players SET Prev_rating=? WHERE id=?");
			$st1->execute([$player['Rating'],$player['id']]);
		}
	}
	$red=0;
	$black=0;
	for ($i=0;$i<7;$i++){
		$st=$db->prepare("SELECT Rating from players WHERE id=?");
		$st->execute([$_POST['red'][$i]]);
		$var=$st->fetch();
		$red=$red+$var['Rating']/7;
	}
	for ($i=0;$i<3;$i++){
		$st=$db->prepare("SELECT Rating from players WHERE id=?");
		$st->execute([$_POST['black'][$i]]);
		$var=$st->fetch();
		$black=$black+$var['Rating']/3;
	}
	if ($_POST['win']){
		$rating=round(($black/$red)*20,0);
		$mas=array();
		foreach ($_POST['red'] as $key => $value) {
			if ((int)$value!=(int)$_POST['mvp']){
				$mas[]=$value;
			}
					
		}
		foreach ($mas as $key => $value) {
			$st=$db->prepare("SELECT Rating FROM players WHERE id=?");
			$st->execute([$value]);
			$var=$st->fetch();		
			$st=$db->prepare("UPDATE players SET Rating=? where id=?");
			$st->execute([$rating+$var['Rating'],$value]);
		}
		foreach ($_POST['black'] as $key => $value) {
			$st=$db->prepare("SELECT Rating FROM players WHERE id=?");
			$st->execute([$value]);
			$var=$st->fetch();		
			$st=$db->prepare("UPDATE players SET Rating=? where id=?");
			$st->execute([$rating-$var['Rating'],$value]);
		}
		$st=$db->prepare("SELECT Rating,MVP FROM players WHERE id=?");
		$st->execute([$_POST['mvp']]);
		$var=$st->fetch();		
		$st=$db->prepare("UPDATE players SET Rating=?, MVP=? where id=?");
		$st->execute([$rating*2+$var['Rating'],$var['MVP']+1,$_POST['mvp']]);
		echo ($rating);
		

	}else{
		$rating=round(($red/$black)*20,0);
		echo ($rating);
	}
}
?>