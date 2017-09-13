<?
require_once "config.php";
header('Access-Control-Allow-Origin: *'); 

//print_r($_REQUEST);
$id=$_REQUEST['id'];
$act=$_REQUEST['act'];
//$_REQUEST['device']=999;
//if ($act=='start')

$sql='INSERT INTO users ("fio", "login", "hash", "id_role", "dognost", "device_id", "age", "ves", "rost") '.
     'VALUES (\''.$_REQUEST['fio'].'\', \'test\', \'test\', \'4\', \'tester\', \''.$_REQUEST['device'].'\', \''.$_REQUEST['age'].'\', \''.$_REQUEST['ves'].'\', \''.$_REQUEST['rost'].'\')';
//echo $sql;
$row=$dbh->query($sql);

$sql="SELECT max(id) as mid FROM users";
$row=$dbh->query($sql)->fetchAll();
//print_r
echo 'Ваш ID '.$row[0]['mid']; //json_encode($ar);

?>