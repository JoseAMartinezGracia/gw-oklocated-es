<?php
class ble{
	public $mac;
	public $type;
	public $bleName;
	public $ibeaconUuid;
	public $ibeaconMajor;
	public $ibeaconMinor;
	public $ibeaconTxPower;
	public $rssi;
	public $battery;
	public $batterymV;
	public $temperature;
	public $pressure;	
	public $humidity;
	public $visibleLight;
	public $accelerationX;
	public $accelerationY;
	public $accelerationZ;
	public $serial;
	public $entries;
	public $exits;
	public $rawData;
	public $gatewayLoad;
	public $gatewayFree;
	public $timestamp;
		
	
	public function process(object $status):void{
		$this->mac = $status->mac ?? null;
		$this->type = $status->type ?? null;
		$this->bleName = $status->bleName ?? null;
		$this->ibeaconUuid = $status->ibeaconUuid ?? null;
		$this->ibeaconMajor = $status->ibeaconMajor ?? null;		
		$this->ibeaconMinor = $status->ibeaconMinor ?? null;
		$this->ibeaconTxPower = $status->ibeaconTxPower ?? null;
		$this->rssi = $status->rssi ?? null;
		$this->battery = $status->battery ?? null;
		$this->temperature = $status->temperature ?? null;
		$this->pressure = $status->pressure ?? null;
		$this->batterymV = $status->batterymV ?? null;
		$this->humidity = $status->humidity ?? null;				
		$this->rawData = $status->rawData ?? null;
		$this->gatewayLoad = $status->gatewayLoad ?? null;
		$this->gatewayFree = $status->gatewayFree ?? null;
		$this->timestamp = date("Y-m-d H:i:s", strtotime($status->timestamp));
		
		if($this->type == "Unknown" AND $this->rawData != null){
			$data = strtolower($this->rawData);
			$type = substr($data, 8, 2);
			//echo "type: ".$type."</br>";
			switch ($type){
				case "03":
					$data = substr(strtolower($this->rawData), 16);
					$service = substr($data, 0, 2);
					if($service == "16"){
						$uuid = self::littleToBigEndian(substr($data, 2, 4));
						//echo "uuid: ".$uuid."</br>";
						switch ($uuid){
							case "ffe1":
								$this->processMinewUUID($data);
								break;
							case "feaa":
								$this->processEddystone($data);
								break;
						}				
					}
					break;
				case "ff":
					//$manufacturer = self::littleToBigEndian(substr($data, 10, 4));
					$manufacturer = substr($data, 10, 4);
					//echo "manufacturer: ".$manufacturer."</br>";
					switch ($manufacturer){
						case "9904":
							$this->processRuuvi($data);
							break;		
						case "3906":
							$this->processMinewMSD($data);
							break;
					}				
					
					
				
			}	
		}
	}
		
	
	// Manufacturer Specific Data
	public function processMinewMSD(string $data){
		$data =  substr($data, 14);
		echo "data: ".$data."</br>";
		$type = substr($data, 2, 2);
		//echo "type: ".$type."</br>";
		switch ($type){
			case "18":
				$this->type = "Minew MSR01-B";
				$this->serial = hexdec(substr($data, 6, 2));
				$this->entries = hexdec(self::littleToBigEndian(substr($data, 8, 4)));
				$this->exits = hexdec(self::littleToBigEndian(substr($data, 12, 4)));
				break;
		}
	}
	
	
	public function processEddystone(string $data){
		$data =  substr($data, 6);
		//echo "data: ".$data."</br>";
		$type = substr($data, 0, 2);
		//echo "type: ".$type."</br>";
		switch ($type) {
			case "00":			
				$this->type = "Eddyston UID";							
				break;
			case "10":			
				$this->type = "Eddyston URL";							
				break;
			case "20":
				$version = substr($data, 2, 2);
				//echo "version: ".$version."</br>";
				if($version == '00'){
					$this->type = "Eddyston TLM";
					//echo hexdec(substr($data, 4, 4));
					$this->batterymV = hexdec(substr($data, 4, 4));
					$this->temperature = self::seedec(substr($data, 8, 4));
				}
				break;
			
		}
		
	}
	
