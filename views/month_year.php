<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>	
  </head>

  <body>
  
	<script type="text/javascript">
	
		var options = {
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
				  url: "/month_year_json?month=<?php echo $month ?>&year=<?php echo $year ?>",
				  dataType: "json",
				  async: false
			  }).responseText;
			  
			//console.log(jsonData);
			
		   var data = new google.visualization.DataTable(jsonData);
		  var chart = new google.charts.Line(document.getElementById('columnchart_material'));
          chart.draw(data, google.charts.Line.convertOptions(options));	

			/*setTimeout(function () {
                loadChart1();
            }, 10000);*/
	  }
	  
	  
	  
    </script>
  
    <!--Div that will hold the pie chart-->
    <div id="columnchart_material"></div>	

	
	
  </body>
</html>