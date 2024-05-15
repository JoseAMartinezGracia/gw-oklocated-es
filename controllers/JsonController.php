<?php
class JsonController
{
	static public function hour()
	{			
		include_once ABSPATH.'model/msr01b.php';
		
		$type = $_GET['type'] ?? 1;		
		
		if(isset($_GET['from'])){
			$from = date('Y-m-d H:i:s', strtotime($_GET['from']));
			$to = date('Y-m-d H:i:s', strtotime($_GET['from']) + 3600);
			$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<' => $to));
		}else{
			$from = date('Y-m-d H:i:s', time());
			$items = msr01bModel::get_items(array('timestamp_device>=DATE_SUB(NOW(), INTERVAL 1 HOUR)' => null, 'timestamp_device<=NOW()' => null));
		}
		
		$ocupacion = 0;
		foreach($items as $key=>$item){
			$ocupacion = $ocupacion + $item['entries'] - $item['exits'];
			$items[$key]['ocupacion'] = $ocupacion;
		}
		
		$json = array();
		
		if($type == 1){
			$json['cols'][] = array('label' => 'Fecha y hora', 'type' => 'datetime');
			$json['cols'][] = array('label' => 'Entradas', 'type' => 'number');
			$json['cols'][] = array('label' => 'Salidas', 'type' => 'number');
			
			if(count($items) > 0){
				foreach($items as $item){
					$json['rows'][]['c'] = [['v' => 'Date('.date("Y", $item['unix_timestamp']).','.(date("n", $item['unix_timestamp'])-1).','.date("j", $item['unix_timestamp']).','.date("G", $item['unix_timestamp']).','.(int)date("i", $item['unix_timestamp']).','.(int)date("s", $item['unix_timestamp']).')'],
											['v' => $item['entries']],
											['v' => $item['exits']]];			
				}
			}else{
				$json['rows'][]['c'] = [['v' => 'Date('.date("Y", strtotime($from)).','.(date("n", strtotime($from))-1).','.date("j", strtotime($from)).','.date("G", strtotime($from)).','.(int)date("i", strtotime($from)).','.(int)date("s", strtotime($from)).')'],
											['v' => 0],
											['v' => 0]];				
			}
		}else if($type == 2){
			$json['cols'][] = array('label' => 'Fecha y hora', 'type' => 'datetime');
			$json['cols'][] = array('label' => 'Ocupación', 'type' => 'number');
			
			if(count($items) > 0){
				foreach($items as $item){
					$json['rows'][]['c'] = [['v' => 'Date('.date("Y", $item['unix_timestamp']).','.(date("n", $item['unix_timestamp'])-1).','.date("j", $item['unix_timestamp']).','.date("G", $item['unix_timestamp']).','.(int)date("i", $item['unix_timestamp']).','.(int)date("s", $item['unix_timestamp']).')'],
											['v' => $item['ocupacion']]];
				}
			}else{
				$json['rows'][]['c'] = [['v' => 'Date('.date("Y", strtotime($from)).','.(date("n", strtotime($from))-1).','.date("j", strtotime($from)).','.date("G", strtotime($from)).','.(int)date("i", strtotime($from)).','.(int)date("s", strtotime($from)).')'],
											['v' => 0]];
			}
		}
		
		echo json_encode($json);
	
	}
	
	
	static public function day()
	{			
		include_once ABSPATH.'model/msr01b.php';
		
		$type = $_GET['type'] ?? 1;
		
		if(isset($_GET['day'])){ //url.php?day=2024-03-15
			// formato YYYY-mm-dd
			$from = $_GET['day']." 00:00:00";
			$to = $_GET['day']." 23:59:59";
			$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<=' => $to));
		}elseif(isset($_GET['from_hour']) AND isset($_GET['to_hour'])){ //url.php?from=2024-03-15 19:00:00&to=2024-03-17 20:00:00
			$from = $_GET['from_hour'];
			$to = $_GET['to_hour'];
			$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<=' => $to));
		}elseif(isset($_GET['from']) AND isset($_GET['to'])){ //url.php?from=2024-03-15&to=2024-03-17
			$from = $_GET['from']." 00:00:00";
			$to = $_GET['to']." 23:59:59";
			$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<=' => $to));
		}else{
			$from = date('Y-m-d')." 00:00:00";			
			$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<=NOW()' => null));						
		}
		
		
		$minutes = array();
		
		
		$entradas = 0;
		$salidas = 0;
		$ocupacion = 0;
		
		foreach($items as $key=>$item){
			$minute = date('Y-m-d H:i', $item['unix_timestamp']);
			if(!isset($minutes[$minute])){
				$minutes[$minute]['entries'] = 0;
				$minutes[$minute]['exits'] = 0;
			}
			$minutes[$minute]['entries'] += $item['entries'];
			$minutes[$minute]['exits'] += $item['exits'];
			$minutes[$minute]['unix_timestamp'] = $item['unix_timestamp'];
						
			$ocupacion = $ocupacion + $item['entries'] - $item['exits'];
			$minutes[$minute]['ocupacion'] = $ocupacion;
			
			$entradas += $item['entries'];
			$salidas += $item['exits'];
		}
				
		$json = array();
		
		if($type == 1){
			$json['cols'][] = array('label' => 'Fecha y hora', 'type' => 'datetime');
			$json['cols'][] = array('label' => 'Entradas', 'type' => 'number');
			$json['cols'][] = array('label' => 'Salidas', 'type' => 'number');
			
			
			if(count($items) > 0){
				foreach($minutes as $minute){
					$json['rows'][]['c'] = [['v' => 'Date('.date("Y", $minute['unix_timestamp']).','.(date("n", $minute['unix_timestamp'])-1).','.date("j", $minute['unix_timestamp']).','.date("G", $minute['unix_timestamp']).','.(int)date("i", $minute['unix_timestamp']).')'],
											['v' => $minute['entries']],
											['v' => $minute['exits']]];			
				}
			}else{
				$json['rows'][]['c'] = [['v' => 'Date('.date("Y", strtotime($from)).','.(date("n", strtotime($from))-1).','.date("j", strtotime($from)).','.date("G", strtotime($from)).','.(int)date("i", strtotime($from)).')'],
											['v' => 0],
											['v' => 0]];						
			}
		}else if($type == 2){
			$json['cols'][] = array('label' => 'Fecha y hora', 'type' => 'datetime');
			$json['cols'][] = array('label' => 'Ocupación', 'type' => 'number');
			
			if(count($items) > 0){
				foreach($minutes as $minute){
					$json['rows'][]['c'] = [['v' => 'Date('.date("Y", $minute['unix_timestamp']).','.(date("n", $minute['unix_timestamp'])-1).','.date("j", $minute['unix_timestamp']).','.date("G", $minute['unix_timestamp']).','.(int)date("i", $minute['unix_timestamp']).')'],
											['v' => $minute['ocupacion']]];
				}
			}else{
				$json['rows'][]['c'] = [['v' => 'Date('.date("Y", strtotime($from)).','.(date("n", strtotime($from))-1).','.date("j",strtotime($from)).','.date("G", strtotime($from)).','.(int)date("i", strtotime($from)).')'],
											['v' => 0]];
			}
		}else if($type == 3){
			if(count($items) > 0){
				$json['entradas'] = $entradas;
				$json['salidas'] = $salidas;
				$json['ocupacion'] = $ocupacion;			
			}else{				
				$json['entradas'] = 0;
				$json['salidas'] = 0;
				$json['ocupacion'] = 0;
			}
		}
		
		echo json_encode($json);
	
	}
	
	
	// Devuelve el número de entradas por días
	// Si recibe month/year o from/to los datos se obtienen de la tabla days
	// Si no recibe patámetros se obtienen los valores del mes en curso desde la tabla status
	
