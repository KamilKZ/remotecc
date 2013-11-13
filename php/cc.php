<?php

//http://us3.php.net/manual/en/function.array-merge-recursive.php#92195
function array_merge_recursive_distinct ( array &$array1, array &$array2 ){
	$merged = $array1;
	foreach ( $array2 as $key => &$value ){
		if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ){
			$merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
		}else{
			$merged [$key] = $value;
		}
	}

	return $merged;
}

if(isset($_GET["ccin"])){
	// PREVENT CACHING FIRST BEFORE ANYTHING ELSE!
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache"); // HTTP/1.0
}
class CC{
	//last1 - save (last1>last2==webcontrol)
	//last2 - "put"
	//last3 - "get"
	public static function alive($id){
		$d = json_decode(file_get_contents("cc/".$id),true);
		return ($d["last2"] > (time()-30))or($d["last3"] > (time()-30));
	}
	
	public static function open($id){
		if(file_exists("cc/".$id)){
			$file = file_get_contents("cc/".$id);
			$data = json_decode($file,true);
			return $data;
		}else{ 
			return array(); 
		}
	}
	
	public static function save($id, $data){
		$data["last1"] = time();
		$file = fopen("cc/".$id,"w");
		fwrite($file, json_encode($data));
		fclose($file);
	}
	
	public static function receive($id, $name, $data){
		$new = json_decode($data,true);
		$cur = CC::open($id);
		$out = array_merge_recursive_distinct($cur, $new);
		$out["name"] = $name;
		$out["last2"] = time();
		CC::save($id, $out);
	}
	
	public static function request($id){
		$data = CC::open($id);
		if(isset($data["name"])){
			$data["last3"] = time();
			return $data;
		}else{
			return false;
		}
	}
}

if(isset($_GET["ccin"])){
	if(isset($_GET["ccid"])){
		$id = $_GET["ccid"];
		$name = $_GET["ccn"];
		if(isset($_GET["put"])){//cc requested to save data
			CC::receive($id, $name, urldecode($_GET["data"]));
			echo "ok";
		}elseif(isset($_GET["get"])){//cc requested to get data
			$o = CC::request($id);
			if($o){
				echo json_encode($o);
			}else{
				echo "error";
			}
		}elseif(isset($_GET["establish"])){//cc first connect
			CC::receive($id, $name, '{"connection-created":'.time().'}');
			echo "ok";
		}
	}else{
		echo "error";
	}
}//else not cc/include in webcontrol
?>	