<?php
include("cc.php");
if(isset($_GET["on"])){
	CC::receive(1,"webcp",'{"on":1}');
}elseif(isset($_GET["off"])){
	CC::receive(1,"webcp",'{"on":0}');
}

$data = CC::request(1);
echo $data["on"];
?>	