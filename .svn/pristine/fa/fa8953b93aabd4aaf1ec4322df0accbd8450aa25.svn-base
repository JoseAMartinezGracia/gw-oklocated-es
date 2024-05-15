<?php
class CronController
{
	static public function cron()
	{	
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		
		// $id = 0 es el valor por defecto y solo ejecuta cada tarea en función de su programación
		// $id = -1 fuerza la realización de TODAS las tareas
		// $id = n fuerza la realización de una tarea concreta
			
		//mail("g.diez@icongrafico.com", time(),"Estos es una prueba: ".time());
	
		// Protegemos la ejecución mediante token
		if(isset($_GET['token']) AND $_GET['token'] == 'fFDfSOV4Alz4qtS68Gt8yCwBo72XVdJ3'){
			// Todas los minutos - limpieza de datos duplicados	
			if($id == -1 OR $id == 1){ 				
				Msr01bController::clean_data();
			}
			// Cada 2 minutos - suma de entradas del día
			if($id == -1 OR $id == 2 OR (date("i") % 2) == 0){ 
				self::cron_day(date('Y-m-d'));
			}
			// A las 00:05 - suma de entradas del día anterior
			if($id == -1 OR $id == 3 OR (date("i") == "05" AND date("H") == "00")){ 
				self::cron_day(date('Y-m-d', time()-86400));
			}
		}
		
		
		/*if($id == 1){
		
			$year = "2024";
			$mes = "4";
			for($day=1; $day<=9 ;$day++){
				echo "2023-".$mes."-".str_pad($day, 2, "0", STR_PAD_LEFT);
				self::cron_day($year."-".$mes."-".str_pad($day, 2, "0", STR_PAD_LEFT));
			}
		}*/
	}
	
		
	
	
	// Por cada uno de los dipositivos
	// suma todas las entradas y salidas de un día
	// y los guarda en la tabla resumen de días
	static public function cron_day($date)
	{	
		include_once ABSPATH.'model/msr01b.php';
		
		//$date = date('Y-m-d');
		//$date = date('2023-03-01');
				
		$macs = msr01bModel::get_macs();
		foreach($macs as $mac){
			$items = msr01bModel::sum_items(array('timestamp_device>=' => $date.' 00:00:00', 'timestamp_device<=' => $date.' 23:59:59', 'mac=' => $mac['mac']));
			if($items['entries'] != null AND $items['exits'] != null){
				//var_dump($items);
				msr01bModel::insertUpdateDay(array('iddevice' => $mac['id'], 'date' => $date, 'entries' => $items['entries'], 'exits' => $items['exits']));
				
			}
		}
		
	}
	
	static public function insert(){
		for($day=1; $day<=31 ;$day++){
			$time = mktime(0, 0, 0, 1, $day, 2023);
			$values = array('AC233FC0355E', 'C2000015E9D1', 'Minew MSR01-B', date("Y-m-d H:i:s", $time), 0, 0, 0);			
			for($i=0; $i<=8639 ;$i++){			
				$insertedRow = getDatabase()->execute('INSERT INTO '.TABLE_STATUS.' (gateway_mac, mac, type, timestamp_device, serial, entries, exits) VALUES (?, ?, ?, ?, ?, ?, ?)', $values);
				$time +=10;
				$values[3] = date("Y-m-d H:i:s", $time);
				$values[4] +=1;
				if($values[4] == 256)
					$values[4] = 1;
				if(rand(0, 5) == 0){
					if(date("H", $time) < 8 OR date("H", $time) > 20){
						$values[5] = 0;
						$values[6] = 0;
					}else if(date("H", $time) == "11" OR date("H", $time) == "14" OR date("H", $time) == "17"){
						if(date("D", $time) == "Sat" OR date("D", $time) == "Sun"){
							$values[5] = rand(0, 16);
							$values[6] = rand(0, 16);
						}else{
							$values[5] = rand(0, 8);
							$values[6] = rand(0, 8);
						}
					}else{
						if(date("D", $time) == "Sat" OR date("D", $time) == "Sun"){
							$values[5] = rand(0, 10);
							$values[6] = rand(0, 10);
						}else{
							$values[5] = rand(0, 5);
							$values[6] = rand(0, 5);
						}
					}						
				}else{
					$values[5] = 0;
					$values[6] = 0;
				}
			}
			
		}
	}
	
}
?>