<?php
require("db/db_setup.php");

ini_set('memory_limit', '1000M');
$y=0;
$counter=0;
$group=array(1,2,3,4,5); //5 element array
foreach($group as $k =>$v1){
    foreach($group as $k => $v2){
        foreach($group as $k => $v3){
            foreach($group as $k => $v4){
                foreach($group as $k => $v5) {
                       
					if (($v1 != $v2) && ($v1 != $v3) && ($v1 != $v4) && ($v1 != $v5) &&($v2 != $v3) && ($v2 != $v4) && ($v2 != $v5) && ($v3 != $v4) && ($v3 != $v5) && ($v4 != $v5)) {
						$counter++;
						$query = "INSERT INTO order_index (study,variant,ordering) VALUES ('1','$counter','$v1,$v2,$v3,$v4,$v5')";
						mysql_query($query) or die("count is $counter and error is: ".mysql_error());
					}
            $y++;
                    }
            }
            }
             }
} 

echo $counter;


?>