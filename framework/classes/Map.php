<?php
class Map
{
    use ConfigMgr;
    
    private $player_instance, $map_array;
    
    public function __construct(Player $player, $map_id=0, $mapJson=0)
    {
        $this->player_instance = $player;
        
        if (!$map_id && !$mapJson)
        {
            //$this->load
            $this->generateRandomMap();
            
        }
        elseif ($mapJson)
        {
            $this->map_array = $this->loadMapFromJson($mapJson);
            
        }
    }
    
    
    public function gettLine($current_line)
    {
        return $this->map_array[$current_line];
    }
    
    public function getPlayer()
    {
        return $this->player_instance;
    }
    
    public function getMapArray()
    {
        return $this->map_array;
    }
    
    public function generateRandomMap()
    {
        $player = $this->getPlayer();
        $player_lvl = $player->GetLevel();
        // what we need to do?
        
        $difficulty_coef = 1;
        
        $npc_count = (int)$difficulty_coef*$player_lvl; //1 2 3
        
        $map_size = ($difficulty_coef*$player_lvl*3)*2; // 6? 12 18
        $map_array = array();
        for ($x=0;$x<$map_size;$x++)
        {
            $map_array[$x]=$this->fillMapLine();
    
        }
        $this->map_array = $map_array;
        return $this->map_array;    
    }
    
    public function fillMapLine()
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
    
           $line_array[]=$rand;
        }
        return $line_array;
    }
    
    public function saveMapToPlayer()
    {
        
    }
    
    public function loadMapFromJson($json_map)
    {
        $map_array = json_decode($json_map);
        return $map_array;
    }
    
    public function makeJson($map_array=0)
    {
        if (!$map_array)
            $map_array=$this->getMapArray();
        
        $json_map = json_encode($map_array);
        return $json_map;
    }
    
    public function readJson()
    {
        
    }
    
    
}