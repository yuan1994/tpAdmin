<?php
$id = $_POST["id"];
$ret = array();
for($i=0;$i<10;$i++){
  $value = $id . "-" . $i; 
  $ret[] = array(
    "id" => $value,
    "text" => $value,
    "value" => $value,
    "showcheck" => true,
    "complete" => false,
    "isexpand" => false,
    "checkstate" => 0,
    "hasChildren" => true
  );
}

header('Content-type:text/javascript;charset=UTF-8');
echo json_encode($ret); 

?>