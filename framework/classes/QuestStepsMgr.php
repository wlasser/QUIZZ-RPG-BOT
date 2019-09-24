<?php

class QuestStepsMgr
{
    use ConfigMgr;
    
    // так нам надо взят айди квеста и получить шаг, относительно очков...
    private $quest_reward = array();
    
    public function __consturct()
    {
        //TODO MAYBE dat can be load shit? NEED TO REFACTOR GETSHIT!!!!!
    }
    
    public function checkTime(Player $player, $quest_id)
    {
        //what we need, get player quests!
        
        $player_q = $player->PlayerQuests();
        
        $questTemplate = new QuestMgr($quest_id);
        
        $timeToFinish = $questTemplate->getTimeToFinish();
        foreach ($player_q['quest_id'] as $key=>$value)
        {
            if ($value==$quest_id)
            {
                if ($player_q['time_accepted'][$key]>0 && strtotime($timeToFinish, $player_q['time_accepted'][$key])<=time()) 
                {
                    return TRUE;
                }
            }
        }
        
        return FALSE;        
    }
    
    public function checkRequirements($req_type, $req_thing, Player $player)
    {
        $inventory = $player->getInventoryInstance();
        
        switch ($req_type)
        {
            case variants_type::ITEM:
                if ($inventory->HaveItemInInventory($req_thing))
                {
                    return 1;
                }
            break;
            case variants_type::RUMORS:
                if ($player->getRumor($req_thing))
                    return 2;
            break;
            case variants_type::NPC_FIGHT:
                // what is it?
                return 3;
            break;
            case variants_type::PLAYER_CLASS:
                if ($player->getClass()==$req_thing)
                    return 4;
            break;
            case variants_type::MONEY:
                if ($player->GetMoney()>=$req_thing)
                    return 5;
            break;
            default:
                return false;
        }
        
        return false;
    }

