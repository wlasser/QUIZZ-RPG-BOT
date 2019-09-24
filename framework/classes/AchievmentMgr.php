<?php
class AchievmentMgr 
{
use ConfigMgr;

    private $player =null;
    private $conn = null;
    //private 
    
    public function __construct(Player $player) // that is id like to pref?
    {
        if ($player)
        {
            $this->conn = $this->connect();
            $this->player = $player;
        }
        // else and other?
       
    }
    // that can be adjust as some addictional checks?
    public function logAchiev($criteriaType, $criteriaId)
    {
      // what we need?
      // need to connect and get exactly rows?
       //"SELECT * FROM `player_achievements_log` WHERE `criteriaType`=? AND `criteriaId`=? AND `player_id`=?";
        if ($this->isHaveCriteria($criteriaType, $criteriaId))
        {
            $sth = null;
            
            $sth = $this->conn->prepare(update_statements::UPDATE_ACHIEVE_LOG);
            //`actionCount`=`actionCount`+1, `lastTime`=? WHERE `player_id`=?, `criteriaType`=?, `criteriaId`=?
            $sth->bindValue(1, time(),PDO::PARAM_STR);
            $sth->bindValue(2, $this->getPlayer()->GetId(),PDO::PARAM_INT);
            $sth->bindValue(3, $criteriaType,PDO::PARAM_INT);
            $sth->bindValue(4, $criteriaId,PDO::PARAM_INT);
            $sth->execute();
        }
        else
        {
            // there is some other?
            //there is first insert?
            $sth=null;
            //player_id, criteriaType, criteriaId, actionCount, startTime, lastTime
            $sth = $this->conn->prepare(insert_statements::INS_ACHIEV_LOG);
            $sth->bindValue(1, $this->getPlayer()->GetId(), PDO::PARAM_INT);
            $sth->bindValue(2, $criteriaType, PDO::PARAM_INT);
            $sth->bindValue(3, $criteriaId, PDO::PARAM_INT);
            $sth->bindValue(4, 1, PDO::PARAM_INT); // magic number as count? WOWOWOWOWOW
            $sth->bindValue(5, time(), PDO::PARAM_STR);
            $sth->bindValue(6, time(), PDO::PARAM_STR);
            $sth->execute();
        }
        
    
    }
    public function isHaveCriteria($criteriaType, $criteriaId)
    {
        $sth = $this->conn->prepare(select_statements::ACHIEV_LOAD_CRITERIA_PLAYER);
        $sth->bindValue(1, $criteriaType, PDO::PARAM_INT);
        $sth->bindValue(2, $criteriaId, PDO::PARAM_INT);
        $sth->bindValue(3, $this->getPlayer()->GetId(), PDO::PARAM_INT);
        $sth->execute();        //$rows = $sth->rowCount();
        if ($sth->rowCount())
        {
            return true;
        }
        return false;
    }
    
    public function getPlayer()
    {
        return $this->player;
    }
    
    public function logQuestAchiev($criteriaType, $criteriaId)
    {
        $player= $this->getPlayer();
        switch ($criteriaType)
        {
            case quest_log::COMPLETE_QUEST:
            {
                
                if ($player->IsQuestComplete($criteriaId) && !$this->isHaveCriteria($criteriaType, $criteriaId))
                {
                    $this->logAchiev($criteriaType, $criteriaId);
                }
            
            }
            break;
            case quest_log::RUMORS_FINDED:
            {
                if ($player->getRumor($criteriaId) && !$this->isHaveCriteria($criteriaType, $criteriaId))
                {
                    $this->logAchiev($criteriaType, $criteriaId);
                }
            }
            break;
            default:return -1;
        }
        
    }
    
    //public function 


}