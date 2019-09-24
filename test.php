<?php



error_reporting(E_ALL);
$start = microtime(true);
include_once 'framework/includes.php';
$player_lvl = 1;
$difficulty_coef = 1;
$npc_count = (int)$difficulty_coef*$player_lvl; //1 2 3
$map_size = ($difficulty_coef*$player_lvl*3)*2; // 6? 12 18

// TODO - GET A SOMELIKE MODEL AND VIEW!
// WHAT I WANT NOW! I WANNA BUILD DATABASE OF SOMESHIT!;

function visualMap()
{
    $map_array=array();
    for ($x=0;$x<15;$x++)
    {
        for($y=0;$y<15;$y++)
        {
            // need to set, just what?
            if ($y>=5 && $y<10 && $x>=5 && $x<10)
            {
                $rand_int = mt_rand(0,9);
                $map_array[$x][$y]=$rand_int; //4*4 x=6 y= 6 x=6 y=9 x=9 y=6 x=9 y=9
                continue;
            }
            //$rand_int = mt_rand(0,9);
            $map_array[$x][$y]=0;
            
        }
        //$map_array[$x][$y]=0;
        //$map_array['x'][$x]=mt_rand(15,30);
    }
    
    $count = count($map_array);
    //print $count;
    $map_show ='';
    $count=0;
    $line = 0;
print ("<b>x/y</b>");
for ($x=0;$x<15;$x++)
{
    print "| ".$x." |";
}
print "<br>";
foreach ($map_array as $key=>$value)
{
    foreach ($value as $k=>$v)
    {
        //$count++;
        if ($k%30==0)
        {
            
            if ($line>0)
            {
                $br = "<br>";
            }
            else $br='';
            
                print ($br."\n<b>".$line."</b>-- ");
                $line++;
        }
        print ("| ".$v." |");
        
    }
}
//print $count;
    //foreach ($map_array as $key=>$value)
    //print $map_show;
    
    return $map_array;
}
$map = visualMap();
//print_r(count($map[29]));
$form_message ="
_|___|___|___|___|___|___|___|___|___|
___|___|___|___|___|___|___|___|___|__
_|___|___|___|___|___|___|___|___|___|
___|___|___|___|___|___|___|___|___|__
_|___|___|___|___|___|___|___|___|___|";



























class Test
{
    function checkLineForWay($map_line)
    {
        //unset($map_line[0]);
        
        //print_r(array_filter($map_line,'strlen'));
        //$map_line = array_filter($map_line, 'strlen');
        $ways_count = 0;
        $result=0;
        foreach ($map_line as $key=>$value)
        {
    
            if ($value==wall_type::WAY)
            {
                ++$ways_count;
                //print ("we are here is! value is ".$value."<br> \n");
                
            }
      
        }
        //print ("ways count: ".$ways_count."<br> \n");
        if ($ways_count==1)
            $result= map_creatings_result::SAFE_LINE;
            if ($ways_count>1)
                $result= map_creatings_result::MANY_WAYS;
        
        print "result if checkline: ".$result."<br> \n";
        return $result;
    }
    
    function checkSomething($array)
    {
       $founded = array_keys($array, 0);
       $count = count($founded);
       print("here is that shit: ");
       print_r($founded);
       print("<br> \n");
       print "there is count of keys in line: ".$count."<br> \n";
       if ($count>1|| $count==0)
           return false;
       
       return true;
    }
    
    function makeLineArray()
    {
        $line_array = array();
        $count = 0;
        for ($x=0;$x<=3;++$x)
        {
            $rand = mt_rand(0,3);
            if ($rand==wall_type::WAY)
                ++$count;
            
            if ($count>1)
                $rand=mt_rand(1,3);
            
            if($x==3 && !$count)
                $rand=0;
            //there is many additional check or something?
            
            $line_array[]=$rand;
        }
        
        //foreach ($line_array as $key=>$value)
    
        
    return $line_array;
    }
    
    function readMapJson($json)
    {
        $map = json_decode($json);
        return $map;
    }
    
    function makeJsonMapFromArray($map_array)
    {
        $json = json_encode($map_array);
        return $json;
    }
    
    
    function buildMap($player_lvl, $difficulty_coef, $npc_count, $map_size)
    {
        $map_array = array();
        for ($x=0;$x<$map_size;$x++)
        {
            // would we wanna have?
            // map_array[0]=line_array;
            $map_array[$x]=makeLineArray();
            /* for ($y=0;$y<=3;++$y)
            {
        
                //$map_array[$x][$y]=$y;
                $rand = mt_rand(0,3);
                // теперь необходимо как-то определить что должно быть.
                 
                $map_array[$x][$y]=$rand;
        
            } */
        
        }
    return $map_array;
    }
    /*
    // $map = buildMap($player_lvl, $difficulty_coef, $npc_count, $map_size);
    
    
    print ("Count of map elems: ".count($map)."<br> \n");
    
    foreach ($map as $key=>$value)
    {
        echo $key;
        print_r($value);
        echo "<br> \n";
    }
    
    print("WE are work with json now! <br>\n");
    print("<hr><br> \n There is json!: <br>");
    $map_json = makeJsonMapFromArray($map);
    print_r($map_json);
    print("<br>\n There is encoded json from upper!: <br> \n");
    $pewpew = readMapJson($map_json);
    print_r($pewpew);
    print ("<br> \n");
     */
    
    /* for ($x=0;$x<=$map_size;++$x)
    {
        $line = getLine($x, $map);
        foreach($line as $key=>$value)
            print $value;
        print "<br> \n";
    } */
    //$test = getLine(4, $map);
    // мне нужно записать массив данных как?
    //print_r($test);
    //print_r($map_array['x']['y']."<br>"); //$y = num of lines?
    //print_r($map_array."<br>"); //$x = 
    //print_r($map_array[0]);
    //print_r($test);
    
    function getLine($line_num, $map_array)
    {
        return $map_array[$line_num];
      
    }
    
    
    
    


}





















/* $login='totos';
$loginSystem = new LoginSystem();
if ($loginSystem->Register($login))
    print ('registered! <br> \n');
$account = new AccountSystem($login);

if ($loginSystem->Login($login))
{
    print ("lgoined <br> \n");

     $player = new Player(0);
                        $player->createPlayer($fullName);
                        $account->setAccountCharacter($player->GetId());

     if (!$account->getAccountCharacter())
     {
        print ("weird <br> \n");
        $player = new Player(0);
        if ($player->createPlayer("Piewediede")==player_defines::CREATED)
        {
            $account->setAccountCharacter($player->GetId());
            print ("acc char is settted! <br> \n");
            print ("now set sex <br> \n");
            $player = null;
            $player = new Player($account->getAccountCharacter());
            
            $player->setSex(1);
            
            print ("done, char sex is setted");
        }
     }
     else 
     {
         $player = null;
         $player = new Player($account->getAccountCharacter());
         $player->setSex(2);
         print ("there is for having accharid");
     }
    
*/

    //$player->setSex(1); */

//echo "done, script finished";
//print('Время на выполнение: '.(microtime(true) - $start) . ' сек.');