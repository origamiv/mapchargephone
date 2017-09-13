<?
header('Content-Type: text/html; charset=windows-1251');   
header('Access-Control-Allow-Origin: *');

include "config.php";
$sql="SELECT * FROM objs LIMIT 100";
$res=execsql($sql);
$j=0;
while($row=mysql_fetch_assoc($res))
{       
    //print_r($row);
    $z1=explode(',',$row['coordinates']);
    
    //print_r($z1);
    
    $row['coords']='['.trim($z1[0]).', '.trim($z1[1]).']';
    //print_r($row);
    //$row['publicphone']=htmlspecialchars($row['publicphone']);
    $row['publicphone']=str_replace("(",'',$row['publicphone']); 
    $row['publicphone']=str_replace(")",'',$row['publicphone']); 
    $row['publicphone']=str_replace("-",'',$row['publicphone']); 
    $row['publicphone']=str_replace(" ",'',$row['publicphone']); 
    $row['publicphone']='+7'.$row['publicphone'];
    $ar[$j]['coords']=array(trim($z1[1]), trim($z1[0])); //$row['coords'];
    $ar[$j]['text']=iconv('cp1251','utf-8',$row['name']);
    $ar[$j]['address']=iconv('cp1251','utf-8',$row['address']); 
    
    $j++;  
}

header('Content-Type: text/html; charset=utf-8');
//print_r($ar);

echo 'myPoints='.json_encode($ar).';';
?>