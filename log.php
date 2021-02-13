<?php
	function writeLog($data = "\n") {
		$f = fopen("sys.log", "a");
		if ($data != "\n") {
			$t = date('Y-m-d h:i:s');
			fwrite($f, $t . "\t" . $data . "\n");
		} else {
			fwrite($f, $data);
		}
	}
?>