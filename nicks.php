<?
	include "db.php";

	$st=$db->query("SELECT Nick FROM players");
	$arr['Nick']=array();
	while ($var=$st->fetch()){
		$arr['Nick'][]=$var['Nick'];
	}
	echo (json_encode($arr));
?>