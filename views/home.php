
	<div class="container text-center mt-4 mb-4">
	  <div class="row">
		<div class="col-md-4 my-1">
		  <div class="card">			  
			  <div class="card-body">
				<h5 class="card-title">Entradas</h5>
				<h6 id="entradas" class="card-title">-</h6>
			  </div>
			</div>
		</div>
		<div class="col-md-4 my-1">
		  <div class="card">			  
			  <div class="card-body">
				<h5 class="card-title">Salidas</h5>
				<h6 id="salidas" class="card-title">-</h6>
			  </div>
			</div>
		</div>
		<div class="col-md-4 my-1">
		  <div class="card">			  
			  <div class="card-body">
				<h5 class="card-title">Ocupación actual</h5>
				<h6 id="ocupacion" class="card-title">-</h6>
			  </div>
			</div>
		</div>
	  </div>
	</div>
	
	
	<div id="chart_1_spinner" class="text-center">
	  <div class="spinner-border text-primary" role="status">
		<span class="visually-hidden">Cargando datos...</span>
	  </div>
	</div>
    <div class="mb-3" id="chart_1"></div>
    
    <div id="chart_2_spinner" class="text-center">
	  <div class="spinner-border text-primary" role="status">
		<span class="visually-hidden">Cargando datos...</span>
	  </div>
	</div>
	<div class="mb-3" id="chart_2"></div>	
	
	<div id="chart_3_spinner" class="text-center">
	  <div class="spinner-border text-primary" role="status">
		<span class="visually-hidden">Cargando datos...</span>
	  </div>
	</div>
	<div class="mb-3" id="chart_3"></div>	
	
	
	<script type="text/javascript">
	$(document).ready(function() {		
	
		loadOcupacion();
		
		
		var options = {
			title: 'Entradas y salidas en la última hora',
          hAxis: {
            format: 'dd/MM/yyyy HH:mm',
            gridlines: {color: 'none'},
          },
          vAxis: {
            gridlines: {color: 'none'},
			//viewWindow: { min: -1 }
          }		  
        };

		

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'bar', 'line']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
		function drawChart() {		  
			loadChart1();
			loadChart2();			 
			loadChart3();
		}
	  
		function loadChart1(){
			$('#chart_1_spinner').addClass("visible");
			$('#chart_1_spinner').removeClass("invisible");
			$('#chart_1').addClass("invisible");
			$('#chart_1').removeClass("visible");
						
			$.ajax({
				  url: "/hour_json",
				  cache: false,
				  dataType: "json",
				  success: function (json) {
						$('#chart_1').addClass("visible");
						$('#chart_1').removeClass("invisible");
						$('#chart_1_spinner').removeClass("visible");
						$('#chart_1_spinner').addClass("invisible");
						options['title'] = 'Entradas y salidas en la última hora';
						var data = new google.visualization.DataTable(json);
						var chart = new google.charts.Line(document.getElementById('chart_1'));
						chart.draw(data, google.charts.Line.convertOptions(options));	
				  }				  
			});
			
			setTimeout(function () {
                loadChart1();
            }, 15000);			
		}
		
		function loadChart2(){
			$('#chart_2_spinner').addClass("visible");
			$('#chart_2_spinner').removeClass("invisible");
			$('#chart_2').addClass("invisible");
			$('#chart_2').removeClass("visible");
			
			$.ajax({
				  url: "/day_json",
				  cache: false,
				  dataType: "json",
				  success: function (json) {
						$('#chart_2').addClass("visible");
						$('#chart_2').removeClass("invisible");
						$('#chart_2_spinner').removeClass("visible");
						$('#chart_2_spinner').addClass("invisible");
						options['title'] = 'Entradas y salidas del día';
						var data = new google.visualization.DataTable(json);
						var chart = new google.charts.Line(document.getElementById('chart_2'));
						chart.draw(data, google.charts.Line.convertOptions(options));
				  }				  
			});
			
			setTimeout(function () {
                loadChart2();
            }, 15000);		
			

		}
		
		function loadChart3(){
			$('#chart_3_spinner').addClass("visible");
			$('#chart_3_spinner').removeClass("invisible");
			$('#chart_3').addClass("invisible");
			$('#chart_3').removeClass("visible");
			$.ajax({
				  url: "/day_json",
				  data: { type: 2 },
				  cache: false,
				  dataType: "json",
				  success: function (json) {
						$('#chart_3').addClass("visible");
						$('#chart_3').removeClass("invisible");
						$('#chart_3_spinner').removeClass("visible");
						$('#chart_3_spinner').addClass("invisible");
						options['title'] = 'Ocupación del día';
						var data = new google.visualization.DataTable(json);
						var chart = new google.charts.Line(document.getElementById('chart_3'));
						chart.draw(data, google.charts.Line.convertOptions(options));
				  }				  
			});
			
			setTimeout(function () {
                loadChart3();
            }, 15000);			
		}
		
	
		function loadOcupacion(){
			
			$.getJSON( "/day_json?type=3&t=" + Date.now(), function( json ) {
				//console.log(json);
				$("#ocupacion").text(json.ocupacion);
				$("#entradas").text(json.entradas);
				$("#salidas").text(json.salidas);
				$("#ocupacion").text(json.ocupacion);
			});
			
			setTimeout(function () {
                loadOcupacion();
            }, 10000);			
		}
	});	
	</script>
	