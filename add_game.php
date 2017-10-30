<?
session_start();

if (isset($_SESSION['admin'])){
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
			$st=$db->prepare("SELECT * FROM players WHERE id=?");
			$st->execute([$value]);
			$var=$st->fetch();		
			$st=$db->prepare("UPDATE players SET Rating=?,Num_games=?,Wins=?,Red=?,Wins_red=? WHERE id=?");
			$st->execute([$rating+$var['Rating'],$var['Num_games']+1,$var['Wins']+1,$var['Red']+1,$var['Wins_red']+1,$value]);
		}
		foreach ($_POST['black'] as $key => $value) {
			$st=$db->prepare("SELECT * FROM players WHERE id=?");
			$st->execute([$value]);
			$var=$st->fetch();		
			$st=$db->prepare("UPDATE players SET Rating=?,Num_games=? WHERE id=?");
			$st->execute([$var['Rating']-$rating,$var['Num_games']+1,$value]);
		}
		$st=$db->prepare("SELECT * FROM players WHERE id=?");
		$st->execute([$_POST['mvp']]);
		$var=$st->fetch();		
		$st=$db->prepare("UPDATE players SET Rating=?,Num_games=?,Wins=?,Red=?,Wins_red=?, MVP=? WHERE id=?");
		$st->execute([$rating*2+$var['Rating'],$var['Num_games']+1,$var['Wins']+1,$var['Red']+1,$var['Wins_red']+1,$var['MVP']+1,$_POST['mvp']]);
		$st=$db->prepare("SELECT * FROM players WHERE id=?");
		$st->execute([$_POST['cop']]);
		$var=$st->fetch();		
		$st=$db->prepare("UPDATE players SET Cop=?,Wins_cop=? WHERE id=?");
		$st->execute([$var['Cop']+1,$var['Wins_cop']+1,$_POST['cop']]);# code...
		$st=$db->prepare("SELECT * FROM players WHERE id=?");
		$st->execute([$_POST['don']]);
		$var=$st->fetch();		
		$st=$db->prepare("UPDATE players SET Don=? WHERE id=?");
		$st->execute([$var['Don']+1,$_POST['don']]);# code.
		echo ($rating);
		

	}else{
		$rating=round(($red/$black)*20,0);
		$mas=array();
		foreach ($_POST['black'] as $key => $value) {
			if ((int)$value!=(int)$_POST['mvp']){
				$mas[]=$value;
			}
					
		}
		foreach ($mas as $key => $value) {
			$st=$db->prepare("SELECT * FROM players WHERE id=?");
			$st->execute([$value]);
			$var=$st->fetch();		
			$st=$db->prepare("UPDATE players SET Rating=?,Num_games=?,Wins=?,Black=?,Wins_black=? WHERE id=?");
			$st->execute([$rating+$var['Rating'],$var['Num_games']+1,$var['Wins']+1,$var['Black']+1,$var['Wins_black']+1,$value]);
		}
		foreach ($_POST['red'] as $key => $value) {
			$st=$db->prepare("SELECT * FROM players WHERE id=?");
			$st->execute([$value]);
			$var=$st->fetch();		
			$st=$db->prepare("UPDATE players SET Rating=?,Num_games=? WHERE id=?");
			$st->execute([$var['Rating']-$rating,$var['Num_games']+1,$value]);
		}
		$st=$db->prepare("SELECT * FROM players WHERE id=?");
		$st->execute([$_POST['mvp']]);
		$var=$st->fetch();		
		$st=$db->prepare("UPDATE players SET Rating=?,Num_games=?,Wins=?,Black=?,Wins_black=?,MVP=? WHERE id=?");
		$st->execute([$rating*2+$var['Rating'],$var['Num_games']+1,$var['Wins']+1,$var['Black']+1,$var['Wins_black']+1,$var['MVP']+1,$_POST['mvp']]);
		$st=$db->prepare("SELECT * FROM players WHERE id=?");
		$st->execute([$_POST['cop']]);
		$var=$st->fetch();		
		$st=$db->prepare("UPDATE players SET Cop=? WHERE id=?");
		$st->execute([$var['Cop']+1,$_POST['cop']]);# code...
		$st=$db->prepare("SELECT * FROM players WHERE id=?");
		$st->execute([$_POST['don']]);
		$var=$st->fetch();		
		$st=$db->prepare("UPDATE players SET Don=?, Wins_don=? WHERE id=?");
		$st->execute([$var['Don']+1,$var['Wins_don']+1,$_POST['don']]);# code.
		echo ($rating);
	}
}
?>