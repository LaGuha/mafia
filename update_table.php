<?
session_start();
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
