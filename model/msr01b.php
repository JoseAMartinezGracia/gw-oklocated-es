<?php
class msr01bModel
{		
	static public function get_items($where = null)
	{						
		// Lista de entradas
		
		$sqlArray = array();
        $paramArray = array();		
		
		// Añadimos mac guardad en la sesión (temporal, se mejorará)
		
		if(getSession()->get(MAC_SEL) != false){		
			$sqlArray[] = "mac=?";
			$paramArray[] = getSession()->get(MAC_SEL);
		}
				
		
		foreach($where as $key=>$value){			
			if($value != null){
				$sqlArray[] = $key."?";
				$paramArray[] = $value;				
			}else
				$sqlArray[] = $key;
		}									
		
		$sql = 'SELECT id, mac, timestamp_device, UNIX_TIMESTAMP(timestamp_device) as unix_timestamp, serial, entries, exits FROM '.TABLE_STATUS. 
									(count($sqlArray)>0 ? ' WHERE '.join(' AND ', $sqlArray) : '').' 
									ORDER BY timestamp_device ASC';


		//echo $sql;
		
		
		
		$items = getDatabase()->all($sql, $paramArray);
		
		return $items;		
	}
	
	
	
	static public function delete_items($where)
	{		
		$sql = array();
        $paramArray = array();
		
		
		foreach($where as $params){
			$sql[] = $params[1];
			if($params[0] != null)
				$paramArray[":".$params[0]] = $params[2];
		}	
		
		$string_sql = 'DELETE FROM '.TABLE_STATUS.' WHERE '.join(' AND ', $sql);
		//echo $string_sql;
			
		$items = getDatabase()->execute($string_sql, $paramArray);
		//return $items;
		
	}
	
	
	static public function get_macs()
	{						
		// Lista de macs en la BD
		
		//$sql = "SELECT distinct(mac) mac FROM ".TABLE_STATUS." WHERE type='Minew MSR01-B' ORDER BY mac asc";
		$sql = "SELECT * FROM ".TABLE_DEVICES." ORDER BY mac asc";
		
		$items = getDatabase()->all($sql, array());
		
		return $items;		
	}
	
	
	static public function sum_items($where = null)
	{						
		// Lista de entradas
		
		$sqlArray = array();
        $paramArray = array();		
		
		// Añadimos mac guardad en la sesión (temporal, se mejorará)
		/*$sqlArray[] = "mac=?";
		$paramArray[] = getSession()->get(MAC_SEL);*/
		
		
		
		foreach($where as $key=>$value){			
			if($value != null){
				$sqlArray[] = $key."?";
				$paramArray[] = $value;				
			}else
				$sqlArray[] = $key;
		}									
		
		$sql = 'SELECT SUM(entries) as entries, SUM(exits) as exits FROM '.TABLE_STATUS. 
									(count($sqlArray)>0 ? ' WHERE '.join(' AND ', $sqlArray) : '');


		//echo $sql;
		
		$items = getDatabase()->one($sql, $paramArray);
		
		return $items;		
	}
	
	
	static public function insertUpdateDay($values){
		$item = getDatabase()->one('SELECT * FROM '.TABLE_DAY_STATUS.' WHERE iddevice=:iddevice AND date=:date',
												array(
														':iddevice' => $values["iddevice"],
														':date' => $values["date"],
													));
		if($item == false){
				$affectedRows = getDatabase()->execute('INSERT INTO '.TABLE_DAY_STATUS.' 
														(iddevice, date, entries, exits)
														VALUES (:iddevice, :date, :entries, :exits)',
														$values);
		}else{
			$values["id"] = $item["id"];
			$affectedRows = getDatabase()->execute('UPDATE '.TABLE_DAY_STATUS.' 
													SET iddevice=:iddevice, date=:date, entries=:entries, exits=:exits
													WHERE id=:id',
													$values);
		}
		return $affectedRows;
		
	}
	
	
	static public function get_days($where = null)
	{						
		// Lista de entradas
		
		$sqlArray = array();
        $paramArray = array();		
		
		// Añadimos mac guardad en la sesión (temporal, se mejorará)
		$sqlArray[] = "iddevice=?";
		$paramArray[] = getSession()->get(ID_SEL);
				
		foreach($where as $key=>$value){			
			if($value != null){
				$sqlArray[] = $key."?";
				$paramArray[] = $value;				
			}else
				$sqlArray[] = $key;
		}									
		
		$sql = 'SELECT *, UNIX_TIMESTAMP(date) as unix_timestamp FROM '.TABLE_DAY_STATUS. 
									(count($sqlArray)>0 ? ' WHERE '.join(' AND ', $sqlArray) : '').' 
									ORDER BY date ASC';


		//echo $sql;
		
		$items = getDatabase()->all($sql, $paramArray);
		
		return $items;		
	}
	
	
	static public function sum_days($where = null)
	{						
		// Lista de entradas
		
		$sqlArray = array();
        $paramArray = array();		
		
		// Añadimos mac guardad en la sesión (temporal, se mejorará)
		$sqlArray[] = "iddevice=?";
		$paramArray[] = (int) getSession()->get(ID_SEL);
				
		foreach($where as $key=>$value){			
			if($value !== null){
				$sqlArray[] = $key."?";
				$paramArray[] = $value;				
			}else
				$sqlArray[] = $key;
		}									
		
		$sql = 'SELECT SUM(entries) as entries FROM '.TABLE_DAY_STATUS. 
									(count($sqlArray)>0 ? ' WHERE '.join(' AND ', $sqlArray) : '')." ORDER BY 1";

		//var_dump($paramArray);
		//echo $sql;
		//exit();
		
		$items = getDatabase()->one($sql, $paramArray);
		
		return $items;		
	}
	
	
	
	
}
?>