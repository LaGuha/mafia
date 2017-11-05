
<? session_start();?>
<head>
	<title>Рейтинг</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css.css">
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
	<? 
		if (!isset($_SESSION['admin'])){

	}
	?>
		<p style="text-decoration: underline; color: blue" id=delta>Обнулить Дельта</p>
	<form method="POST" id=game action="add_game.php">
		<div>
			<p>Красная команда</p>
			<input type="hidden" value=1 name=start>
			<input name=red1 id=nick1 class="nick">
			<input name=red2 id=nick2 class="nick">
			<input name=red3 id=nick3 class="nick">
			<input name=red4 id=nick4 class="nick">
			<input name=red5 id=nick5 class="nick">
			<input name=red6 id=nick6 class="nick">
		</div>
		<div>
			<p>Шериф</p>
			<input name=red7 id=nick7 class="nick">
		</div>
		<div>
			<p>Мафия</p>
			<input name=black1 id=nick8 class="nick">
			<input name=black2 id=nick9 class="nick">
		</div>
		<div>
			<p style="width:100px;">Дон</p>
			<input name=black3 id=nick10 class="nick"> 
		</div>
		<div>
			<p style="width:100px;">MVP</p>
			<input name=mvp id=nick11 class="nick">
			<p style="width: 100%">Красные <input type=radio name=win value=true>
			Черные <input type=radio name=win value=false></p>
			<p>Ведущий:</p> <p><input  name="Lead"></p>
			<p style="width: 100%">Дата:</p> <input type="date" name="data">
			<p style="width: 100%">Номер игры:</p> <input name="Num">

			<p style="width: 100%">&nbsp;</p>
			<button type=submit>Сохранить</button>
		</div>
	</form>

<script>

$('#delta').click(function(){
	$.ajax({
        url: "delta.php",
        type: "POST",
        async:false,
        data:{
        	start:1
        },
        success: function(data){
        	alert(data)

        }
    })

})

var arr= new Array()
$.ajax({
        url: "nicks.php",
        type: "POST",
        async:false,
        data:{
        },
        success: function(data){
        	d=JSON.parse(data)
        	arr=d.Nick

        }
    })
console.log(arr[0])
  $( function() {
    $( "#nick1" ).autocomplete({
      source: arr
    });
     $( "#nick2" ).autocomplete({
      source: arr
    });
      $( "#nick3" ).autocomplete({
      source: arr
    });
       $( "#nick4" ).autocomplete({
      source: arr
    });
        $( "#nick5" ).autocomplete({
      source: arr
    });
         $( "#nick6" ).autocomplete({
      source: arr
    });
          $( "#nick7" ).autocomplete({
      source: arr
    });
           $( "#nick8" ).autocomplete({
      source: arr
    });
            $( "#nick9" ).autocomplete({
      source: arr
    });
             $( "#nick10" ).autocomplete({
      source: arr
    });
             $( "#nick11" ).autocomplete({
      source: arr
    });
  } );

  </script>