	static public function month()
	{			
		include_once ABSPATH.'model/msr01b.php';
		
		$type = $_GET['type'] ?? 1;		
		
		if(isset($_GET['month']) AND isset($_GET['year'])){			
			$from = date('Y-m-d', strtotime($_GET['year']."-".str_pad($_GET['month'], 2, "0", STR_PAD_LEFT)."-01"));
			$to = date('Y-m-d', strtotime("+1 month ".$from));
			$days = msr01bModel::get_days(array('date>=' => $from, 'date<' => $to));
			//$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<' => $to));			
		}elseif(isset($_GET['from']) AND isset($_GET['to'])){
			// formato YYYY-mm-dd
			$from = $_GET['from'];
			$to = $_GET['to'];
			$days = msr01bModel::get_days(array('date>=' => $from, 'date<=' => $to));
			//$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<=' => $to));
		}else{		
			$from = date('Y-m')."-01 00:00:00";
			$items = msr01bModel::get_items(array('timestamp_device>=' => $from, 'timestamp_device<=NOW()' => null));
		
			$days = array();
		
			$entradas = 0;
			$salidas = 0;
			$ocupacion = 0;
			
			foreach($items as $key=>$item){
				$day = date('Y-m-d', $item['unix_timestamp']);
				if(!isset($days[$day])){
					$days[$day]['entries'] = 0;
					$days[$day]['exits'] = 0;
				}
				$days[$day]['entries'] += $item['entries'];
				$days[$day]['exits'] += $item['exits'];
				$days[$day]['unix_timestamp'] = $item['unix_timestamp'];
				
				$entradas += $item['entries'];			
				$salidas += $item['exits'];
				$ocupacion = $ocupacion + $item['entries'] - $item['exits'];
				$days[$day]['ocupacion'] = $ocupacion;
			}
		}
	
				
		$json = array();
		
		if($type == 1){
			$json['cols'][] = array('label' => 'Fecha', 'type' => 'date');
			$json['cols'][] = array('label' => 'Entradas', 'type' => 'number');
			//$json['cols'][] = array('label' => 'Salidas', 'type' => 'number');
			
			if(count($days) > 0){
				foreach($days as $day){
					$json['rows'][]['c'] = [['v' => 'Date('.date("Y", $day['unix_timestamp']).','.(date("n", $day['unix_timestamp'])-1).','.date("j", $day['unix_timestamp']).')'],
											['v' => $day['entries']],
											/*['v' => $day['exits']]*/];			
				}
			}else{
				$json['rows'][]['c'] = [['v' => 'Date('.date("Y", strtotime($from)).','.(date("n", strtotime($from))-1).','.date("j", strtotime($from)).')'],
											['v' => 0],
											/*['v' => 0]*/];	
			}
		}
		
		echo json_encode($json);	
	}
	
	
	
