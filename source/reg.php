<?
include "config.php";
header('Content-Type: text/html; charset=windows-1251');
$s=file_get_contents('data.json');
$z=explode('},',$s);

foreach($z as $m)
{
$z2=explode(',"',$m);


//$ar=json_decode($z[1]);
foreach($z2 as $item)
{
    $z3=explode('":',$item);
    $nam=$z3[0];    
    unset($z3[0]);
    $val=implode('":',$z3);
    //print_r($z3);
    
    $nam=str_replace('"','',$nam);
    $nam=str_replace('[','',$nam);
    $nam=str_replace(']','',$nam);
    $nam=str_replace('{','',$nam);
    $nam=str_replace('}','',$nam);
    $nam=strtolower($nam);
    
    $val=str_replace('"','',$val);
    $val=str_replace('[','',$val);
    $val=str_replace(']','',$val);
    $val=str_replace('{','',$val);
    $val=str_replace('}','',$val);
    
    $ar[trim($nam)]=trim($val);
}
$ar['id']=0+$ar['id'];

$sql="INSERT INTO `objs` (`id`) VALUES ('".$ar['id']."')";
execsql($sql);
foreach ($ar as $k=>$v)
{
    if ($k!='id')
    {
    $sql="UPDATE `objs` SET `$k`='$v' WHERE (`id`='".$ar['id']."')";
    execsql($sql);  
    }
}
echo $sql;

print_r($ar);
}

?>