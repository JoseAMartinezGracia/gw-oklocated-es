<?php
class Msr01bController
{
	static public function clean_data()
	{	
		include_once ABSPATH.'model/msr01b.php';
		
		$macs = msr01bModel::get_macs();
		
		foreach($macs as $mac){
			echo "<p><b>Limpiando: ".$mac['mac']."</b></p>";
			
			msr01bModel::delete_items(array(['mac', 'mac=:mac', $mac['mac']], ['type', 'type=:type', 'Unknown']));
			
			
			$items = msr01bModel::get_items(array("mac=" => $mac['mac'], "timestamp_device>=DATE_SUB(NOW(), INTERVAL 24 HOUR)" => null));	
	
			$last_item = null;
			$ids = array();
			
			foreach($items as $item){
				//var_dump($item);
				if($last_item != null AND $last_item['serial'] == $item['serial'] AND $last_item['entries'] == $item['entries'] AND $last_item['exits'] == $item['exits']){
					//echo "<p>repetido: ".$item['id']."</p>";
					$ids[] = $last_item['id'];
				}
				$last_item = $item;
			}
			
			
			//var_dump($ids);
			
			
			if(count($ids) > 0){
				for($i=0; $i<=count($ids); $i += 100){				
					$slice = array_slice($ids, $i, 100);				
					echo "<p>Eliminando: ".count($slice)." registros</p>";
					msr01bModel::delete_items(array([null, 'id IN ('.join(', ', $slice).')', null]));						
				}
				
			}
		}
		
		
		
		
	}
	
	
	
	
	
	
}
?>