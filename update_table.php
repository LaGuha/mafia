<?
session_start();

if (isset($_POST['dwnload'])){
	function download_send_headers($filename) {
	// disable caching
	$now = gmdate("D, d M Y H:i:s");
	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	header("Last-Modified: {$now} GMT");

	// force download
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");

	// disposition / encoding on response body
	header("Content-Disposition: attachment;filename={$filename}");
	header("Content-Transfer-Encoding: binary");
}

function array2csv(array &$array, $titles) {
	if (count($array) == 0) {
		return null;
	}
	ob_start();
	$df = fopen("php://output", 'w');
	fputcsv($df, $titles, ';');
	foreach ($array as $row) {
		fputcsv($df, $row, ';');
	}
	fclose($df);
	return ob_get_clean();
}

$titles = array("Ник","Рейт","Пред","Игр","Побед","МВП","Красн","побед","Черный","побед","Шериф","побед","Дон","побед");
include "db.php";
$st=$db->query("SELECT * FROM players");
$data=array();
while ($player=$st->fetch()) {
	$data[]=array($player['Nick'],$player['Rating'],$player['Prev_rating'],$player['Num_games'],$player['Wins'],$player['MVP'],$player['Red'],$player['Wins_red'],$player['Black'],$player['Wins_black'],$player['Cop'],$player['Wins_cop'],$player['Don'],$player['Wins_don']);
}

download_send_headers("data_export.csv");
echo array2csv($data, $titles);
die();

}

if (isset($_SESSION['admin'])){
	include "db.php";
		$uploaddir = '/var/www/html/mafia/uploads/';
		$uploadfile = $uploaddir . "tmp.csv";

		echo '<pre>';
		if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		    echo "Файл корректен и был успешно загружен.\n";
		    $row = 1;
			if (($handle = fopen($uploadfile, "r")) !== FALSE) {
				$st=$db->query("TRUNCATE TABLE players");
				$st=$db->query("DELETE FROM `players` WHERE 1");
			    while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
			        $num = count($data);
			        //echo "<p> $num полей в строке $row: <br /></p>\n";
			        if ($row>1){
			        	$st=$db->prepare("INSERT INTO players VALUES (id,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			        //print_r($st);
			       		 $st->execute([$data[1],$data[18],$data[19],$data[3],$data[4],$data[17],$data[5],$data[6],$data[8],$data[9],$data[11],$data[12],$data[14],$data[15]]);
			        }
			        $row++;

			        
			    }
			    fclose($handle);
			}
		} else {
		    echo "Возможная атака с помощью файловой загрузки!\n";
		}

		echo 'Некоторая отладочная информация:';
		print_r($_FILES);

		print "</pre>";

	//header("Location:/mvp/admin.php?ok=ok");
}
?>
