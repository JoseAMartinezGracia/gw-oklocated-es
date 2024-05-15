<?php
require_once(ABSPATH.'libs/ble.php');

class GW2
{
	static public function post_status(string $mac)
	{			
		$mac = strtoupper($mac);
		//file_put_contents("data/".time().".json", file_get_contents('php://input'));
		//echo "<p>".$mac."</p>";
		if(preg_match('([0-9A-Fa-f]{12})', $mac) == 1 AND strlen($mac) == 12){			
			
			
			// Lista de archivos
			$datas = scandir(ABSPATH.'/data/');
			
			foreach($datas as $file){
				if($file != '.' AND $file != '..'){
					$json = file_get_contents(ABSPATH.'/data/'.$file);
					
					$json = json_decode($json);
					
					//var_dump($json);
					//exit();
			
					//file_put_contents(date("H:i:s"), $json);
					
					if($json != null){	
						foreach($json as $status){
							echo "<p>".$mac."</p>";
							if(count(MACS) == 0 OR in_array($status->mac, MACS)){
								echo "<p>".$status->mac."</p>";
								var_dump($status);
								
								$ble = new ble();
								$ble->process($status);
								
								if(in_array($ble->type, TYPES)){
													
									$status_db = getDatabase()->execute('INSERT INTO '.TABLE_STATUS.' 
																				(gateway_mac, mac, type, timestamp_device, ble_name, ibeacon_uuid, ibeacon_major, ibeacon_minor, ibeacon_tx_power, rssi, battery, batterymV, temperature, humidity, pressure, visible_light, acceleration_x, acceleration_y, acceleration_z, serial, entries, exits, raw_data, gateway_load, gateway_free)
																				VALUES (:gateway_mac, :mac, :type, :timestamp_device, :ble_name, :ibeacon_uuid, :ibeacon_major, :ibeacon_minor, :ibeacon_tx_power, :rssi, :battery, :batterymV, :temperature, :humidity, :pressure, :visible_light, :acceleration_x, :acceleration_y, :acceleration_z, :serial, :entries, :exits, :raw_data, :gateway_load, :gateway_free)',
													array(
														':gateway_mac' => $mac,
														':mac' => $ble->mac,
														':type' => $ble->type,
														':timestamp_device' => $ble->timestamp,
														':ble_name' => $ble->bleName ?? null,
														':ibeacon_uuid' => $ble->ibeaconUuid ?? null,
														':ibeacon_major' => $ble->ibeaconMajor ?? null,
														':ibeacon_minor' => $ble->ibeaconMinor ?? null,
														':ibeacon_tx_power' => $ble->ibeaconTxPower ?? null,
														':rssi' => $ble->rssi ?? null,
														':battery' => $ble->battery ?? null,
														':batterymV' => $ble->batterymV ?? null,
														':temperature' => $ble->temperature ?? null,
														':humidity' => $ble->humidity ?? null,
														':pressure' => $ble->pressure ?? null,
														':visible_light' => $ble->visibleLight ?? null,
														':acceleration_x' => $ble->accelerationX ?? null,
														':acceleration_y' => $ble->accelerationY ?? null,
														':acceleration_z' => $ble->accelerationZ ?? null,
														':serial' => $ble->serial ?? null,
														':entries' => $ble->entries ?? null,
														':exits' => $ble->exits ?? null,
														':raw_data' => $ble->rawData ?? null,
														':gateway_load' => $ble->gatewayLoad ?? null,
														':gateway_free' => $ble->gatewayFree ?? null,
														//':updated_at' => date("Y-m-d H:i:s", strtotime($ble->timestamp)),
														
													));
								}
								
								
							}
						}
					}
					
					
				}				
			}
			
			
			
			
			
			
			
		}
		
	}
	
	static public function get_status($mac)
	{		
		$debug = true;
		self::post_status($mac, true);
		
	}
	
	
	
	
}
?>