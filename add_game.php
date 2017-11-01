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
		$st=$db->prepare("SELECT Rating from players WHERE Nick=?");
		$st->execute([$_POST['red'.($i+1)]]);
		$var=$st->fetch();
		$red=$red+$var['Rating'];
	}
	for ($i=0;$i<3;$i++){

		$st=$db->prepare("SELECT Rating from players WHERE Nick=?");
		$st->execute([$_POST['black'.($i+1)]]);
		$var=$st->fetch();
		$black=$black+$var['Rating'];
	}
	if ($_POST['win']=='true'){
		$rating=round((($black*3)/(7*$red))*20,0);
		for ($i=1;$i<8;$i++) {
			$st=$db->prepare("UPDATE players SET Rating=Rating+?,Num_games=Num_games+1,Wins=Wins+1,Red=Red+1,Wins_red=Wins_red+1 WHERE Nick=?");
			$st->execute([$rating,$_POST['red'.$i]]);
		}
		for($i=1;$i<4;$i++) {	
			$st=$db->prepare("UPDATE players SET Rating=Rating-?,Num_games=Num_games+1 WHERE Nick=?");
			$st->execute([$rating,$_POST['black'.$i]]);
		}
		$st=$db->prepare("UPDATE players SET Rating=Rating+?, MVP=MVP+1 WHERE Nick=?");
		$st->execute([$rating,$_POST['mvp']]);
		$st=$db->prepare("UPDATE players SET Cop=Cop+1,Wins_cop=Wins_cop+1, Red=Red-1, Wins_red=Wins_red-1 WHERE Nick=?");
		$st->execute([$_POST['red3']]);# code...
		$st=$db->prepare("UPDATE players SET Don=Don+1 WHERE Nick=?");
		$st->execute([$_POST['black3']]);# code.
		echo ($rating);
		

	}else{
		$rating=round((($red*3)/(7*$black))*20,0);
		for($i=1;$i<4;$i++) {
			$st=$db->prepare("UPDATE players SET Rating=Rating+?,Num_games=Num_games+1,Wins=Wins+1,Black=Black+1,Wins_black=Wins_black+1 WHERE Nick=?");
			$st->execute([$rating,$_POST['black'.$i]]);
		}
		for ($i=1;$i<8;$i++) {	
			$st=$db->prepare("UPDATE players SET Rating=Rating-?,Num_games=Num_games+1 WHERE Nick=?");
			$st->execute([$rating,$_POST['red'.$i]]);
		}
		$st=$db->prepare("UPDATE players SET Rating=Rating+?, MVP=MVP+1 WHERE Nick=?");
		$st->execute([$rating,$_POST['mvp']]);
		$st=$db->prepare("UPDATE players SET Cop=Cop+1 WHERE Nick=?");
		$st->execute([$_POST['red7']]);# code...
		$st=$db->prepare("UPDATE players SET Don=Don+1, Wins_don=Wins_don+1 WHERE Nick=?");
		$st->execute([$_POST['black3']]);# code.
		echo ($rating);
	}
}
?>
<br>
<a href=/new_game.php style="color: #0000ff">Все хорошо, иди назад</a>