<style>
        #chart_2, #chart_3, #chart_4 {
    width: 100%;
    height: 400px;
}

        .loading-message {
            display: none;
            text-align: center;
            font-style: italic;
        }

        .containerDate {
            text-align: center;
        }

        .dates {
            display: inline-block;
            text-align: center;
            box-sizing: content-box;
            margin-top: 20px;
            border: 1px solid black;
            padding: 10px;
            background-color: #f0f0f0;
        }
    </style>
     <div class="dates containerDate">
        <input type="date" id="start_date" class="start_date">
        <input type="time" id="start_time" class="start_time">
        <input type="date" id="end_date" class="end_date">
        <input type="time" id="end_time" class="end_time">
    </div>

    <div id="chart_2"></div>
    <div id="chart_3"></div>
    <div id="chart_4"></div>
    <div id="loading_message_chart_4" class="loading-message">Cargando...</div>

    <div id="loading_message_chart_2" class="loading-message">Cargando...</div>
    <div id="loading_message_chart_3" class="loading-message">Cargando...</div>

    <script type="text/javascript">
    var options = {
        title: 'Entradas y salidas por hora del día',
        hAxis: {
            title: 'Hora',
            format: 'HH:mm',
        },
        vAxis: {
            title: '',
            gridlines: { color: 'grey' },
            viewWindow: { min: 0 }
        }
    };

    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(inicializar);

    function inicializar() {
        var currentDate = new Date();
        var formattedDate = currentDate.toISOString().split('T')[0];
        var formattedTime = ("0" + currentDate.getHours()).slice(-2) + ":" + ("0" + currentDate.getMinutes()).slice(-2);

        $("#start_date, #end_date").val(formattedDate);
        $("#start_time, #end_time").val(formattedTime);

        $("#start_date, #start_time, #end_date, #end_time").on("change", function () {
            var startDate = $("#start_date").val();
            var startTime = $("#start_time").val();
            var endDate = $("#end_date").val();
            var endTime = $("#end_time").val();
            drawChart(startDate, startTime, endDate, endTime);
        });

        drawChart(formattedDate, formattedTime, formattedDate, formattedTime);
    }

    function drawChart(startDate, startTime, endDate, endTime) {
        showLoadingMessage('2');
        showLoadingMessage('3');
        showLoadingMessage('4');

        loadChart2(startDate, startTime, endDate, endTime);
        loadChart3(startDate, startTime, endDate, endTime);
        loadChart4(startDate, startTime, endDate, endTime);
    }

    function loadChart2(startDate, startTime, endDate, endTime) {
        var fromHour = startDate + " " + startTime;
        var toHour = endDate + " " + endTime;
        $.ajax({
            url: "/day_json?from_hour=" + fromHour + "&to_hour=" + toHour,
            cache: false,
            dataType: "json",
            beforeSend: function () {
                showLoadingMessage('2');
            },
            complete: function () {
                hideLoadingMessage('2');
            },
            success: function (jsonData) {
                var data = new google.visualization.DataTable(jsonData);
                var chart = new google.visualization.LineChart(document.getElementById('chart_2'));
                var groupedData = groupDataByHour(data);
                options.title = 'Entradas y salidas por hora del periodo';
                chart.draw(groupedData, options);
            },
            error: function () {
                console.error("Error al cargar los datos para el gráfico 2");
                hideLoadingMessage('2');
            }
        });
    }

    function loadChart3(startDate, startTime, endDate, endTime) {
        var fromHour = startDate + " " + startTime;
        var toHour = endDate + " " + endTime;
        $.ajax({
            url: "/day_json?from_hour=" + fromHour + "&to_hour=" + toHour + "&type=2",
            cache: false,
            dataType: "json",
            beforeSend: function () {
                showLoadingMessage('3');
            },
            complete: function () {
                hideLoadingMessage('3');
            },
            success: function (jsonData) {
                var data = new google.visualization.DataTable(jsonData);
                var chart = new google.visualization.LineChart(document.getElementById('chart_3'));
                options.title = 'Ocupación del periodo';
                chart.draw(data, options);
            },
            error: function () {
                console.error("Error al cargar los datos para el gráfico 3");
                hideLoadingMessage('3');
            }
        });
    }

    function loadChart4(startDate, startTime, endDate, endTime) {
        var fromHour = startDate + " " + startTime;
        var toHour = endDate + " " + endTime;
        $.ajax({
            url: "/day_json?from_hour=" + fromHour + "&to_hour=" + toHour,
            cache: false,
            dataType: "json",
            beforeSend: function () {
                showLoadingMessage('4');
            },
            complete: function () {
                hideLoadingMessage('4');
            },
            success: function (jsonData) {
                var data = new google.visualization.DataTable(jsonData);
                var chart = new google.visualization.PieChart(document.getElementById('chart_4'));
                var groupedData = groupDataByDay(data);
                options.title = 'Entradas por día de la semana';
                chart.draw(groupedData, options);
            },
            error: function () {
                console.error("Error al cargar los datos para el gráfico 4");
                hideLoadingMessage('4');
            }
        });
    }

    function groupDataByHour(data) {
        var groupedData = {};
        for (var i = 0; i < data.getNumberOfRows(); i++) {
            var hour = data.getValue(i, 0).getHours();
            var entries = parseFloat(data.getValue(i, 1));
            var exits = parseFloat(data.getValue(i, 2));

            if (!groupedData[hour]) {
                groupedData[hour] = { entries: 0, exits: 0 };
            }

            groupedData[hour].entries += entries;
            groupedData[hour].exits += exits;
        }

        var resultData = new google.visualization.DataTable();
        resultData.addColumn('timeofday', 'Hora');
        resultData.addColumn('number', 'Entradas');
        resultData.addColumn('number', 'Salidas');

        var sortedHours = Object.keys(groupedData).map(Number).sort((a, b) => a - b);

        sortedHours.forEach(hour => {
            resultData.addRow([[hour, 0, 0], groupedData[hour].entries, groupedData[hour].exits]);
        });

        return resultData;
    }

    function groupDataByDay(data) {
        var groupedData = {};
        for (var i = 0; i < data.getNumberOfRows(); i++) {
            var date = data.getValue(i, 0);
            var day = date.getDay(); // 0 (Sunday) to 6 (Saturday)
            var entries = parseFloat(data.getValue(i, 1));

            if (!groupedData[day]) {
                groupedData[day] = { entries: 0 };
            }

            groupedData[day].entries += entries;
        }

        var resultData = new google.visualization.DataTable();
        resultData.addColumn('string', 'Día de la semana');
        resultData.addColumn('number', 'Entradas');

        var daysOfWeek = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        Object.keys(groupedData).forEach(day => {
            resultData.addRow([daysOfWeek[day], groupedData[day].entries]);
        });

        return resultData;
    }

    function showLoadingMessage(chartId) {
        $("#loading_message_chart_" + chartId).show();
    }

    function hideLoadingMessage(chartId) {
        $("#loading_message_chart_" + chartId).hide();
    }
</script>