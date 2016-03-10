<?php 

function mail_all_update ($message, $array_match) {
	foreach ($array_match as list($search, $replace)) {
		$message = mail_update($message, $search, $replace);
	}
	return $message;
}

function mail_update($message, $search, $replace) {
	return str_replace($search, $replace, $message);
}

?>