<head>
	<title>Рейтинг</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css.css">
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
</head>
<body>
	<div id=table>
		<p class="header">Рейтинг клуба мафии</p>
		<div class="player">
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
				$i++;
				if ($player['Num_games']>4){
				?>
					<div class="player" id=<?=$player['id']?>>
						<p style="width: 45px"><?=$i?></p>
						<p style="width: 155px"><?=$player['Nick']?></p>
						<p style="width: 65px"><?=$player['Rating']?></p>
						<p style="width: 55px"><?=$player['Rating']-$player['Prev_rating']?></p>
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