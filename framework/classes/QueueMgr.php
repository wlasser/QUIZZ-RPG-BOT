<?php
class QueueMgr
{
    use ConfigMgr;
    
    /* what we need? we must create instrance of queue.
     * what is include
     * i need to get room id for better using, not better - just one possible!
     * how to make it? if status 
     * 1st player , 2nd player, 3d player. first - we create that room?
     * 
     *     
     * first we check all for free rooms?
     * player going to queue. if that not have current players in queue room
     * if there is new room, create it!
     * if that is not filled room, join it.
     *
     * 1 and now - we must create room, if player is not in room
     * 2 we put player in queue and make him happy, if that is random dungeon
     * 3 we are create instance for that player
     * 4 
     * REVIEW: need to create party-system, communicated with gameMgr, and than look over the realising...
     *   */
    
    private $id, $time_created, $time_ended, $dungeon_id, $status;
    private $player_in_queue = array();
    private $instance_instance = 0;
    
    public function __construct(Player $Player, $dungeon_id=0) //dungeon id is always existed, but need to set as...
    {
        // where dungeon id =0, we must create random dungeon!
        $this->instance_instance = new InstanceMgr($Player);  // we must rename that is in lower case TODO
        
        if (!$dungeon_id)
        {
            
        }
        
        $room_id=$this->getFreeRoomId($dungeon_id);
        // dat is terrible!
        if ($room_id && !$this->isPlayerInQueue($Player, $dungeon_id) && $this->checkFreeSlotForRoom($dungeon_id, $room_id))
        {
            $this->playerJoin($room_id, $Player, $dungeon_id);
        }
        if ($room_id && $this->isPlayerInQueue($Player, $dungeon_id))
        {
            
            if ($this->checkForReady($room_id, $dungeon_id))
            {
            }
                
        }
        
        if (!$room_id && !$this->isPlayerInQueue($Player, $dungeon_id))
        {
            $this->createRoom($dungeon_id, $Player);
            LogDebug::log_msg("player go to room");
        }
                
    }
    // fuck!
    
    public function acceptQueue(Player $Player, $dungeon_id)
    {
        
    }
    
/*     public function isPlayerInDungeon(Player $player, $dungeon_id)
    {
        $conn = $this->connect();
        $sth = $conn->prepare("SELECT * FROM queue_info WHERE player_id=? AND dungeon_id=?");
        $sth->bindValue(1, $player->GetId(),PDO::PARAM_INT);
        $sth->bindValue(2, $dungeon_id,PDO::PARAM_INT);
        $sth->execute();
        
        if ($sth->rowCount())
            return TRUE;
        
        return FALSE;
        
    } */
        
    public function getInstance()
    {
        return $this->instance_instance;
    }
    
    public function checkForReady($room_id, $dungeon_id)
    {
        if (!$dungeon_id) // maybe we need set is at other, when we create dat as party, but party is not working now
            return TRUE;
        // first check all queue!
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::QUEUE_MGR_SEL_DUNGEON_ROOM_MEMBERS);
        $sth->bindValue(1, $dungeon_id, PDO::PARAM_INT);
        $sth->bindValue(2, queue_status::ACCEPTED, PDO::PARAM_INT);
        $sth->bindValue(3, queue_status::IN_PROGRESS, PDO::PARAM_INT);
        $sth->bindValue(4, $room_id, PDO::PARAM_INT);
        $sth->execute();
        
        //check for maximum in queue?
        
        
        if ($sth->rowCount()==queue::MAX_ACCEPTED_PLAYERS)
        {
            
            return TRUE;
        }
            
