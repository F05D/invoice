<?php

class Enigma {

	private $enigma;
	
	public function __construct()
{
		
		$this->enigma =  array(			
			array('A','A3','H5','O6'),
			array('V','A6','H8','O0'),
			array('r','A1','H6','O3'),
			array('=','A4','H3','O8'),
			array('B','A2','H1','O2'),
			array('W','A8','H7','O7'),
			array('s','A9','H2','O1'),
			array('@','A0','H9','O4'),
			array('C','A5','H0','O5'),
			array('Y','A7','H4','O9'),
			array('t','B5','I6','P3'),
			array('|','B8','I0','P6'),
			array('D','B6','I3','P1'),
			array('Z','B3','I8','P4'),
			array('u','B1','I2','P2'),
			array('E','B7','I7','P8'),
			array('a','B2','I1','P9'),
			array('v','B9','I4','P0'),
			array('F','B0','I5','P5'),
			array('b','B4','I9','P7'),
			array('w','C6','J3','Q5'),
			array('G','C0','J6','Q8'),
			array('c','C3','J1','Q6'),
			array('y','C8','J4','Q3'),
			array('H','C2','J2','Q1'),
			array('d','C7','J8','Q7'),
			array('z','C1','J9','Q2'),
			array('I','C4','J0','Q9'),
			array('e','C5','J5','Q0'),
			array('1','C9','J7','Q4'),
			array('J','D6','K5','R3'),
			array('f','D0','K8','R6'),
			array('2','D3','K6','R1'),
			array('K','D8','K3','R4'),
			array('g','D2','K1','R2'),
			array('3','D7','K7','R8'),
			array('L','D1','K2','R9'),
			array('h','D4','K9','R0'),
			array('4','D5','K0','R5'),
			array('M','D9','K4','R7'),
			array('i','E5','L6','S3'),
			array('5','E8','L0','S6'),
			array('N','E6','L3','S1'),
			array('j','E3','L8','S4'),
			array('6','E1','L2','S2'),
			array('O','E7','L7','S8'),
			array('k','E2','L1','S9'),
			array('7','E9','L4','S0'),
			array('P','E0','L5','S5'),
			array('l','E4','L9','S7'),
			array('8','F6','M3','T5'),
			array('Q','F0','M6','T8'),
			array('m','F3','M1','T6'),
			array('9','F8','M4','T3'),
			array('R','F2','M2','T1'),
			array('n','F7','M8','T7'),
			array('0','F1','M9','T2'),
			array('S','F4','M0','T9'),
			array('o','F5','M5','T0'),
			array('-','F9','M7','T4'),
			array('T','G5','N6','U3'),
			array('p','G8','N0','U6'),
			array('_','G6','N3','U1'),
			array('U','G3','N8','U4'),
			array('q','G1','N2','U2'),
			array('.','G7','N7','U8'),
			
		);
	}

	public function encoder($string) {
		
		$arr_string = str_split($string);
		
		$enigma_code = "";
				
		$counter_3x = 1;
		for ( $i = 0 ; $i < count($arr_string) ; $i++ ) {
			for ( $j = 0 ; $j < count($this->enigma) ; $j++ ) {
				
				if($arr_string[$i] == $this->enigma[$j][0] ) {
					$enigma_code .= $this->enigma[$j][$counter_3x];				
				}
			}
			$counter_3x++;
			if($counter_3x > 3) $counter_3x = 1;
		}
		
		return $enigma_code;
	}
	
	public function decoder($enigma_code){
		
		$arr_enigma = str_split($enigma_code,2);
		
		$enigma_str = "";	
		
		$counter_3x = 1;
		for ( $i = 0 ; $i < count($arr_enigma) ; $i++ ) {
			for ( $j = 0 ; $j < count($this->enigma) ; $j++ ) {
				if($arr_enigma[$i] == $this->enigma[$j][$counter_3x] ) {
					$enigma_str .= $this->enigma[$j][0];
				}
			}
			$counter_3x++;
			if($counter_3x > 3) $counter_3x = 1;
		}
		
		return $enigma_str;
	}
}
	
	
	
?>