	public function processMinewUUID(string $data){
		$data =  substr($data, 6);
		//echo "data: ".$data."</br>";
		$type = substr($data, 0, 2);
		//echo "type: ".$type."</br>";
		if($type == 'a1'){
			$productModel = hexdec(substr($data, 2, 2));
			//echo "productModel: ".$productModel."</br>";			
			switch ($productModel) {
				case 2:
					// Light sensor e6
					$this->type = "Minew E6";
					$this->battery = hexdec(substr($data, 4, 2));
					$this->visibleLight = hexdec(substr($data, 6, 2));
					break;
				case 3:
					// Accelerometer sensor e8
					$this->type = "Minew E8";
					$this->battery = hexdec(substr($data, 4, 2));
					$this->accelerationX = self::seedec(substr($data, 6, 4));
					$this->accelerationY = self::seedec(substr($data, 10, 4));
					$this->accelerationZ = self::seedec(substr($data, 14, 4));					
					break;
					
				case 8:
					$this->type = "Minew Info";
					$this->battery = hexdec(substr($data, 4, 2));
					$this->bleName = self::hexstring(substr($data, 18));
					break;
			}
		}		
	}
	
	
	public function processRuuvi(string $data){
		$this->type = "Ruuvi";
		$data =  substr($data, 14);
		echo "data: ".$data."</br>";
		$type = substr($data, 0, 2);
		//echo "type: ".$type."</br>";
		switch ($type){
			case "03":			
				$this->humidity = hexdec(substr($data, 2, 2)) * 0.5;
				echo "humidity: ".$this->humidity."</br>";							
				$this->temperature = self::shexdec(substr($data, 4, 2)) + hexdec(substr($data, 6, 2))/100;				
				echo "temp: ".$this->temperature."</br>";
				$this->pressure = hexdec(substr($data, 8, 4)) + 50000;
				echo "pressure: ".$this->pressure."</br>";	
				echo "dex pressure: ".substr($data, 8, 4)."</br>";	
				
								
				echo "dex accelerationX: ".substr($data, 12, 4)."</br>";	
				$this->accelerationX = self::twocomplementhexdec(substr($data, 12, 4));
				echo "accelerationX: ".$this->accelerationX."</br>";	
				
				echo "dex accelerationY: ".substr($data, 16, 4)."</br>";	
				$this->accelerationY = self::twocomplementhexdec(substr($data, 16, 4));
				echo "accelerationY: ".$this->accelerationY."</br>";	
				
				echo "dex accelerationZ: ".substr($data, 20, 4)."</br>";	
				$this->accelerationZ = self::twocomplementhexdec(substr($data, 20, 4));
				echo "accelerationZ: ".$this->accelerationZ."</br>";	
				
				
				$this->batterymV = hexdec(substr($data, 24, 4));
				echo "batterymV: ".$this->batterymV."</br>";
				
				break;
			case "05":			
				$this->humidity = hexdec(substr($data, 6, 4)) * 0.0025;
				echo "humidity: ".$this->humidity."</br>";							
				$this->temperature = self::twocomplementhexdec(substr($data, 2, 4)) * 0.005;
				echo "temp: ".$this->temperature."</br>";
				$this->pressure = (hexdec(substr($data, 10, 4)) + 50000) * 0.01;
				echo "pressure: ".$this->pressure."</br>";	
				echo "dex pressure: ".substr($data, 10, 4)."</br>";	
												
				echo "dex accelerationX: ".substr($data, 14, 4)."</br>";	
				$this->accelerationX = self::twocomplementhexdec(substr($data, 14, 4));
				echo "accelerationX: ".$this->accelerationX."</br>";	
				
				echo "dex accelerationY: ".substr($data, 18, 4)."</br>";	
				$this->accelerationY = self::twocomplementhexdec(substr($data, 18, 4));
				echo "accelerationY: ".$this->accelerationY."</br>";	
				
				echo "dex accelerationZ: ".substr($data, 22, 4)."</br>";	
				$this->accelerationZ = self::twocomplementhexdec(substr($data, 22, 4));
				echo "accelerationZ: ".$this->accelerationZ."</br>";	
				
				
				// Power info
				$power = base_convert(substr($data, 26, 4), 16, 2);
				$power = str_pad($power, 16, "0", STR_PAD_LEFT);
				$this->batterymV = substr($power, 0, 11);
				$this->batterymV =  base_convert($this->batterymV, 2, 10) + 1600;				
				echo "batterymV: ".$this->batterymV."</br>";
				
				break;
		}
	}
	
	
	static function littleToBigEndian(string $little) {
		 return implode('',array_reverse(str_split($little,2)));
	}
	
	// signed 8.8 to decimal notation
	static function seedec(string $hex):float{
		$integer = hexdec(substr($hex, 0, 2));
		$decimal = hexdec(substr($hex, 2, 2)) / 256;

		// 2-complement int8
		if($integer > 127) {
			return ($integer - 256) + $decimal;
		}
		return $integer + $decimal;
	}
	
	// Hexadecimal to ascii string
	static function hexstring(string $hex){
		$string = '';
		for ($i=0; $i < strlen($hex)-1; $i+=2){
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	
	// Signed hexadecimal to decimal
	function shexdec(string $hex):int {
		$integer = hexdec($hex);
		if($integer > 127) 
			$integer = 128 - $integer;
		return $integer;
	}
	
	// 2-complement hexadecimal to decimal
	function twocomplementhexdec(string $hex):int {
		$pow = pow(2, 4 * strlen($hex));
		$integer = hexdec($hex);
		if($integer > $pow/2-1)
			$integer = $integer - $pow;
		return $integer;
	}

	
}
?>