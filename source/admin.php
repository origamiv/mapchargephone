<table width="100%">
<tr>
<td bgcolor="#dcdcdc"><a href="admin.php?act=objs">Объекты</a></td>
<!--td bgcolor="#dcdcdc"><a href="admin.php?act=usl">Услуги</a></td>
<td bgcolor="#dcdcdc"><a href="admin.php?act=rasp2">Расписание</a></td>
<td bgcolor="#dcdcdc"><a href="admin.php?act=google">Параметры</a></td -->

<td bgcolor="#dcdcdc" width="100%">&nbsp;</td>
</tr>
</table>
<?
include "config.php";
$act=$_REQUEST['act'];
$more=$_REQUEST['more'];
$id=$_REQUEST['id'];


if ($act=='google000')
{
    $url="https://calendar.google.com/calendar/ical/bolivspinenet%40gmail.com/private-3b14ec8a473ce904214352f5dbfb3f91/basic.ics";
    $f=file($url);
    $i=0;
    foreach($f as $item)
    {
    $item=trim($item);
    if ($item=='BEGIN:VEVENT') {$i++;unset($event);}
    if ($item=='END:VEVENT') {$events[$i]=$event;}
    $cmd=explode(':',$item);
    //print_r($cmd);
    $c=count($cmd);
    if ($c==2) 
    {
        $cmd1=$cmd[0];
        $val=$cmd[1];
    
        if ($cmd1=='SUMMARY') {$event['txt']=$val;}
        if ($cmd1=='DTSTART') {$event['dat_from']=date('Y-m-d H:i',strtotime($val));}
        if ($cmd1=='DTEND') {$event['dat_to']=date('Y-m-d H:i',strtotime($val));}
    }
     
        
    }
       
    //print_r($events);
    
    //$dat='01.05.2017';
//    for($t1=9;$t1<=21;$t1++)
//    {
//    $ar[$dat][$t1.':00:00']='';
//    $ar[$dat][$t1.':30:00']='';    
//    }
//    
//    foreach($events as $item)
//    {
//        
//    }
    
    
    
    
    //exit;
    foreach($events as $item)
    {
    //print_r($item);    
    
    $fio=$item['txt'];
    $date1 = new DateTime($item['dat_from']);
    $date2 = new DateTime($item['dat_to']);
    $diff = $date2->diff($date1);

// разница в секундах
    $seconds = ($diff->y * 365 * 24 * 60 * 60) +
    ($diff->m * 30 * 24 * 60 * 60) +
    ($diff->d * 24 * 60 * 60) +
    ($diff->h * 60 * 60) +
    ($diff->i * 60) +
    $diff->s;
    
    $min=$seconds / 60;
    
    $int=$min / 30;
    
    $dat2=date('Y-m-d',strtotime($item['dat_from']));
    $dat3=date('d.m.Y',strtotime($item['dat_from']));
    $mytime=date('H:i:00',strtotime($item['dat_from']));
    $fil=1;
    
    
    
    //echo $int."\r\n";
    
    for($ci=0;$ci<$int;$ci++)
    {
    $xxx=30*$ci;  
    
    $mytime=date('H:i:00',strtotime($item['dat_from']."+ $xxx minutes"));  
    
    $sql="SELECT * FROM rasp WHERE (dat='$dat2') and (tim='$mytime') and (id_mesto=$fil)";
    $res=execsql($sql,'utf8');
    $row=mysql_fetch_assoc($res);
    //print_r($row);      
     
    if (trim($fio)!='')
    {  
    $ar[$dat3][$mytime]=$fio;
    }
      
    if ($row==false)
    {
    $sql="INSERT INTO `rasp` (`id_mesto`, `dat`, `tim`, `rec`) VALUES ('$fil', '$dat2', '$mytime', '$fio')";
    //execsql($sql,'utf8');
    }
    else
    {
     $sql="UPDATE rasp SET rec='$fio' WHERE (dat='$dat2') and (tim='$mytime') and (id_mesto=$fil)";
     //execsql($sql,'utf8');
    }
    }
    
    //echo $sql;
    
    //echo $min;
        
    }
    
    print_r($ar);
    exit;
}



