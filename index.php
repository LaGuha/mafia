<? session_start(); unset($_SESSION['admin']);?>
<head>
	<title>Рейтинг</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css.css">
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
</head>
<body>
<? 
	if (isset($_SESSION['admin'])){
?>
	<center>
		<button id="start">Начать сессию</button>
		<button id="stop">Завершить сессию</button>
		<p>&nbsp;</p>
	</center>
<?
	}
?>
	<div id=table>
		<p class="header">Рейтинг клуба мафии Гринвич</p>
		<div class="player" style="position: fixed;top:21px;">
			<p style="width: 45px">№</p>
			<p style="width: 155px">Ник</p>
			<p style="width: 65px">Рейтинг</p>
			<p style="width: 55px">Дельта</p>
			<p style="width: 45px">Игр</p>
			<p style="width: 55px">Побед</p>
			<p style="width: 65px">% Побед</p>
			<p style="width: 45px">MVP</p>
			<p style="width: 155px">% Побед мирными</p>
			<p style="width: 155px">% Побед мафией</p>
			<p style="width: 155px">% Побед шерифом</p>
		</div>
		<? include "db.php";
			$st=$db->query("SELECT * FROM players ORDER BY Rating DESC");
			$i=0;
			while ($player=$st->fetch()){
				
				$delta=$player['Rating']-$player['Prev_rating'];
				if (($player['Num_games']>4 && ($player['Prev_rating']!=$player['Rating']))|| isset($_SESSION['admin'])){
					$i++;
				?>
					<div class="player" id="<?=$player['id']?>"<? if ($i==1){?>style="margin-top:40px;" <?}?> >
						<p style="width: 45px"><?=$i?></p>
						<p style="width: 155px" class="Name"><?=$player['Nick']?></p>
						<p style="width: 65px"><?=$player['Rating']?></p>
						<p style="width: 55px" <? if ($delta>0) echo "class=green"; elseif ($delta<0) echo "class=red";?>>
							<?=$delta?>
						</p>
						<p style="width: 45px"><?=$player['Num_games']?></p>
						<p style="width: 55px"><?=$player['Wins']?></p>
						<p style="width: 65px"><?=round($player['Wins']/$player['Num_games']*100,0)?> %</p>
						<p style="width: 45px"><?=$player['MVP']?></p>
						<p style="width: 155px"><? if ($player['Red']) echo(round($player['Wins_red']/$player['Red']*100,0))." %"; else echo "Нет игр";?></p>
						<p style="width: 155px"><? if ($player['Black']) echo (round($player['Wins_black']/$player['Black']*100,0))." %"; else echo "Нет игр";?></p>
						<p style="width: 155px"><?if ($player['Cop']) echo (round($player['Wins_cop']/$player['Cop']*100,0))." %"; else echo "Нет игр";?> </p>
					</div>
				<?
				}
			}
		?>
	</div>
	<div id=spacer>
	</div>
	<script type="text/javascript">
		$("#start").click(function(){
			alert("Пожалуйста, выберите красную команду");
			var red = new Array();
			var r=1;
			var cop=0
			var black= new Array();
			var b=1;
			var don=0;
			var start=1
			var mvp=0;
			var win =false

			
				$(".player").click(function(){
					
					if (r<8){
						var err=0; //повторно добавленный красный игрок
						for (var a=0;a<r;a++){
							if (red[a]==this.id){
								alert("Выбранный игрок уже добавлен");
								var err=1;
							}
						}
						if (!err){
							ok = confirm("Добавлен "+ $(this).find($(".Name")).text())
							if (ok){
								red.push(this.id)
								r++	
							}
							if (r==8){
								alert("Выберите шерифа")
							}	
						}
						
					}else if (!cop){
						var err=1; // черный шериф
						for (var a=0;a<r;a++){
							if (red[a]==this.id){
								ok= confirm ("Шериф добавлен - "+ $(this).find($(".Name")).text())
								if (ok){
									cop=this.id
									err =0;
									alert ("Выберите черную команду")	
								}
															
							}
						}
						if (err){
							alert("Шериф должен быть из красной команды")
						}

					}else if (b<4){
						var err=0; //повторно добавленный красный игрок
						for (var a=0;a<b;a++){
							if (black[a]==this.id){
								alert("Выбранный игрок уже добавлен");
								var err=1;
							}
						}
						for (var a=0;a<r;a++){
							if (red[a]==this.id){
								alert("Мафия не может быть в красной команде");
								var err=1;
							}
						}
						if (!err){
							ok=confirm("Добавлен "+ $(this).find($(".Name")).text())
							if (ok){
								black.push(this.id)
								b++
							}
							
							if (b==4){
								alert("Выберите дона")
							}	
						}
					}else if(!don){
						var err=1; // черный шериф
						for (var a=0;a<b;a++){
							if (black[a]==this.id){
								ok=confirm ("Дон добавлен - "+ $(this).find($(".Name")).text())
								if (ok){
									don=this.id
									err =0;
									win = confirm("Победившая команда - ок - крассные, cancel - черные")
									alert("Выберите MVP")
								}
								
							}
						}
						if (err){
							alert("Дон должен быть из черной команды")
						}
					}else if (!mvp){
						err=1
						if (win){
							for (var a=0;a<r;a++){
								if (this.id == red[a]){
									ok=confirm ("MVP добавлен - "+ $(this).find($(".Name")).text())
									if (ok){
										mvp=this.id
										err =0;
										add_game(red,cop,black,don,mvp,win,start)
										for (a=0;a<r;a++){
											delete (red[a])
										}
										for (a=0;a<b;a++){
											delete (black[a])
										}
										cop=0;
										don=0;
										r=1;
										b=1;
										start=2;
										//alert ("Игра успешно добавлена. Выберите красную команду или завершите сессию")
									}
								}
							}
						}else{
							for (var a=0;a<b;a++){
								if (this.id == black[a]){
									ok=confirm ("MVP добавлен - "+ $(this).find($(".Name")).text())
									if (ok){
										mvp=this.id
										err =0;
										add_game(red,cop,black,don,mvp,win,start)
										for (a=0;a<r;a++){
											delete (red[a])
										}
										for (a=0;a<b;a++){
											delete (black[a])
										}
										cop=0;
										don=0;
										r=1;
										b=1;
										start=2;
										//alert ("Игра успешно добавлена. Выберите красную команду или завершите сессию")
									}
								}
							}
						}
						if (err){
							alert("MVP не может быть из проигравшей команды")
						}
					}
				})
				
			
			
		})
 function add_game(red,cop,black,don,mvp,win,start){
 	$.ajax({
        url: "add_game.php",
        type: "POST",
        data:{
            red : red,
			cop : cop,
			black : black,
			don : don,
			mvp:mvp,
			win:win,
			start : start
        },
        success: function(data){
            alert("Игра успешно добавлена. Изменение рейтинга составляет "+data+". Выберите красную команду или завершите сессию")            
        }
    })
 }


	</script>
</body>