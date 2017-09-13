<?
# Имя базы данных  
$DB_NAME='phonemap';
# Имя сервера баз данных   
$DB_HOST='localhost'; 
# Логин для доступа к БД  
$DB_USER='root'; 
# Пароль для доступа к БД   
$DB_PASS='9030404'; 

ini_set('display_errors','off');

function connopen($charset='cp1251')
{
global $LINK;
global $DB_NAME,$DB_USER,$DB_HOST,$DB_PASS;
$LINK=@mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
//echo "LINK: $LINK";
mysql_select_db($DB_NAME,$LINK);
//mysql_query("set NAMES cp1251", $LINK); 
mysql_query("SET NAMES '$charset'");
mysql_query("SET CHARACTER SET '$charset'"); 
}

function execsql($sql, $charset='cp1251')
{
global $LINK;
global $DB_NAME,$DB_USER,$DB_HOST,$DB_PASS;    
connopen($charset);
$res=mysql_query($sql,$LINK);
return $res;
}   




function convert_subj($subject)
{
    if ($subject != null)
    {
        $arr = explode('?', $subject);
        //print_r($arr);
        if ($arr != null)
        {
            if (count($arr) >= 4)
            {
                $in_charset = $arr[1];
                $text = $arr[3]; //' '.$arr[7].' '.$arr[10];
                if ($text != null)
                {
                    if (strlen($text) >= 3 && $arr[2] != null)
                    {
                        $text = substr($text, 0, strlen($text));
                        $text = $arr[2] == 'Q' ? quoted_printable_decode($text) : base64_decode($text);
                        $text=iconv($in_charset, 'WINDOWS-1251', $text);
                    }
                }
                $text1=$text;
                
                $text = $arr[7]; //' '.$arr[7].' '.$arr[10];
                if ($text != null)
                {
                    if (strlen($text) >= 3 && $arr[6] != null)
                    {
                        $text = substr($text, 0, strlen($text));
                        $text = $arr[6] == 'Q' ? quoted_printable_decode($text) : base64_decode($text);
                        $text=iconv($in_charset, 'WINDOWS-1251', $text);
                    }
                }
                $text2=$text;
                
                $text = $arr[11]; //' '.$arr[7].' '.$arr[10];
                if ($text != null)
                {
                    if (strlen($text) >= 3 && $arr[10] != null)
                    {
                        $text = substr($text, 0, strlen($text));
                        $text = $arr[10] == 'Q' ? quoted_printable_decode($text) : base64_decode($text);
                        $text=iconv($in_charset, 'WINDOWS-1251', $text);
                    }
                }
                $text3=$text;
                
                return trim($text1.$text2.$text3);
                
            }
        }
    }
    else return null;
}

function convert_txt($txt)
{
//    echo $txt;
    
if (($txt[0]=='-') and ($txt[1]=='-')) 
{
$z=explode("\n",$txt);
$razd=trim($z[0]);
$parts=explode($razd,$txt);
}
else
{
$parts[1]=$txt;
}
//echo $razd;
//exit;
//print_r($parts);

unset($parts[0]);
foreach($parts as $k=>$v)
{
    $parts[$k]=trim($v);
    
    $part=trim($v);
    if ($part=='--') {continue;}
    
    $z=explode("\n",$part);
    
    //print_r($z);
    
    $ct=$z[0];
    $enc=$z[1];        
    
    $i=0;
    while(trim($z[$i])!='')
    {           
    unset($z[$i]); 
    $i++;
    }
    //unset($z[1]);
    //unset($z[2]);
    
    $part=implode("\n",$z);
    unset($z);
    $ct=strtolower($ct);
    $z=explode(';',$ct);
    $type=str_replace('content-type: ','',trim($z[0]));
    $charset=str_replace('charset=','',trim($z[1]));
    
    //echo "Typ $type CH $charset";
    unset($z);
    $z=explode(':',$enc);
    $encoding=trim($z[1]);
    $encoding=strtolower($encoding);
    //echo $encoding;
    
    if ($encoding=='base64')
    {    
    $part=imap_base64($part);    
    }
    
    //echo  "PART ".$part;
    
    //exit;
    
    if ($type=='text/plain')
    {
      $part=iconv($charset,'windows-1251',$part);
    }
    
    if ($type=='text/html')
    {
      unset($z);
      $z=explode('>',$part);
      //print_r($z);
      foreach($z as $k=>$v)
      {         
        $v=trim($v);
        if ($v!='')
        {    
        $v=rtrim($v).'>';
        $z[$k]=iconv($charset,'windows-1251',$v);
        $z[$k]=trim($z[$k]);
        }
        //$zz=explode('<',$v);
        //$z2[]=$zz[0];
        //$z2[]=$zz[1];
        
        
      }
      $part=implode("\n",$z);
      //print_r($z);
      
      //$part=iconv($charset,'windows-1251',$part);
    }
    
    if (trim($part)!='')
    {
    $parts2[$type]=$part;
    }
    //echo $part."\r\n";
 
 //exit;   
}
  
//$pismo=implode("\r\n".$razd."\r\n",$parts2);

return $parts2;
}


//$sql='select * FROM t_info_params';
//$res=execsql($sql);
//while($row=mysql_fetch_assoc($res))
//{
//$nam=$row['param'];
//$params[$nam]=$row['value'];
//}        
//          
//extract($params);
//
          
//echo $MAIL_HOST;          
//$MAIL_HOST='smtp.mail.ru';
//$MAIL_PORT=465;
//$MAIL_USER='uspeh@tarifgd.ru';
//$MAIL_PASS='oOBPquXa';

?>
