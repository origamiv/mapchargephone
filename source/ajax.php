<?
require_once "config.php";
header('Access-Control-Allow-Origin: *'); 
$func=$_REQUEST['func'];


if ($func=='reg')
{
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
}

if ($func=='mar')
{
$id_point=$_REQUEST['id_point'];
$id_user=$_REQUEST['id_user'];    
$sql="INSERT INTO `objs_click` (`id_obj`, `dat`, `id_user`) VALUES ('$id_point', NOW(), '$id_user')";
execsql($sql);

}

?>