if ($act=='objs') 
{
  unset($fields);
  unset($descr);
  unset($prop);
  
  if ($act=='objs') 
  {
      $table='objs'; 
      $fields="name;address;coordinates;district";
      $descr="Название;Адрес;Координаты;Район";
  }
  if ($act=='usl')   
  {
      $table='uslugi';
      $fields="nam;icon";
      $descr="Название;Иконка";
  }
  if ($act=='rasp')   
  {
      $table='rasp';
      $fields="id_mesto;dat;tim;rec";
      $descr="Место;Дата;Время;Запись";  
      $prop='loc(mesta,id,nam);dat;tim;check';
  }
  if ($act=='rasp2')   
  {
      $table='rasp2';
      $fields="id_mesto;dow;from_tim;to_tim;interval";
      $descr="Место;День нед.;Время начала;Время окончания;Интервал";  
      $prop='loc(mesta,id,nam);dow;tim2;tim2;int';
  }
   if ($act=='google')   
  {
      $table='google';
      $fields="param;val;descr";
      $descr="Параметр;Значение;Описание";
  }
  
    
if ($more=='week')    
{
  $dd1=date('Y-m-d',strtotime('last monday'));
  $dd2=date('Y-m-d',strtotime('last monday+6 days'));
  //echo $dd2;
  $sql="SELECT * FROM rasp WHERE (dat>='$dd1') and (dat<='$dd2')";
  $res=execsql($sql,'utf8');
  while($row=mysql_fetch_assoc($res))
  {
     //print_r($row);
     $id_mesto=$row['id_mesto'];
     $dat3=$dd1=date('Y-m-d',strtotime($row['dat'].'+ 6 days'));
     $tim=$row['tim'];
     $sql1="INSERT INTO `rasp` (`id_mesto`, `dat`, `tim`, `rec`) VALUES ('$id_mesto', '$dat3', '$tim', 'Y')";
     //echo $sql1."\r\n";
     execsql($sql1);
     
      
  }
    
    
  echo "<script>document.location='admin.php?act=$act';</script>";
  exit;    
}
    
if ($more=='del')    
{
  $sql="DELETE FROM $table WHERE id=$id";
  execsql($sql,'utf8'); 
  echo "<script>document.location='admin.php?act=$act';</script>";
  exit;  
}
    
if ($more=='new')
{

    
    if (is_array($_REQUEST['tim']))
    {
    $t=$_REQUEST['tim'];
    foreach($t as $tim=>$val)
    {
      
    $sql="INSERT INTO `$table` (tim) VALUES ('$tim')";
    execsql($sql);
    $id=mysql_insert_id();
    
    foreach($_REQUEST as $k=>$v)
    {
        if ($k=='more') {continue;}
        if ($k=='tim') {continue;}
        if ($k=='dat') {$v=date('Y-m-d',strtotime($v));}               
        $sql="UPDATE $table SET $k='$v' WHERE id=$id";
        execsql($sql,'utf8');
    }  
        
        
    } 
    }
    else
    {    
    $sql="INSERT INTO `$table` () VALUES ()";
    execsql($sql);
    $id=mysql_insert_id();
    
    foreach($_REQUEST as $k=>$v)
    {
        if ($k=='more') {continue;}
        if ($k=='dat') {$v=date('Y-m-d',strtotime($v));}               
        $sql="UPDATE $table SET $k='$v' WHERE id=$id";
        execsql($sql,'utf8');
    }
    }
    echo "<script>document.location='admin.php?act=$act';</script>";
    exit;  
    
}
    
if ($more=='save')    
{
    //print_r($_REQUEST);
    foreach($_REQUEST as $k=>$v)
    {
        if ($k=='id') {continue;}
        if ($k=='dat') {$v=date('Y-m-d',strtotime($v));}
        $sql="UPDATE $table SET $k='$v' WHERE id=$id";
        execsql($sql,'utf8');
    }
    echo "<script>document.location='admin.php?act=$act';</script>";
    exit;
}
    
if ($more!='add')
{    
echo "<span bgcolor='#dcdcdc'><a href='admin.php?act=$act&more=add'>добавить</a></span>&nbsp;";    
if ($act=='rasp') {echo "<span bgcolor='#dcdcdc'><a href='admin.php?act=$act&more=week'>продублировать неделю</a></span>"; }
echo '<table><form method=post>';    

$sql="SELECT * FROM $table";
$res=execsql($sql,'utf8');
$f=explode(';',$fields);
$d=explode(';',$descr);

echo '<tr bgcolor="silver">';
    
    echo '<th>id</th>';
foreach($d as $descr)
{    
    echo '<th>'.$descr.'</th>';
}
    echo '<th>Действия</th>';
    
    echo '</tr>';

while($row=mysql_fetch_assoc($res))
{
    //$id=$row['id'];
    //$filial[$id]=$row;
    echo '<tr bgcolor="#DCDCDC">';
    echo "<td>{$row['id']}</td>";
    if ($id==$row['id'])
    {
        foreach($f as $field)
        {      
            echo "<td><input type='text' style='width:100%' name='$field' value='{$row[$field]}'></td>";
        }
        echo "<td><input type='hidden' name='more' value='save'><input type='submit' value='ok'></td>";
    }           
    else
    {
        foreach($f as $field)
        {     
        echo '<td>'.$row[$field].'</td>';
        }    
    echo "<td>";
    echo "<a href='admin.php?act=$act&more=edit&id={$row['id']}'>Ред.</a>&nbsp;";
    echo "<a href='admin.php?act=$act&more=del&id={$row['id']}'>удал.</a>&nbsp;";
    echo "</td>";
    }   
    echo '</tr>';    
}

echo '</form></table>'; 
}
       
if ($more=='add')
{
$f=explode(';',$fields);
$d=explode(';',$descr);    
if ($prop!='')
{
    $p=explode(';',$prop);
    //print_r($p);
}
?>
<form method='post'>
<table>
<?

foreach($d as $k=>$descr)
{ 
if ($prop=='') {$tip='text';}
else 
{
    $z=explode('(',$p[$k]);
    //print_r($z);
    $tip=$z[0];
    if ($tip=='loc')
    {
        $z[1]=str_replace(')','',$z[1]);
        $z1=explode(',',$z[1]);
        //print_r($z1);
        
        $tab=$z1['0'];
        $link=$z1[1];
        $val=$z1[2];
    }
    //echo $tip;
}    
?>
   <tr>
      <td>
         <?=$descr;?>
      </td>
      <td>
      <? if ($tip=='text')
      {
         echo "<input type='text' name='{$f[$k]}'>";
      } 
      elseif ($tip=='dow')
      {
         $days = array('воскресенье', 'понедельник', 'вторник', 'среда','четверг','пятница', 'суббота'); 
         echo "<SELECT name='{$f[$k]}'>";
         for($i2=1;$i2!=0;$i2++)
         {
             if ($i2==7) {$i2=0;}
             echo "<OPTION value='$i2'>{$days[$i2]}</OPTION>";
             if ($i2==0) {break;}
         }
         echo '</SELECT>';         
      } 
      elseif ($tip=='dat')
      {
         echo "<input type='text' name='{$f[$k]}'>";
      } 
      elseif ($tip=='check')
      {
         echo "<input type='hidden' name='{$f[$k]}' value=''>"; 
         echo "<input type='checkbox' name='{$f[$k]}' value='Y'>";         
      } 
      elseif ($tip=='int')
      {
         $ints = array(30,15,60,120); 
         echo "<SELECT name='{$f[$k]}'>";
         foreach($ints as $int)
         {
             echo "<OPTION>$int</OPTION>";
         }
         echo '</SELECT>';         
      }
      elseif ($tip=='tim2')
      {
         //$times = array('воскресенье', 'понедельник', 'вторник', 'среда','четверг','пятница', 'суббота'); 
         echo "<SELECT name='{$f[$k]}'>";
         
         $t='9:00';
         $j=0;
         for($j=0;$j<25;$j++)         
         {   $xx=$j*30;
             //$j++; 
             $t=date('H:i:s',strtotime($dat3.' 9:00 +'.$xx.' minutes'));
             //if ($i2==7) {$i2=0;}
             echo "<OPTION>$t</OPTION>";
             //if ($i2==0) {break;}
         }
         echo '</SELECT>';         
      } 
      elseif ($tip=='tim')
      {
         //echo "<input type='hidden' name='{$f[$k]}' value=''>"; 
         //$t1=10;
         echo '<table><tr>';
         $j=0;
         for($t1=9;$t1<=21;$t1++)
         {$j++;
         $n=$j % 3;
         if ($n==1) {echo "<td valign=top>\r\n";}    
         //echo $n; 
         echo "<input type='checkbox' name='{$f[$k]}[$t1:00:00]' value='Y'>$t1:00<br>\r\n";         
         echo "<input type='checkbox' name='{$f[$k]}[$t1:30:00]' value='Y'>$t1:30<br>\r\n";                   
         if ($n==0) {echo "</td>\r\n";$j=0;} 
         }
         
         if ($n!=0) {echo '</td>';}
         echo '</tr></table>';
         
      } 
      elseif ($tip=='loc')
      {
         echo "<SELECT name='{$f[$k]}'>";
         $sql="SELECT * from $tab ORDER by $link";
         //echo $sql;
         $res=execsql($sql,'utf8');
         while($row=mysql_fetch_assoc($res))
         {
             echo "<OPTION value='{$row[$link]}'>{$row[$val]}</OPTION>";
         }
         echo '</SELECT>';
         //echo "<input type='text' name='{$f[$k]}'>";
      }
      
      ?>   
      </td>
   </tr>
<? } ?>     
</table>
<input type='hidden' name='more' value='new'>
<input type='submit' value='Добавить'>
</form>
<?    
}
}   



exit;
$mail_user='info@kharinov.ru';
$mail_pass='Kharinov2017';


//$obj['nam']='Клиника Доктора Фомина (Лидии Базановой, 5)';
//$obj['adr']='ул. Л. Базановой, 5';
//$obj['coord']='35.91126413201596,56.85194136772949';

//$filial[1]=$obj;
//$filial[2]=$obj;
//$filial[3]=$obj;

$sql="SELECT * FROM uslugi";
$res=execsql($sql,'utf8');

while($row=mysql_fetch_assoc($res))
{
    $id=$row['id'];
    $uslugi[$id]=$row;
}

unset($usl);

