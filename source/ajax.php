<?
require_once "config.php";

 function gen_pass( $length ) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);

}

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

execsql($sql,'utf8');

$sql="SELECT max(id) as mid FROM users";
$res=execsql($sql);
$row=mysql_fetch_assoc($res);
//print_r
echo $row['mid']; //json_encode($ar);
}

if ($func=='click')
{
$id_point=$_REQUEST['id_point'];
$id_user=$_REQUEST['id_user'];    
$sql="INSERT INTO `objs_click` (`id_obj`, `dat`, `id_user`, `pr`) VALUES ('$id_point', NOW(), '$id_user', 'click')";
execsql($sql);
}

if ($func=='mar')
{
$id_point=$_REQUEST['id_point'];
$id_user=$_REQUEST['id_user'];    
$sql="INSERT INTO `objs_click` (`id_obj`, `dat`, `id_user`, `pr`) VALUES ('$id_point', NOW(), '$id_user', 'mar')";
execsql($sql);
}

if ($func=='skid')
{
    $val=gen_pass(12);
    echo $val;
    $id_user=$_REQUEST['id_user'];    
    $sql="INSERT INTO `skid_users` (`val`, `dat`, `id_user`) VALUES ('$val', NOW(), '$id_user')";
    execsql($sql);
}
?>