        return FALSE;
    }
    
    public function updatePlayerStatusQueue($room_id, Player $Player, $status)
    {
        $conn = $this->connect();
        $sth = $conn->prepare(update_statements::UPDATE_QUEUE_PLAYER_STATUS);
        $sth->bindValue(1, $status, PDO::PARAM_INT);
        $sth->bindValue(2, $Player->GetId(), PDO::PARAM_INT);
        $sth->bindValue(3, $room_id, PDO::PARAM_INT);
        $sth->execute();
    }
    
    public function updateRoomStatus($room_id, $status)
    {
        $conn = $this->connect();
        $sth = $conn->prepare(update_statements::UPDATE_QUEUE_ROOM_STATUS);
        $sth->bindValue(1, $status, PDO::PARAM_INT);
        $sth->bindValue(2, $room_id, PDO::PARAM_INT);
        $sth->execute();
    }
    
    public function playerJoin($room_id, Player $Player, $dungeon_id)
    {
        // there is need to be what?
        
        // when we join we must insert it, but what happens, if we are join, but early createed dat shit?
        // what what what whatafuck!
        
        if (!$dungeon_id)
            $status = queue_status::ACCEPTED;
        else 
            $status = queue_status::IN_PROGRESS;
        
        if ($this->isPlayerJoined($Player, $dungeon_id))
        {
            LogDebug::log_msg("player exactly in queue!r");
            
        }
        else 
        {
            $conn = $this->connect();
            $sth = $conn->prepare(insert_statements::INS_QUEUE_MGR_INFO);
            $sth->bindValue(1, $room_id, PDO::PARAM_INT);
            $sth->bindValue(2, $Player->GetId(), PDO::PARAM_INT);
            $sth->bindValue(3, $dungeon_id, PDO::PARAM_INT); // ok. dat is here?
            $sth->bindValue(4, time(), PDO::PARAM_INT); // start time!
            $sth->bindValue(5, 0, PDO::PARAM_INT); //end time
            $sth->bindValue(6, $status, PDO::PARAM_INT);
            $sth->execute();
            LogDebug::log_msg("we are create new info for that");
            
           
        }
        
        $instanceMgr = new InstanceMgr($Player);
        
        LogDebug::log_msg("join to queue player");
    }
    
    //
    
    public function isPlayerJoined(Player $player, $dungeon_id)
    {
        $conn = $this->connect();
        $sth = $conn->prepare("SELECT * FROM queue_info WHERE player_id=? AND dungeon_id=? AND player_status=?");
        $sth->bindValue(1, $player->GetId(), PDO::PARAM_INT);
        $sth->bindValue(2, $dungeon_id, PDO::PARAM_INT);
        $sth->bindValue(3, queue_status::ACCEPTED, PDO::PARAM_INT);
        $sth->execute();
        
        if ($sth->rowCount())
            return TRUE;
        
        return FALSE;
    }
    
    public function createRoom($dungeon_id, Player $Player)
    {
        $conn = $this->connect();
        $sth = $conn->prepare(insert_statements::INS_QUEUE_MGR_ROOM);
        $sth->bindValue(1, $dungeon_id,PDO::PARAM_INT);
        $sth->execute();
        $room_id=$conn->lastInsertId();
        
        $this->playerJoin($room_id, $Player, $dungeon_id);
        
    }
    
    public function getFreeRoomId($dungeon_id=0)
    {
        if (!$dungeon_id) // that is when we make it random!
            return NULL;
        
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::QUEUE_MGR_SEL_DUNGEON_ROOM);
        $sth->bindValue(1, $dungeon_id,PDO::PARAM_INT);
        $sth->bindValue(2, queue_room_status::IN_PROGRESS,PDO::PARAM_INT);
        $sth->execute();
        
        while ($row=$sth->fetch(PDO::FETCH_ASSOC))
        {
            return $row['id'];
        }
        
        
    }
    
    public function isPlayerInQueue(Player $Player, $dungeon_id)
    {
        $conn = $this->connect();
        
        $sth = $conn->prepare(select_statements::QUEUE_MGR_SEL_PLAYER_DUNGEON);
        $sth->bindValue(1, $Player->GetId(), PDO::PARAM_INT);
        $sth->bindValue(2, $dungeon_id, PDO::PARAM_INT);
        $sth->execute();
        
        if (!$sth->rowCount())
            return FALSE;
        
        return TRUE;
    }
    
    public function checkFreeSlotForRoom($dungeon_id, $room_id)
    {
        
        // now it hardcoded, need to write dungeon system or somelike for get other values!
        // then that before. need to check free slots. it's is when players maximum in that room dungeon
        // statuses: in progress, accepted, declined.
        /* $dungeon_max_slots = queue::MAX_ACCEPTED_PLAYERS; */
        
        LogDebug::log_msg("check free slots for dungeon_id");
        // выбрать все, где есть этот данж и засунуть в первый, где есть свободное место.
        $conn = $this->connect();
        
        $sth = $conn->prepare(select_statements::QUEUE_MGR_SEL_DUNGEON_ROOM_MEMBERS);
        $sth->bindValue(1, $dungeon_id, PDO::PARAM_INT);
        $sth->bindValue(2, queue_status::IN_PROGRESS, PDO::PARAM_INT);
        $sth->bindValue(3, queue_status::ACCEPTED, PDO::PARAM_INT);
        $sth->bindValue(4, $room_id, PDO::PARAM_INT);
        $sth->execute();
        
        $players_in_queue = $sth->rowCount();
        
        if ($players_in_queue>=queue::MAX_ACCEPTED_PLAYERS) //that need to be changed by other states!
        {
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function checkPlayerQueue(Player $Player)
    {
        // check for player dungeon, if have completed - skip
        
        
    }
    
    public function addPlayerToQueue(Player $Player, $dungeon_id)
    {
        
    }
    
    
}