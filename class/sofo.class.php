<?php

class TSOFO {
	
	static function getDayFromAvailabilityCode($av_code) {
	
		if($av_code == 'AV_NOW') return 0;
		else if(preg_match('/AV_([0-9]+)([W,D,M]+)/',$av_code,$reg)) {
			
			$nb = (int)$reg[1];
			
			if($reg[2] == 'D') return $nb;
			else if($reg[2] == 'W') return $nb * 7;
			else if($reg[2] == 'M') return $nb * 31;
			
			return 0;
			
		}
		else{
			return 0;
		}
		
	}
	static function getMinAvailability($fk_product, $qty) {
	global $db,$form;
		
		$sql = "SELECT fk_availability 
				FROM ".MAIN_DB_PREFIX."product_fournisseur_price
				WHERE fk_product=". $fk_product ." AND quantity <= ".$qty;
				
		$res_av = $db->query($sql);
		
		$min = false;
		
		if(empty($form))$form=new Form($db);
		if(empty($form->cache_availability)){
			$form->load_cache_availability();	
		}
		
		while($obj_availability = $db->fetch_object($res_av)) {
			$av_code = $form->cache_availability[$obj_availability->fk_availability] ; 
			$nb_day = self::getDayFromAvailabilityCode($av_code['code']);
			
			if($min === false || $nb_day<$min) $min = $nb_day;
			
		}
		
		return $min;
		
	}
	
	
}
