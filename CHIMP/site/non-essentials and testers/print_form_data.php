
<?php

echo "hello world. <br /><br />";

if (isset($_REQUEST)) {
	echo "meep";
	$data = $_REQUEST;
	if($keys = array_keys($data)) {

		for ($i=0; $i<count($keys); $i++) {
			$this_key = $keys[$i];
			echo "input reads as: $this_key equals \"$_REQUEST[$this_key]\" <br /><br />";
		}
	} else {
		echo "no input data <br />";
	}
} else {
	echo "not even set!! <br />";
}

echo "end";
?>