	// Devuelve un resumen de entradas del mes (o periodo concreto)
	// Si recibe month/year o from/to los datos se obtienen de la tabla days
	
	static public function month_overview()
	{			
		include_once ABSPATH.'model/msr01b.php';
		
		$params = array();
		if(isset($_GET['month']) AND isset($_GET['year'])){
			$month = (int) $_GET['month'];
			$year = (int) $_GET['year'];
			$entries = msr01bModel::sum_days(array('year(date)=' => $year, 'month(date)=' => $month));			
			$params['total'] = $entries['entries'] != NULL ? $entries['entries'] : 0;			
			
			$entries = msr01bModel::sum_days(array('year(date)=' => $year-1, 'month(date)=' => $month));
			$params['total-1-year'] = $entries['entries'] != null ? $entries['entries'] : 0;
			
			// Mes anterior
			if($month == 1){
				$month = 13;
				$year = $year-1;
			}
			$entries = msr01bModel::sum_days(array('year(date)=' => $year, 'month(date)=' => $month-1));
			$params['total-1-month'] = $entries['entries'] ? $entries['entries'] : 0;		
			
			
			$params['var-1-year'] = $params['total'] - $params['total-1-year'];
			if($params['total-1-year'] != 0)
				$params['var-1-year-percent'] = $params['var-1-year'] / $params['total-1-year'] * 100;
			else
				$params['var-1-year-percent'] = 0;
			
			$params['var-1-month'] = $params['total'] - $params['total-1-month'];
			if($params['total-1-month'] != 0)
				$params['var-1-month-percent'] = $params['var-1-month'] / $params['total-1-month'] * 100;
			else
				$params['var-1-month-percent'] = 0;
				
			
			/*
			// Calculamos la distribución por días de la semana
			$days = msr01bModel::get_days(array('year(date)=' => $year, 'month(date)=' => $month));
			$week_day = date('N', strtotime($year."-".str_pad($month, 2, "0", STR_PAD_LEFT)."-01"));
			$week_days = array();
			foreach($days as $day)
			*/
		}
		
		//var_dump($params);
		echo json_encode($params);			
	}
	
	
	// Datos de gráfica de porciones según el reparto de los días de la semana de un periodo dado
	
	static public function days_of_week()
	{			
		include_once ABSPATH.'model/msr01b.php';
		
		
		if(isset($_GET['month']) AND isset($_GET['year'])){			
			$month = (int) $_GET['month'];
			$year = (int) $_GET['year'];
			$days = msr01bModel::get_days((array('year(date)=' => $year, 'month(date)=' => $month)));
		}elseif(isset($_GET['from']) AND isset($_GET['to'])){
			// formato YYYY-mm-dd
			$from = $_GET['from'];
			$to = $_GET['to'];
			$days = msr01bModel::get_days(array('date>=' => $from, 'date<=' => $to));
		}
				
		$json = array();
		
		$json['cols'][] = array('label' => 'Día de la semana', 'type' => 'string');
		$json['cols'][] = array('label' => 'Entradas', 'type' => 'number');
		
		$week_days = array("1" => 0, "2" => 0, "3" => 0, "4" => 0, "5" => 0, "6" => 0, "7" => 0);		
		foreach($days as $day){
			$week_days[date("N", $day['unix_timestamp'])] += $day['entries'];
		}		
		foreach($week_days as $day_index => $week_day){
			$json['rows'][]['c'] = [['v' => self::day_number_to_tex($day_index)],
									['v' => $week_day],
									];			
		}		
		echo json_encode($json);	
	}
	
	static private function day_number_to_tex($number){
		$str = "";
		if($number == 1)
			$str = "Lunes";
		else if($number == 2)
			$str = "Martes";
		else if($number == 3)
			$str = "Miércoles";
		else if($number == 4)
			$str = "Jueves";
		else if($number == 5)
			$str = "Viernes";
		else if($number == 6)
			$str = "Sábado";
		else if($number == 7)
			$str = "Domingo";
		return $str;
	}
	
	
}
?>