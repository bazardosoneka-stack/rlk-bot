<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Dark Checker</title>
	<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="layout-styles.css">
	<script src="init.js?version=<?=rand(0,9000)?>"></script>

</head>
<body>
	<center>
	<br>
		<h1>[ DARK - CHECKER ] </h1>
		
		<img src="logo2.png" style="height:50px;">
		<br>

		<textarea id="ccs" placeholder="0000000000000000|00|0000|000" style="overflow:auto;text-align:center;color:#fff;background:transparent;width:20%;height:250px;resize:none;outline:0px;-webkit-appearance:none;border:outset 4px #ccc;"></textarea>
	
		<p id="info-div">
		<br>
			<span class="informacoes">Carregadas: <strong id="carregadasCount">0&nbsp</strong></span>
			<span class="informacoes">&nbspAprovadas: <strong id="aprovadasCount">0</strong></span>
			<span class="informacoes">&nbspReprovadas: <strong id="reprovadasCount">0&nbsp</strong></span>
			<span class="informacoes">&nbspTestadas: <strong id="testadasCount">0</strong></span>
		</p>
		

		<button class="btn btn-outline-primary" id="start">Iniciar</button>

		<div id="aprovadas">
			
		</div>
		<br>
		<div id="reprovadas">
			
		</div>
	</center>
</body>
</html>