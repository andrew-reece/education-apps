<?php

//for printing out all possible combinations of a factorial

function comb($a, $len){
   //if ($len > count($a))return 'error';
   $out = array();
   if ($len==1) {
      foreach ($a as $v) $out[] = array($v);
      return $out;
   }
   $len--;
	  $example = count($a);
   while (($len > 3) && $ {
	  echo "len is $len and count a is $example<br/>";
      $b = array_shift($a);
      $c = comb($a, $len);
      foreach ($c as $v){
         array_unshift($v, $b);
         $out[] = $v;
      }
   }
   return $out;
}
$test = array(1,2,3);
$a = comb($test,5);

$counter = 1;
$count = count($a);
echo "final a count is $count";
foreach($a as $first) {
	$query = "INSERT INTO order_index (study, variant, order) VALUES ('1', ";
	$line = '';
	foreach($first as $second) {
		$line .= "$second - ";
	}
	$line = rtrim($line, " - ");
	$query .= "'$counter', '$line')";
	echo $query;
	echo "<br />counter is $counter and count is $count";
	$counter++;
}
?>