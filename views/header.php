<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OK Located - Contador de personas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<!-- bootstrap-datepicker -->
	<link id="bsdp-css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.standalone.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.es.min.js"></script>
	
  </head>
  <body>
    <div class="container">
		<div>Mostrando datos para: <?php echo getSession()->get(MAC_SEL); ?></div>
		<header class="d-flex justify-content-center py-3">
		  <ul class="nav nav-pills">
			<li class="nav-item"><a href="/" class="nav-link <?php if($ok_page_active == 'home') echo 'active'?>" <?php if($ok_page_active == 'home') echo 'aria-current="page"'?>>En tiempo real</a></li>
			<li class="nav-item"><a href="/select_day" class="nav-link <?php if($ok_page_active == 'dia') echo 'active'?>" <?php if($ok_page_active == 'dia') echo 'aria-current="page"'?>>Día</a></li>
			<li class="nav-item"><a href="/month" class="nav-link <?php if($ok_page_active == 'month') echo 'active'?>" <?php if($ok_page_active == 'month') echo 'aria-current="page"'?>>Mes</a></li>
			<li class="nav-item"><a href="/mac_sel" class="nav-link <?php if($ok_page_active == 'mac_sel') echo 'active'?>" <?php if($ok_page_active == 'mac_sel') echo 'aria-current="page"'?>>Dispositivo</a></li>
			<li class="nav-item"><a href="select_day" class="nav-link <?php if($ok_page_active == 'select_day') echo 'active'?>" <?php if($ok_page_active == 'select_day') echo 'aria-current="page"'?>>Día</a></li>
			<li class="nav-item dropdown">
			  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				Ejemplos
			  </a>
			  <ul class="dropdown-menu">
				<li><a class="dropdown-item" href="/ejemplo_tiempo_real">Tiempo real</a></li>
				<li><a class="dropdown-item" href="/ejemplo_mes">Mes</a></li>
			  </ul>
			</li>
		  </ul>
		</header>
		
		<div class="container">
	