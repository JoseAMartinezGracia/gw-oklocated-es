
  
	<script type="text/javascript">
	
		var options = {
			title: 'Entradas y salidas en la última hora',
          hAxis: {
            format: 'dd/MM/yyyy HH:mm',
            gridlines: {color: 'none'},
          },
          vAxis: {
            gridlines: {color: 'none'},
			viewWindow: { min: 0 }
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
		  	var jsonData = $.ajax({
				  url: "/hour_json?from=2024-03-29 18:15:00",
				  cache: false,
				  dataType: "json",
				  async: false
			}).responseText;
			 
			//console.log(jsonData);
			
			var data = new google.visualization.DataTable(jsonData);
			var chart = new google.charts.Line(document.getElementById('chart_1'));
			chart.draw(data, google.charts.Line.convertOptions(options));	

			setTimeout(function () {
                loadChart1();
            }, 10000);
		}
		
		function loadChart2(){
		  	var jsonData = $.ajax({
				  url: "/day_json?from_hour=2024-03-29 00:00:00&to_hour=2024-03-29 19:14:59",
				  cache: false,
				  dataType: "json",
				  async: false
			}).responseText;
			 
			//console.log(jsonData);
			
			options['title'] = 'Entradas y salidas del día';
			
			var data = new google.visualization.DataTable(jsonData);
			var chart = new google.charts.Line(document.getElementById('chart_2'));
			chart.draw(data, google.charts.Line.convertOptions(options));

			setTimeout(function () {
                loadChart2();
            }, 60000);			

		}
		
		function loadChart3(){
		  	var jsonData = $.ajax({
				  url: "/day_json?from_hour=2024-03-29 00:00:00&to_hour=2024-03-29 19:14:59&type=2",
				  cache: false,
				  dataType: "json",
				  async: false
			}).responseText;
			 
			//console.log(jsonData);
			
			options['title'] = 'Ocupación del día';
			
			var data = new google.visualization.DataTable(jsonData);
			var chart = new google.charts.Line(document.getElementById('chart_3'));
			chart.draw(data, google.charts.Line.convertOptions(options));

			setTimeout(function () {
                loadChart3();
            }, 60000);			

		}
		
		
	  
	  
	  
    </script>
  
	<div class="card mb-3">
	  <div class="card-body">
		<p class="card-text">Ocupación Actual</p>
		<h5 class="card-title" id="ocupacion_actual">-</h5>		
	  </div>
	</div>
  
    <div class="mb-3" id="chart_1"></div>	
	<div class="mb-3" id="chart_2"></div>	
	<div class="mb-3" id="chart_3"></div>	
	
	
	<script type="text/javascript">
	$(function () {
	
		loadOcupación();
	
		function loadOcupación(){
			
			$.getJSON( "/day_json?from_hour=2024-03-29 00:00:00&to_hour=2024-03-29 19:14:59&type=3&t=" + Date.now(), function( json ) {
				console.log(json);
				$("#ocupacion_actual").text(json.ocupacion);	
			});
			
			setTimeout(function () {
                loadOcupación();
            }, 10000);			
		}
	});	
	</script>
	