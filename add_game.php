<?
session_start();

if (isset($_SESSION['admin'])){
	include "db.php";

	$red=0;
	$black=0;
	$var=array();
	for ($i=0;$i<7;$i++){
		$st=$db->prepare("SELECT Rating from players WHERE Nick=?");
		$st->execute([$_POST['red'.($i+1)]]);
		$var[]=$st->fetch();
		$red=$red+$var[$i]['Rating'];
	}
	$var1=array();
	for ($i=0;$i<3;$i++){

		$st=$db->prepare("SELECT Rating from players WHERE Nick=?");
		$st->execute([$_POST['black'.($i+1)]]);
		$var1[]=$st->fetch();
		$black=$black+$var1[$i]['Rating'];

	}
	if ($_POST['win']=='true'){
		$rating=round((($black*7)/(3*$red))*20,0);
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
		$st->execute([$_POST['red7']]);# code...
		$st=$db->prepare("UPDATE players SET Don=Don+1 WHERE Nick=?");
		$st->execute([$_POST['black3']]);# code.
		$st=$db->prepare("INSERT INTO games VALUES (id,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		//print_r($_POST);
		$st->execute([$_POST['Lead'],date($_POST['data']),$_POST['Num'],$_POST['red1'].';'.$var[0]['Rating'],$_POST['red2'].';'.$var[1]['Rating'],$_POST['red3'].';'.$var[2]['Rating'],$_POST['red4'].';'.$var[3]['Rating'],$_POST['red5'].';'.$var[4]['Rating'],$_POST['red6'].';'.$var[5]['Rating'],$_POST['red7'].';'.$var[6]['Rating'],$_POST['black1'].';'.$var1[0]['Rating'],$_POST['black2'].';'.$var1[1]['Rating'],$_POST['black3'].';'.$var1[2]['Rating'],$_POST['win']]);
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
<a href=new_game.php style="color: #0000ff">Все хорошо, иди назад</a>