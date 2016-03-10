<?php 
	class Recaptcha {
		
		private $api_secret;
		private $api_site;

		function __construct ($api_site, $api_secret) {
			$this->api_secret = $api_secret;
			$this->api_site = $api_site;
		}

		/**
		 * Permet de vérifier la réponse donnée par recaptha
		 * @param string $code
		 * retune bool
		 */

		public function isValid ($code, $ip = null) {

			if (empty ($code)) {
				return false;
			}

			$params = [
				'secret' => $this->api_secret,
				'response' => $code,
				'remoteip' => $ip
				];
			if ($ip) {
				$params['remoteip'] = $ip;
			}

			$url = "https://www.google.com/recaptcha/api/siteverify?". http_build_query($params);
			
			if (function_exists('curl_version')) {
				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_TIMEOUT, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				
				$response = curl_exec($curl);					
			}
			else {
				$response = file_get_contents($url);
			}

			if (empty($response) || is_null($response)) {
				return false;
			}

			$json = json_decode($response);
			return $json->success;
		}
	}	
?>