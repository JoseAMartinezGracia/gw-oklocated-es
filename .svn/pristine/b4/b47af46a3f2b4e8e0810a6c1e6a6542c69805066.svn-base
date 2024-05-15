
  
	<script type="text/javascript">
	
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
      google.charts.load('current', {'packages':['corechart', 'bar', 'line']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
		function drawChart() {		  
			loadChart1();
		}
	  
		function loadChart1(){
		  	var jsonData = $.ajax({
				  url: "/month_json?month=3&year=2024",
				  cache: false,
				  dataType: "json",
				  async: false
			}).responseText;
			 
			//console.log(jsonData);
			
			var data = new google.visualization.DataTable(jsonData);
			var chart = new google.charts.Line(document.getElementById('chart_1'));
			chart.draw(data, google.charts.Line.convertOptions(options));	

			
		}
		
		
		
		
	  
	  
	  
    </script>
  
	
  
    <div class="mb-3" id="chart_1"></div>	
	
	