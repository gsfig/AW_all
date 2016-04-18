<?php


class Ibent_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	public function annotate($text){	
		
		$payload = json_encode( array( "text"=> $text,"format"=>"json"));
		// formato json do python client vai diferente

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
		curl_setopt($curl, CURLOPT_URL, "127.0.0.1:8080/iice/chemical/entities");
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);		
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		# Send request.
		$result = curl_exec($curl);
		curl_close($curl);
			
		
		return $result;
	}
	
}

?>