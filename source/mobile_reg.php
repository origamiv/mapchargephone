<?
require_once "config.php";
header('Access-Control-Allow-Origin: *'); 

//print_r($_REQUEST);
$id=$_REQUEST['id'];
$act=$_REQUEST['act'];
//$_REQUEST['device']=999;
//if ($act=='start')

$sql="INSERT INTO `users` (`fio`, `vozrast`, `login`, `pass`) VALUES ('{$_REQUEST['fio']}', '{$_REQUEST['vozrast']}', '{$_REQUEST['login']}', '{$_REQUEST['pass']}')";
//echo $sql;

execsql($sql);

$sql="SELECT max(id) as mid FROM users";
$res=execsql($sql);
$row=mysql_fetch_assoc($res);
//print_r
echo $row['mid']; //json_encode($ar);

?>