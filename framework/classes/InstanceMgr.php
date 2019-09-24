<?php
class InstanceMgr
{
    /*
     * what is it? we must first create room for all actions, as now it random map.
     * first - create room, with dungeon id = 0; // - that mean random
     * next, in instance mgr we must create random map for it? and
     * 
     *  player_id, instance_type, map, start_time, end_time, completed!
     *  
     *  we must check is player in instance at now?
     */ 
    use ConfigMgr;
    private $conn, $player_instance;
    private $dungeon_id, $map, $start_time, $end_time, $completed, $current_line, $map_instance;
    
    public function __construct(Player $player, $dungeon_id=0)// what????? // we must get instance uid?
    {
        // what we need to do?
        $this->conn = $this->connect();
        $this->player_instance = $player;
        $instance_uid = $this->isInInstance($dungeon_id);
        if (!$instance_uid)
        {
            $this->createInstance($dungeon_id);
        }
        else
        {
            $this->loadData($instance_uid, $dungeon_id);
        }
    }
/*     
    public function getPlayerInstanceUidForDungeon($dungeon_id)
    {
        $sth = $this->getConn()->prepare("SELECT * FROM player_instance WHERE player_id=? AND dungeon_id=? AND completed=?");
        $sth->bindValue($parameter, $value);
        
    } */
    
    public function getPlayer() // hmm... it can be moved to abstract or something?
    {
        return $this->player_instance;
    }
    
    public function getConn()
    {
        return $this->conn;
    }
    
    public function randomDungeonReward()
    {
        $this->getPlayer()->modifyMoney(20);
        $this->getPlayer()->addExp(50);
        $this->completed=1;
        $this->end_time=time();
        $this->updateInstance();
    }
    
    public function makeStep($step)
    {
        $currentLine = $this->getMapLine();
        $currentLinePos = $this->current_line;
        $player = new Player($this->getPlayer()->GetId());
        $last_line = count($this->map_instance->getMapArray());
        // 1,2,3,4
        //3 | 2 | 3 | 0 |
        $complete = false;
        $achievmentMgr = new AchievmentMgr($this->getPlayer());
        if ($currentLinePos+1>=$last_line)
        {
            $this->randomDungeonReward();
            $achievmentMgr->logAchiev(instance_log::COMPLETE_RANDOM_SOLO, 0); // as it i think it's what? random dungeon counter;
            $complete=true;
        }
        
        switch ($currentLine[$step])
        {
            case wall_type::DOOR:
                if ($this->getPlayer()->modifyFatigue(4))
                {
                    $this->current_line++;
                    $this->updateInstance();
                    if ($complete)
                        return step_result::COMPLETE;
                        
                    return step_result::DOOR_OPENED;
                }
                else
                {
                    $this->current_line = $this->current_line;
                    //$this->updateInstance();
                    return step_result::CANT_MOVE;
                }
            break;
            case wall_type::WAY:
                if ($this->getPlayer()->modifyFatigue(3))
                {
                    $this->current_line++;
                    $this->updateInstance();
                    if ($complete)
                        return step_result::COMPLETE;
                    return step_result::DONE;
                }
                else
                {
                    $this->current_line = $this->current_line;
                    //$this->updateInstance();
                    return step_result::CANT_MOVE;
                }
            break;
            case wall_type::WALL:
            case wall_type::WEIRD:
                if ($this->getPlayer()->modifyFatigue(5))
                {
                    //$this->getPlayer()->UpdatePlayer();
                    //$this->getPlayer()->UpdateStats();
                    return step_result::FAIL;
                }
                else
                {
                    return step_result::CANT_MOVE;
                }
            break;
            default:
                $this->getPlayer()->modifyFatigue(5);
                return step_result::FAIL;
        }
             
    }
    
    public function updateInstance()
    {
        $sth = $this->getConn()->prepare("UPDATE player_instance SET current_line=?, completed=?, end_time=? WHERE player_id=? AND dungeon_id=?");
        $sth->bindValue(1, $this->current_line, PDO::PARAM_INT);
        $sth->bindValue(2, $this->completed, PDO::PARAM_INT);
        $sth->bindValue(3, $this->end_time, PDO::PARAM_STR);
        $sth->bindValue(4, $this->getPlayer()->GetId(), PDO::PARAM_INT);
        $sth->bindValue(5, $this->dungeon_id, PDO::PARAM_INT);
        $sth->execute();
        
    }
    
    public function loadData($instance_uid, $dungeon_id)
    {
        if ($this->isInInstance($dungeon_id))
        {
            $sth = $this->conn->prepare("SELECT * FROM player_instance WHERE uid=?");
            $sth->bindValue(1, $instance_uid, PDO::PARAM_INT);
            $sth->execute();
            // what we need to do? FUCK!
            while ($row=$sth->fetch(PDO::FETCH_ASSOC))
            {
                // do something!
                $this->map = $row['map'];
                $this->dungeon_id = $row['dungeon_id'];
                $this->start_time = $row['start_time'];
                $this->end_time = $row['end_time'];
                $this->completed = $row['completed'];
                $this->current_line = $row['current_line'];
                $this->map_instance = new Map($this->getPlayer(), 0, $this->map);
            }
        }
    }
    
    public function getMapLine()
    {
        $current_line = $this->current_line;
        $mapArray = $this->map_instance->getMapArray();
        
        $line = $this->map_instance->gettLine($current_line);
        
        return $line;
        
    }
    
    public function isInInstance($dungeon_id)
    {
        // there need to be checekd otherway
        // theoreticaly it's not happen, but?
        $conn = $this->connect();
        //ogDebug::log_msg("instaqncemgr : something here 100,1");
        // WTF!!!
        $sth=$conn->prepare("SELECT * FROM `player_instance` WHERE `player_id`=? AND `dungeon_id`=? AND `completed`=0");
        $sth->bindValue(1, $this->getPlayer()->GetId(), PDO::PARAM_INT);
        $sth->bindValue(2, $dungeon_id, PDO::PARAM_INT);
        $sth->execute();
        if ($sth->rowCount())
        {
            while ($row = $sth->fetch(PDO::FETCH_ASSOC))
            {
                return $row['uid'];    
            }
            
        }
        return FALSE;
        
    }
    
    
    
    public function createInstance($dungeon_id)
    {
        $mapMgr = new Map($this->getPlayer(),0); // 0 is mean random map gen
        $mapJson = $mapMgr->makeJson(); // as now it 0, coz it use self array
        $timeNow = time();
        //uid, player_id, dungeon_id, map, start_time, end_time, compeleted
        $sth = $this->conn->prepare("INSERT INTO `player_instance` VALUE ('',?,?,?,?,?,?,?)");
        $sth->bindValue(1, $this->getPlayer()->GetId(), PDO::PARAM_INT);
        $sth->bindValue(2, $dungeon_id, PDO::PARAM_INT);
        $sth->bindValue(3, $mapJson, PDO::PARAM_STR);
        $sth->bindValue(4, 0, PDO::PARAM_INT); // current_line
        $sth->bindValue(5, $timeNow, PDO::PARAM_STR);
        $sth->bindValue(6, 0, PDO::PARAM_INT);
        $sth->bindValue(7, 0, PDO::PARAM_INT);
        $sth->execute();
        $this->map = $mapJson;
        $this->dungeon_id = $dungeon_id;
        $this->start_time = $timeNow;
        $this->end_time = 0;
        $this->completed = 0;
        $this->current_line=0; // need to check is it
        $this->map_instance = new Map($this->getPlayer(), 0, $this->map);
    }
    
    
    
}