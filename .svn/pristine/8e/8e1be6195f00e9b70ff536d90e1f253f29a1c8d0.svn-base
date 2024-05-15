
	<div class="input-group mb-3">
	  <span id="date_icon" class="input-group-text" id="basic-addon1"><i class="bi bi-calendar"></i></span>
	  <input id="date" type="text" class="form-control" placeholder="Selecciona mes" aria-describedby="basic-addon1" aria-label="Selección de mes" readonly>
	</div>
	
	<div class="container text-center mt-4 mb-4">
	  <div class="row">
		<div class="col-md-4 my-1">
		  <div class="card">			  
			  <div class="card-body">
				<h5 class="card-title">Entradas</h5>
				<h6 id="entradas" class="card-title"></h6>
			  </div>
			</div>
		</div>
		<div class="col-md-4 my-1">
		  <div class="card">			  
			  <div class="card-body">
				<h5 class="card-title">Diferencia mes anterior</h5>
				<h6 id="mes_anterior" class="card-title"></h6>
			  </div>
			</div>
		</div>
		<div class="col-md-4 my-1">
		  <div class="card">			  
			  <div class="card-body">
				<h5 class="card-title">Diferencia mismo mes año anterior</h5>
				<h6  id="anio_anterior" class="card-title"></h6>
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
	
	
	<div class="container text-center mt-4 mb-4">
	  <div class="row">
		<div class="col-md-4 my-1">
			<div id="chart_2_spinner" class="text-center">
			  <div class="spinner-border text-primary" role="status">
				<span class="visually-hidden">Cargando datos...</span>
			  </div>
			</div>
			<div class="mb-3" id="chart_2"  style="width: 100%; height: 400px;"></div>	
		</div>
		
		<div class="col-md-4 my-1">
		  
		</div>
	  </div>
	</div>
	
	<script>
	$(document).ready(function() {				
		$('#date').datepicker({			
			format: "MM yyyy",
			minViewMode: 1,
			language: "es",
			autoclose: true,			
		});						
		$('#date').datepicker("setDate", new Date());	
		$('#date').datepicker()
			.on('changeDate', function(e) {
				loadChart1();
				loadChart2();
				loadOverView()
		});		
		$("#date_icon").on( "click", function() {
			$('#date').datepicker('show');
		} );
		
		loadOverView();
		
		// Charts
		
		var options = {
			title: 'Entradas del mes',
          hAxis: {
            format: 'dd/MM/yyyy',
            gridlines: {color: 'none'},
          },
          vAxis: {
            gridlines: {color: 'none'},
			viewWindow: { min: 0 }
          }		  
        };

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'bar', 'line', 'corechart']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
		function drawChart() {		  
			loadChart1();
			loadChart2();
		}
	  
		
		function loadChart1(){
			$('#chart_1_spinner').removeClass("d-none");
			$('#chart_1').addClass("d-none");
		  	$.ajax({
				  url: "/month_json",
				  data: { month: $('#date').datepicker('getFormattedDate', 'mm'), year: $('#date').datepicker('getFormattedDate', 'yyyy') },
				  cache: false,
				  dataType: "json",
				  success: function (json) {
						$('#chart_1_spinner').addClass("d-none");
						$('#chart_1').removeClass("d-none");					    
						var data = new google.visualization.DataTable(json);
						var chart = new google.charts.Line(document.getElementById('chart_1'));
						chart.draw(data, google.charts.Line.convertOptions(options));
				  }				  
			});
							
		}
		
		function loadChart2(){
			$('#chart_2_spinner').removeClass("d-none");
			$('#chart_2').addClass("d-none");
			options['title'] = 'Entradas por día';
		  	$.ajax({
				  url: "/days_of_week_json",
				  data: { month: $('#date').datepicker('getFormattedDate', 'mm'), year: $('#date').datepicker('getFormattedDate', 'yyyy') },
				  cache: false,
				  dataType: "json",
				  success: function (json) {
						$('#chart_2_spinner').addClass("d-none");
						$('#chart_2').removeClass("d-none");					    						
						var data = new google.visualization.DataTable(json);
						var chart = new google.visualization.PieChart(document.getElementById('chart_2'));
						chart.draw(data, options);
						//var chart = new google.charts.Line(document.getElementById('chart_2'));
						//chart.draw(data, google.charts.Line.convertOptions(options));
				  }				  
			});
							
		}
		
		function loadOverView(){
			//console.log("/month_overview_json?month=" + $('#date').datepicker('getFormattedDate', 'mm') +"&year=" + $('#date').datepicker('getFormattedDate', 'yyyy'));
			
			$.ajax({
				  url: "/month_overview_json",				  
				  data: { month: $('#date').datepicker('getFormattedDate', 'mm'), year: $('#date').datepicker('getFormattedDate', 'yyyy') },
				  cache: false,
				  dataType: "json",
				  success: function (json) {
						$('#entradas').html(json["total"]);
						$('#mes_anterior').html(json["var-1-month"] + " (" + parseFloat(json["var-1-month-percent"]).toFixed(2) + "%)");
						$('#anio_anterior').html(json["var-1-year"] + " (" + parseFloat(json["var-1-year-percent"]).toFixed(2) + "%)");
						//$('#chart_1').removeClass("d-none");					    
						//console.log(json);						
				  }				  
			});
						
			
		}	
		
	});
	
	
	
	
	</script>
	
	