    public function GetShit($quest_id, $scores=0)
    {
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::GET_VARIANTS_TEXT);
        $execute_params = array( //TODO MAKE IT BINDVALUE!
            $quest_id,
            $scores
        );
        $sth->execute($execute_params);
        $quest_temp = array();
        // $x=0;
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            // ++$x;
            // how to do that? if we have npc fight, need to give player?????
            // $quest_temp['npc_fight']=$row['npc_fight'];
            $quest_temp['next_quest'] = $row['next_quest'];
            $quest_temp['greetings'] = $row['greetings'];
            $quest_temp['variants'][1] = $row['variant_1'];
            $quest_temp['variants'][2] = $row['variant_2'];
            $quest_temp['variants'][3] = $row['variant_3'];
            $quest_temp['variants'][4] = $row['variant_4'];
            $quest_temp['req_type'][1] = $row['req_type_1'];
            $quest_temp['req_type'][2] = $row['req_type_2'];
            $quest_temp['req_type'][3] = $row['req_type_3'];
            $quest_temp['req_type'][4] = $row['req_type_4'];
            $quest_temp['req_thing'][1] = $row['req_thing_1'];
            $quest_temp['req_thing'][2] = $row['req_thing_2'];
            $quest_temp['req_thing'][3] = $row['req_thing_3'];
            $quest_temp['req_thing'][4] = $row['req_thing_4'];
            $quest_temp['special'][1] = $row['special_1'];
            $quest_temp['special'][2] = $row['special_2'];
            $quest_temp['special'][3] = $row['special_3'];
            $quest_temp['special'][4] = $row['special_4'];
            for ($x=1;$x<=4;$x++)
            {
                $this->quest_reward['rewardItem'][$x]=$row['rewardItem_'.$x];
                $this->quest_reward['rewardCount'][$x]=$row['rewardCount_'.$x];
                $this->quest_reward['rewardType'][$x]=$row['rewardType_'.$x];
            }
        }
        return $quest_temp;
    }
    
    public function getRewards($scores=0)
    {
        if (!empty($this->quest_reward))
            return $this->quest_reward;
    } 

    public function CollectScores($variant, $quest_id, $player_old_scores,  Player $Player)
    {
        if (! (int) $player_old_scores)
            $player_old_scores = 0;
            
        $this->GetShit($quest_id, $player_old_scores);
        
            
        if ($this->checkTime($Player, $quest_id))
        {
            $Player->finishQuest($quest_id, true);
            return quest_result::TIME_FAIL;
        }
        
        if ($player_old_scores<(-100))
        {
            $Player->finishQuest($quest_id, true);
            return quest_result::QUEST_FAIL;
        }
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::GET_VARIANTS_TEXT);
        $execute_params = array($quest_id,$player_old_scores); // TODO:: MAKE IT LIKE BINDVALUE!
        
        $sth->execute($execute_params);
        $rewards = $this->getRewards();
        $current_rewards= array();
        // TODO DO SOMETHING WITH DAT!
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) 
        {
            // echo '1231';
            switch ($variant) {
                case 1:
                    $score = $row['succ_score_1'];
                    $current_rewards['rewardType']=$rewards['rewardType'][1];
                    $current_rewards['rewardItem']=$rewards['rewardItem'][1];
                    $current_rewards['rewardCount']=$rewards['rewardCount'][1];
                    break;
                case 2:
                    $score = $row['succ_score_2'];
                    $current_rewards['rewardType']=$rewards['rewardType'][2];
                    $current_rewards['rewardItem']=$rewards['rewardItem'][2];
                    $current_rewards['rewardCount']=$rewards['rewardCount'][2];
                    break;
                case 3:
                    $score = $row['succ_score_3'];
                    $current_rewards['rewardType']=$rewards['rewardType'][3];
                    $current_rewards['rewardItem']=$rewards['rewardItem'][3];
                    $current_rewards['rewardCount']=$rewards['rewardCount'][3];
                    break;
                case 4:
                    $score = $row['succ_score_4'];
                    $current_rewards['rewardType']=$rewards['rewardType'][4];
                    $current_rewards['rewardItem']=$rewards['rewardItem'][4];
                    $current_rewards['rewardCount']=$rewards['rewardCount'][4];
                    break;
            }
        }
        
        $inventory = $Player->getInventoryInstance();
        $free_inv_slots = $inventory->getFreeSlots();
        $result = null;
        if (!empty($current_rewards))
        {
            $rewardCount = $current_rewards['rewardCount'];
            switch ($current_rewards['rewardType'])
            {
                case reward_types::ITEM:
                    if ($rewardCount>0)
                    {
                        if ($rewardCount>$free_inv_slots)
                        {
                            return $result = quest_result::ERROR_INVENTORY;
                        }
                        else
                        {
                            for ($x=1;$x<=$current_rewards['rewardCount'];$x++)
                            {
                                $item = new Item($current_rewards['rewardItem']);
                                if ($inventory->HaveItemInInventory($item->GetId()))
                                {
                                    return quest_result::ERROR_INVENTORY;
                                    //continue;
                                }
                                if ($inventory->PickupItem($item)==inventory_result::ERROR_MAX_COUNT)
                                {
                                    return  quest_result::ERROR_INVENTORY;
                                    //continue;
                                }
                                else
                                    continue;
                    
                            }
                    
                        }
                    }
                break;
                case reward_types::PROFESSION:
                    if ($rewardCount>0)
                    {
                        if (!$Player->GetProfessionId())
                        {
                            if ($current_rewards['rewardItem']==profession::OPERATOR)
                            {
                                if (!$inventory->HaveItemInInventory(30))
                                {
                                    $item = new Item(30);
                                    if ($inventory->PickupItem($item)==inventory_result::ERROR_MAX_COUNT)
                                    {
                                        return  quest_result::ERROR_INVENTORY;
                                        //continue;
                                    }
                                }
                            }
                            $Player->setProfession($current_rewards['rewardItem']);
                            
                        }
                    }
                break;
            }
        }
        
        if (!$result || $result==(-100))
        {
            $Player->UpdateCurrentScores($quest_id, $score);
            return quest_result::ALL_OK;
        }
        else 
            return quest_result::ERROR_INVENTORY;
       
        // FUCK!@!!111
    }
}