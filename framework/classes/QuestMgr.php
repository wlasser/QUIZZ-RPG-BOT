<?php
class QuestMgr
{
    use ConfigMgr;
    
	private $quest_id, $caption, $name, $title, $objective, $location;
	private $rewardExp, $rewardMoney, $variants_id, $min_lvl, $looseFatigue, $nextQuest, $timeToFinish;

	private $reward = array();
	private $require_item = array();
	private $require_type = array();
	
	public function __construct($quest_id)/* :void */
	{
	    if (!$quest_id)
	        return NULL;
	    
		$this->LoadQuest($quest_id);
	}

	public function SetCaption($caption)/*: void */
	{
		$this->caption=$caption;
	}

	public function SetName($name)/*: void */
	{
		$this->name=$name;
	}

	public function SetTitle($title)/*: void */
	{
		$this->title=$title;
	}

	public function SetObjective($objective)/*: void */
	{
		$this->objective=$objective;
	}

	public function LoadQuest($quest_id)/*: void */
	{
        if (!$quest_id)
			return NULL;

        $conn = $this->connect();

        $sth = $conn->prepare(select_statements::LOAD_QUEST);
        $execute_params =array($quest_id) ;
        $sth->execute($execute_params);
        $quest_info = array();
				
		while ($row = $sth->fetch(PDO::FETCH_ASSOC))
		{
        	$this->SetName($row['name']);
			$this->SetTitle($row['title']);
			$this->objective = $row['objective'];
			$this->caption = $row['caption'];
			$this->rewardMoney = $row['rewardMoney'];
            $this->rewardExp=$row['rewardExp'];
			$this->variants_id=$row['variants_index'];
			$this->location=$row['location'];
			$this->min_lvl=$row['min_lvl'];
            $this->quest_id = $quest_id; 
            $this->looseFatigue = $row['looseFatigue'];
            $this->nextQuest = $row['nextQuest'];
            $this->timeToFinish = $row['timeToFinish'];
            for ($x=1;$x<=4;++$x)
            {
                if (!$row['reward'.$x])
                {
                    continue;   
                }
                $this->reward[$x]=$row['reward'.$x];
            }

            for ($x=1;$x<=4;++$x)
            {
      	        if (!$row['require'.$x])
      	        {
                    continue;
                }
                
                $this->require_item[$x]=$row['require'.$x];
            }

            for ($x=1;$x<=4;++$x)
            {
                if (!$row['require_type'.$x])
                {
                    continue;
                }
                $this->require_type[$x]=$row['require_type'.$x];
            }		 

		}

	}
	
	public function getRewards()
	{
	    return $this->reward;
	}
	
	public function checkRequirements(Player $player)
	{
		//printf(var_dump($this->require_type));
		switch ($this->require_type)
		{
			case require_type_quests::QUEST:
				// check player comleted quest
				if (!$player->questCompleted($this->require_item))
					return FALSE;
			break;
			
		}
		//die(var_dump($this->require_item));
	return TRUE;	
		
	}
	
    public function getName()
    {
        return $this->name;
    }
    
	public function CheckLvlRequire(Player $player)/* :boolean */
	{
        if ($player->GetLevel()<$this->min_lvl)
        {
			return false;
                
        }
		return true;
	}
    
	public function getTimeToFinish()
	{
	    return $this->timeToFinish;
	}
	
	public function getObjective()/* :string */
	{
        return $this->objective;
	}

	public function getRewardMoney()
	{
		return $this->rewardMoney;
	}

	public function GetTitle()/* :string */
	{
        return $this->title;
	}

	
	public function completeQuest($quest_id, Player $Player)
	{		
	    $inventory = $Player->getInventoryInstance();
		$reward_count = count($this->reward);
		
	    $free_inv_slots = $inventory->getFreeSlots();
	   
	    // i think that fixed!, but have some questions...???
	    
	    if ($reward_count>$free_inv_slots)
	        return quest_result::ERROR_INVENTORY;
		
	    if (!$Player->modifyFatigue($this->looseFatigue))
	       return quest_result::ERROR_FATIGUE;
	        
	        
		foreach ($this->reward as $key=>$value)
		{
			$item = new Item($value); 
			if ($inventory->PickupItem($item)==inventory_result::ERROR_MAX_COUNT)
				return quest_result::ERROR_INVENTORY;
		}
		
		if ($this->nextQuest)
			$Player->AddQuest($this->nextQuest);
		
		$Player->addExp($this->rewardExp);
		$Player->modifyMoney($this->rewardMoney);
		$Player->finishQuest($quest_id, $fail=false);

	    return quest_result::ALL_OK;
	}
        
    public function GetQuestId()
    {
        return $this->quest_id;
    }
        

}
