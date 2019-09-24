<?php

class Game
{
    use ConfigMgr;
    // TODO - we must check player online status, we must change it from 1 to 0 when activity time is go away?
    function __construct()
    {
        
        // TODO - Insert your code here
    }
    
    public function getAllPlayers()
    {
        $players = array();
        $conn = $this->connect();
        
        $sth = $conn->prepare(select_statements::LOAD_ALL_PLAYERS);
        $sth->execute();
        
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $players['id'][]=$row['id'];
            $players['online'][]=$row['online'];
        }
        
        
        return $players;  
    }
    
    public function updateFatigue()
    {
        $players = $this->getAllPlayers();
        
        foreach ($players['id'] as $key=>$value)
        {
            $player = new Player($value);
            $max_fatigue = $player->getMaxFatigue();
            $current_fatigue = $player->GetFatigue();
            $currect_hp = $player->GetHp();
            $max_hp = $player->GetMaxHp();
            
            if ($current_fatigue==0)
                continue;
            // maybe cron update?
            $player->modifyFatigue(-(restore_values::FATIGUE_RESTORE));    
            
        }
        
        
    }
    
    public function getOnlinePlayers()
    {
        $conn = $this->connect();
        
        $sth = $conn->prepare(select_statements::LOAD_ALL_ONLINE_PLAYERS);
        $sth->execute();
        
        $online_players = array();
        
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $online_players['lastActivityTime'][] = $row['lastActivityTime'];
            $online_players['id'][] = $row['id'];
            $online_players['online'][] = $row['online'];
            $online_players['name'][]=$row['name'];
            $online_players['location'][]=$row['location'];
            $online_players['sublocation'][]=$row['sublocation'];
        }
        
        return $online_players;
    }
    
    public function getOnlinePlayersForCurrentLocSubloc($location, $sublocation)
    {
        $conn = $this->connect();
        
        $sth = $conn->prepare(select_statements::LOAD_ALL_PLAYERS_LOC_SUBLOC_ONLINE);
        $sth->bindValue(1, $location, PDO::PARAM_INT);
        $sth->bindValue(2, $sublocation, PDO::PARAM_INT);
        $sth->execute();
        $players_online=array();
        
        while ($row=$sth->fetch(PDO::FETCH_ASSOC))
        {
            $players_online['id'][]=$row['id'];
            $players_online['name'][]=$row['name'];
        }
        
        return $players_online;
    }

    public function checkActivity()
    {
        $online_players = $this->getOnlinePlayers();
        $time_now = time();
        
        foreach ($online_players['lastActivityTime'] as $key => $value) 
        {
            if ($time_now>=strtotime(update_timers::UPDATE_ONLINE_PLAYER,$value))
            {
                $player = new Player($online_players['id'][$key]);
                $player->setOnline(player_ingame::OFFLINE);
            }
        }
    }

}

?>