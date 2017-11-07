
<? session_start();?>
<head>
	<title>Рейтинг</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css.css">
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
</head>
<body>
	<? 
		if (!isset($_SESSION['admin'])){
			?>
				<form>
					<p>Login <input name=login></p>
					<p>Password <input name=password></p>
					<input type=hidden name=admin value=1>
					<button type=submit>Enter</button>
				</form>
			<? 
		}else{


	?>
	<form method=POST>
		<p>Ник:&nbsp;<input name="Nick"></p>
		<p>Рейтинг:&nbsp;<input name="Rating"></p>
		<p><input type=submit value="Добавить"></p>
	</form>
	<p>Вставить таблицу</p>
	<form action=update_table.php method="POST" enctype="multipart/form-data">
		<input type="file" name=file>
		<button type=submit>Обновить таблицу</button>
	</form>

	<form method=POST action="update_table.php">
		<input type=hidden name=dwnload>
		<button type=submit>Выгрузка</button>
	</form>
	<? 		}
	if (isset($_POST['Nick'])){
		include "db.php";
		$st=$db->prepare("INSERT INTO players VALUES (id,?,?,?,0,0,0,0,0,0,0,0,0,0,0)");
		$st->execute([$_POST['Nick'],$_POST['Rating'],$_POST['Rating']]);
	}
	if (isset($_GET['admin'])){
		include "db.php";
		$st=$db->prepare("SELECT password FROM stuff WHERE login=?");
		$st->execute([$_GET['login']]);
		$stuff=$st->fetch();
		if ($stuff['password']=$_GET['password']){
			$_SESSION['admin']=1;
		}
	}