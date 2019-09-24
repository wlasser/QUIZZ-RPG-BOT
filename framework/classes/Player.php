<?php
class Player extends Abstract_class
{
    // 
    use ConfigMgr;

	private $armor, $stamina, $resistance, $curr_exp, $agility, $accuracy, $fatigue, $money, $max_fatiguem, $sex, $class;
	private $intellect, $level, $state, $online, $last_activity_time, $inInstance;

	private $location;
	private $sublocation;
    private $profession, $skill;
    public $vehicle; // it can be instance and private. nvm at that moment.
    private $inventory;
    private $flag;
    private $quest_fight = array();    
	private $player_quests = array();


	//TODO REVIEW SOMETHING WITH UPDATING, COZ IT'S HAVE SOME MANY QUERYS IN SAME TIME!
	
	public function __construct($player_id=0) // можно заменить на player_name, получаемый из сессии
	{
	    if ($player_id==0)
	        return;
	    
		$this->LoadData($player_id);
	}
	
	public function initQuestFight($npc_id, $quest_id, $quest_variant=0, $compete=0) //quest variant is for near future!
	{
	    //// autoid, npc_id, player_id, quest_id, quest_variant, complete
	    $conn = $this->connect();
	    
	    $sth0 = $conn->prepare(select_statements::PLAYER_LOAD_QUEST_NPC_FIGHT);
	    //quest_id=? AND player_id=? AND quest_variant=?
	    $sth0->bindValue(1, $quest_id, PDO::PARAM_INT);
	    $sth0->bindValue(2, $this->GetId(), PDO::PARAM_INT);
	    $sth0->bindValue(3, $quest_variant, PDO::PARAM_INT);
	    $sth0->execute();
	    
	    if (!$sth0->rowCount())
	        $this->startQuestFight($npc_id, $quest_id, $quest_variant, $compete=0);
	    else 
	    {
	           $quest_fight=array();
	           while ($row = $sth0->fetch(PDO::FETCH_ASSOC))
	           {
	               $this->quest_fight['autoid'] = $row['autoid'];
	               $this->quest_fight['npc_id'] = $row['npc_id'];
	               $this->quest_fight['player_id'] = $row['player_id'];
	               $this->quest_fight['quest_id'] = $row['quest_id'];
	               $this->quest_fight['quest_variant'] = $row['quest_variant'];
	               $this->quest_fight['complete'] = $row['complete'];	               
	           }
	    }
	}
	
	//public function loadQuestBattle()
	
	public function isQuestFightComplete($npc_id, $quest_id)
	{
	    if ($this->quest_fight['quest_id']==$quest_id && $this->quest_fight['complete']==0)
	        return false;
	    return true;
	        
	    
	   /*  foreach ($this->quest_fight['quest_id'] as $key=>$value)
	    {
	        if ($this->quest_fight['npc_id'][$key]==$npc_id && $this->quest_fight['quest_id'][$key]==$quest_id && $this->quest_fight['complete']==1)
	            return true;
	    }
	    return false; */
	}
	
	public function startQuestFight($npc_id, $quest_id, $quest_variant, $compete=0)
	{
	    $conn = $this->connect();
	    //autoid, npc_id, player_id, quest_id, quest_variant, complete
	    $sth = $conn->prepare(insert_statements::INS_PLAYER_QUEST_FIGHT);
	    $sth->bindValue(1, $npc_id, PDO::PARAM_INT);
	    $sth->bindValue(2, $this->GetId(), PDO::PARAM_INT);
	    $sth->bindValue(3, $quest_id, PDO::PARAM_INT);
	    $sth->bindValue(4, $quest_variant, PDO::PARAM_INT);
	    $sth->bindValue(5, 0, PDO::PARAM_INT);
	    $sth->execute();
	    
	  
	    $this->quest_fight['npc_id'] = $npc_id;
	    $this->quest_fight['player_id'] = $this->GetId();
	    $this->quest_fight['quest_id'] = $quest_id;
	    $this->quest_fight['quest_variant'] = $quest_variant;
	    $this->quest_fight['complete'] = $compete;
	    
	}
	
	public function GetCoords($location_id) //locatuion id? why?
    {
        $conn=$this->connect();

        $sth = $conn->prepare(select_statements::GET_COORDS);
        $execute_params =array($location_id) ;
        $sth->execute($execute_params);
        $coords=array();

        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $coords['x']=$row['x'];
            $coords['y']=$row['y'];

        }

        return $coords;
    }

	public function LoadData($player_id)/*: void */
	{

		if (!$player_id)
			return NULL;
	    
		
	    $conn = $this->connect();        
		$sth = $conn->prepare(select_statements::PLAYER_LOAD_DATA);
		$execute_params =array($player_id) ;
		$sth->execute($execute_params);

		while ($row = $sth->fetch(PDO::FETCH_ASSOC))
		{
		     //update this with setting vars, 
             //Set method's will be use work with mysql
             //this bad for every time.

			$this->SetName($row['name']);
			$this->money = $row['money'];
			$this->location=$row['location'];
			$this->level = $row['level'];
			$this->SetId($player_id);
			$this->curr_exp = $row['curr_exp'];
			$this->sublocation=$row['sublocation'];
		    $this->state = $row['state'];
		    $this->flag = $row['flag'];
		    $this->sex = $row['sex'];
		    $this->class=$row['class'];
		    $this->online = $row['online'];
		    $this->last_activity_time=$row['lastActivityTime']; // mb that is not necessery? why?
		    //$this->vehicle= $row['vehicle'];
	    }
        $this->SetId($player_id);
		$this->PlayerQuests();
		$this->loadInventory();
		
	    if ($this->flag!=player_flags::FRESH)
	    {
		      $this->LoadStats();
              $this->LoadProfession();
              
	    }
	}
	
	public function GetAvailableZoneQuest()
	{

		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::GET_LOCATION_QUEST);
		$execute_params =array($this->location) ;
		$sth->execute($execute_params);
		$available_quests = array();

		while ($row = $sth->fetch(PDO::FETCH_ASSOC))
		{
             $available_quests[]=$row['id'];
		}

		return $available_quests;
	}

	public function setProfession($prof_id)
	{
	    $this->profession = $prof_id;
	    $this->updateProfession();
	}
	
	public function updateProfession()
	{
	    if ($this->profession!=0)
	    {
	        $conn = $this->connect();
	        $sth = $conn->prepare("SELECT * FROM player_profession WHERE player_id=?");
	        $sth->bindValue(1, $this->GetId(), PDO::PARAM_INT);
	        $sth->execute();
	        
	        if (!$sth->rowCount())
	        {
	            $sth = null;                                                //ID,player_id, prof_id, skill
	            $sth = $conn->prepare("INSERT INTO player_profession VALUES ('',?,?,?)");
	            $sth->bindValue(1, $this->GetId(),PDO::PARAM_INT);
	            $sth->bindValue(2, $this->GetProfessionId(),PDO::PARAM_INT);
	            $sth->bindValue(3, 0,PDO::PARAM_INT); // there is 0 at is it! can be rewrited, or somelike
	            $sth->execute();
	        }
	     }
	}
	
	public function setLastActivityTime()
	{
	    $this->last_activity_time = time();
	    //$this->UpdatePlayer();
	    $conn = $this->connect();
	    $sth = $conn->prepare(update_statements::PLAYER_UPDATE_LAST_ACTIVITY);
	    $sth->bindValue(1, $this->last_activity_time, PDO::PARAM_INT);
	    $sth->bindValue(2, $this->GetId(),PDO::PARAM_INT);
	    $sth->execute();
	}
	
    public function LoadProfession()/*:void*/
    {
        $conn = $this->connect();

        $sth = $conn->prepare(select_statements::PLAYER_LOAD_PROFESSION);
        $execute_params =array($this->GetId());
        $sth->execute($execute_params);
            
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $this->profession = $row['profession'];
            $this->skill = $row['skill'];
        }
                        
    }
        
    public function isHaveVehicle()
    {
        if ($this->vehicle)
            return true;
        else
            return false;
    }
    
    public function createPlayer($name, $sex=0 /*$class */) // i think that is not all //that shit is suck!
    {
        //echo "creating new character"; 
        // we need to create temp (not exactly) player
        // cleaning befor accept to database
        $name = $this->SafeString($name);
        
        $conn = $this->connect();
        
        // that can be improved by getIdFromName function, maybe!
        
        $sth = $conn->prepare(select_statements::PLAYER_SELECT_NAME);
        $sth->bindValue(1, $name, PDO::PARAM_STR);
        $sth->execute();
        if ($sth->rowCount())
            return player_defines::ERROR_SAME_NAME;
        // id, name,class, money, level, location, curr_exp, sublocation, state, flag, sex
        $sth = $conn->prepare(insert_statements::INS_DEFAULT_PLAYER);
        $sth->bindValue(1, $name, PDO::PARAM_STR);
        $sth->bindValue(2, 0, PDO::PARAM_INT);
        $sth->bindValue(3, 0, PDO::PARAM_INT);
        $sth->bindValue(4, 1, PDO::PARAM_INT);
        $sth->bindValue(5, 0, PDO::PARAM_INT);
        $sth->bindValue(6, 0, PDO::PARAM_INT);
        $sth->bindValue(7, 0, PDO::PARAM_INT);
        $sth->bindValue(8, 0, PDO::PARAM_INT);
        $sth->bindValue(9, player_flags::FRESH, PDO::PARAM_INT);
        $sth->bindValue(10, $sex, PDO::PARAM_INT); //sex
        $sth->bindValue(11, player_ingame::ONLINE, PDO::PARAM_INT);
        $sth->bindValue(12, 0, PDO::PARAM_INT);
        $sth->execute();
        $this->LoadData($this->getIdFromName($name)); // there not necessary
        //echo $this->GetId();
        return player_defines::CREATED;
    }
    
       
    public function getIdFromName($name)
    {
        $conn = $this->connect();
        
        $sth = $conn->prepare(select_statements::PLAYER_SELECT_NAME);
        $sth->bindValue(1, $name, PDO::PARAM_STR);
        $sth->execute();
        
        while ($row=$sth->fetch(PDO::FETCH_ASSOC))
        {
                $id = $row['id'];
        }
     
        return $id;
        
    }
    
    public function setOnline($online)
    {
        $this->online = $online;
        $conn = $this->connect();
        $sth = $conn->prepare(update_statements::UPDATE_ONLINE_PLAYER);
        $sth->bindValue(1, $this->online, PDO::PARAM_INT);
        $sth->bindValue(2, $this->GetId(), PDO::PARAM_INT);
        $sth->execute();
        
        //$this->UpdatePlayer();
    }
    
    public function createPlayerDefaultStats($class)
    {
        // как блядь создать игрока, не создавая его?
        switch ($class)
        {
            case player_classes::WITCHHUNTER:
                $armor = 10;
                $intellect =13;
                $stamina = 15;
                $hp = 150; // need formula
                $maxHp = 150; // formula?
                $resistance = 5;
                $agility =5;
                $fatigue=0;
                $maxFatigue = 90; // formula?
                $accuracy = 10;
            break;
            case player_classes::READERS:
                $armor = 5;
                $intellect = 10;
                $stamina = 15;
                $hp = 150;
                $maxHp = 150;
                $resistance = 5;
                $agility = 5;
                $fatigue = 0;
                $maxFatigue = 105;
                $accuracy = 10;
            break;
            case player_classes::HUNTER:
                $armor = 13;
                $intellect = 7;
                $stamina = 20;
                $hp = 200;
                $maxHp=200;
                $resistance = 7;
                $agility=7;
                $fatigue=0;
                $maxFatigue=110;
                $accuracy=10;
            break;
            case player_classes::DETECTIVE:
                $armor = 12;
                $intellect = 9;
                $stamina = 18;
                $hp = 140;
                $maxHp=140;
                $resistance = 5;
                $agility=5;
                $fatigue=0;
                $maxFatigue=100;
                $accuracy=13;
            break;
        }
        
        $this->armor=$armor;
        $this->intellect=$intellect;
        $this->stamina=$stamina;
        $this->SetHP($hp);
        $this->SetMaxHp($maxHp);
        $this->resistance=$resistance;
        $this->agility=$agility;
        $this->fatigue=$fatigue;
        $this->max_fatigue = $maxFatigue;
        $this->accuracy=$accuracy;
        $this->flag=player_flags::NEWLY;
        $this->class = $class;
        
        $this->UpdatePlayer();
        $this->UpdateStats();
        
    }
    
        
	public function LoadStats()/*: void */
	{
		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::PLAYER_LOAD_STATS);
		$execute_params =array($this->GetId());
		$sth->execute($execute_params);

		while ($row = $sth->fetch(PDO::FETCH_ASSOC))
		{
			$this->armor = $row['armor'];
			$this->intellect = $row['intellect'];
			$this->stamina = $row['stamina'];
			$this->SetHP($row['hp']);
			$this->SetMaxHp($row['max_hp']);
			$this->resistance = $row['resistance'];
			$this->agility = $row['agility'];
			$this->fatigue = $row['fatigue'];
			$this->max_fatigue = $row['maxFatigue'];
			$this->accuracy = $row['accuracy'];
			//$this->creating_scores = $row['creating_scores'];
		}
	}

	public function setProfessionId($prof_id)
	{
	    $this->profession = $prof_id;
	    
	    // that is need to be writed as insert and update ..... .
	    
	    
	}
    public function GetProfession()
    {
        switch ($this->GetProfessionId())
        {
            case profession::WEAPONMASTER:
                return "Оружейник";
            break;
        }
    }
        
    public function GetProfessionId()/*:int*/
    {
        return $this->profession;
    }

    public function GetProfessionSkill()/*:int*/
    {
        return $this->skill;
    }
        
    public function SkillUp()
    {
    	++$this->skill;
            
        $this->UpdateProfessionSkill();
    }
        
    public function UpdateProfessionSkill()
    {
        $player_id=$this->GetId();
           
        $prof_id=$this->GetProfessionId();
        if (!$prof_id)
            return;
        
        $conn = $this->connect();
        $sth = $conn->prepare(update_statements::PLAYER_UPDATE_PROFESSION_SKILL);
        $execute_params = array($this->skill, $player_id, $prof_id);
        $sth->execute($execute_params);
            
    }

    public function questCompleted($quest_id)
    {
    	$this->PlayerQuests();

    	foreach ($this->player_quests['quest_id'] as $key=>$value)
    	{
    		if ($quest_id==$value)
    		{
    			if ($this->player_quests['complete'][$key]==1)
    			{
    				return true;
    			}
    			if ($this->player_quests['complete'][$key]==-1)
    			{
    			    return -1;
    			}
    		}
    	}
    	return false;
    }

    public function getQuestIdFromList($player_internal_quest_key)
    {
        $playerQuests = $this->player_quests;
        
        if (!empty($playerQuests))
        {
            foreach ($playerQuests['quest_id'] as $key=>$value)
            {
                if ($playerQuests['complete'][$key] == quest_status::COMPLETED)
                    continue;
                if ($player_internal_quest_key==$key)
                    return $value;
                //return $value;
            }
        }
        else
            return NULL;
        
    }
    
    public function getFirstNotCompletedQuestPlayerKey()
    {
        $playerQuests = $this->player_quests;
        
        if ($playerQuests)
        {
            foreach ($playerQuests['quest_id'] as $key=>$value)
            {
                if ($this->IsQuestComplete($value))
                    continue;
                
                return $key;
            }
        }
        
    }
    
	public function PlayerQuests()
	{
		$conn=$this->connect();

		$player_id = $this->GetId();

		$sth = $conn->prepare(select_statements::PLAYER_LOAD_QUESTS);
		$execute_params =array($player_id) ;
		$sth->execute($execute_params);
                
		$x=0;

		while ($row = $sth->fetch(PDO::FETCH_ASSOC))
		{
			++$x; //why?

			$this->player_quests['quest_id'][$x]=$row['quest_id'];
			$this->player_quests['scores'][$x]=$row['scores'];
			$this->player_quests['complete'][$x]=$row['complete'];
			$this->player_quests['time_accepted'][$x]=$row['time_accepted'];
			$this->player_quests['time_finished'][$x]=$row['time_finished'];
		}
        // that can be moved to other method, GetQuests() forexample.
         if (empty($this->player_quests))
             return NULL;
         
	return $this->player_quests;		
	}

	public function IsQuestComplete($quest_id) /* :bool */
	{
        if (empty($this->player_quests))
            return false;
		foreach ($this->player_quests['quest_id'] as $key=>$value)
		{
			if ($value==$quest_id && $this->player_quests['complete'][$key]==1)
			{
                return true;	
			}
		}

	return false;
	}

	public function ModifyHp($hp)/*: void */ // when negative value it's going to else condition
	{

		$player_id = $this->GetId();
		//echo 'debug';
		if ($hp>=0)
		{
			if ($this->GetHp() < $this->GetMaxHp())
			{
                $modify_hp = $this->GetHp()+$hp; 
                	//echo 'debug';

                if ($modify_hp >= $this->GetMaxHp())
					$modify_hp = $this->GetMaxHp();

                $this->SetHP($modify_hp);
			}
		} 
		else 
		{
			$modify_hp=$this->GetHp()+$hp;

			if ($modify_hp<=0)
			{
                $this->PlayerDie(); // not impemented yet...			
			} 
			else 
			{
                $this->SetHP($modify_hp);
			}


		}	
		$this->UpdateStats();
	}

	public function GetCurrXp()/* :int */
	{
		return $this->curr_exp;
	}


	public function GetMoney()/* :int */
	{
		return $this->money;
	}

	public function GetArmor()/* :int */
	{
		return $this->armor;
	}

	public function GetResistance()/* :int */
	{
		return $this->resistance;
	}

	public function GetAccuracy()/* :int */
	{
		return $this->accuracy;
	}
    public function loadInventory()
    {
        $this->inventory = new Inventory($this->GetId());
    }
    
	public function GetAgility()/* :int */
	{
		return $this->agility;
	}

	public function GetLevel()/* :int */
	{
		return $this->level;
	}

	public function GetLocation()/* :int */
	{
		return $this->location;
	}

	public function GetSublocation()/* :int */
	{
		return $this->sublocation;
	}

	public function GetFatigue()/* :int */
	{
		return $this->fatigue;
	}

	public function SetFatigue($fatigue)/*: void */
	{
		$this->fatigue=$fatigue;
		$this->UpdatePlayer();
	}

	public function SetAccuracy($accuracy)/*: void */
	{
		$this->accuracy=$accuracy;
		$this->UpdatePlayer();
	}

	public function SetResistance($resistance)/*: void */
	{
		$this->resistance=$resistance;
		$this->UpdatePlayer();
	}

	public function SetAgility($agility)/*: void */
	{
		$this->agility=$agility;
		$this->UpdatePlayer();
	}

	public function SetMoney($money)/*: void */
	{
		$this->money=$money;
		$this->UpdatePlayer();
	}

	public function SetLevel($level)/*: void */
	{
		$this->level=$level;
		$this->UpdatePlayer();
	}

	public function SetLocation($location)/*: void */
	{
		$this->location=$location;
		$this->UpdateLocationSublocation();
	}


	public function SetSublocation($sublocation)/*: void */
	{
		//echo $sublocation;
		$this->sublocation=$sublocation;
		//echo $this->sublocation;
		$this->UpdateLocationSublocation();
	}

	public function getMaxFatigue()
	{
		return $this->max_fatigue;
	}
        
    public function modifyMoney($money) //change that method to modifyMoney
    {
            //echo $money;
        $this->money=$this->money+$money;
            //echo $this->money;
        $this->UpdatePlayer();
    }
    
	public function SetArmor($armor)/*: void */
	{
		$this->armor=$armor;
		$this->UpdatePlayer();
	}
	
	public function SetIntellect($int)/*: void */
	{
		$this->intellect=$int;
		$this->UpdatePlayer();
	}

	public function SetStamina($stm)/*: void */
	{
		$this->stamina=$stm;
		$this->UpdatePlayer();
	}
	
	public function SetCurrXp($xp)/*: void */
	{
		$this->curr_exp=$xp;
		$this->UpdatePlayer();
	}
	
	public function addExp($xp)
	{
	    if ($this->curr_exp>=ExpMgr::GetNextLvlExpValue($this->level))
	        $this->levelUp();
	    
		$this->curr_exp=$this->curr_exp+$xp;
		
		$this->UpdatePlayer();
	}
	public function isQuestFailed($quest_id)
	{
	     
	}
	public function finishQuest($quest_id, $fail=false)
	{
		//"UPDATE player_quests SET complete=? WHERE player_id=? AND quest_id=?";
		$conn=$this->connect();
    
		$player_id = $this->GetId();
        $achievMgr = new AchievmentMgr($this);
        
        
		$sth = $conn->prepare(update_statements::FINISH_QUEST);
	
		$execute_params =array(1,time(),$player_id,$quest_id) ; //TODO MAKE as bindValue!
		if ($fail)
		{
		    $execute_params =array(-1,time(),$player_id,$quest_id) ; //TODO MAKE as bindValue! //TODO make it define, or something!
		}
		
		$sth->execute($execute_params);
		if (!$fail)
		  $achievMgr->logQuestAchiev(quest_log::COMPLETE_QUEST, $quest_id);
		//$this->setLastActivityTime();
               
	}


	public function GetIntellect()/* :int */
	{
		return $this->intellect;
	}

	public function GetCurrHp()/* :int */
	{
		return $this->curr_hp;
	}

	public function GetStamina()/* :int */
	{
		return $this->stamina;
	}
	
	public function modifyFatigue($fatigue)
	{
		$new = $this->GetFatigue()+$fatigue;

		if ($new>$this->getMaxFatigue())
			return false;
        
		if ($new<=0)
		{
		    $new=0;
		}
		
		$this->fatigue=$new;
		$this->UpdateStats();

		return true;

	}

	public function setState($state)
	{
	    $this->state = $state;
	    
	    $this->UpdatePlayer();
	}
	
	public function PlayerDie()/*: void */
	{
		//die ("DIED!");                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
		$this->setState(player_state::DEAD);
	    $this->SetLocation(100); // purgatory set!
		$this->SetHP(10); // 
        if (!$this->modifyFatigue((int)$this->GetFatigue()*2))
            $this->modifyFatigue(50);// and half of fatigue
	}
	
	public function getClass()
	{
	    return $this->class;
	}
        
    public function UpdatePlayer()
    {
        $conn=$this->connect();
                 //money=?, level=?, curr_exp=? WHERE id=?
        $sth = $conn->prepare(select_statements::PLAYER_LOAD_STATS);
        $sth->bindValue(1, $this->GetId());
        $sth->execute();
        // there is called by every time, now it's best for placing that!
        ////$this->setLastActivityTime();
        if (!$sth->rowCount())
        {
            // player_id, hp, max_hp, accuracy, armor, intellect, stamina, resistance, agility, fatigue, maxFatigue, creating_scores
            $sth=$conn->prepare(insert_statements::INS_DEFAULT_PLAYERSTAT);
            $sth->bindValue(1, $this->GetId());
            $sth->bindValue(2, $this->GetHp());
            $sth->bindValue(3, $this->GetMaxHp());
            $sth->bindValue(4, $this->accuracy);
            $sth->bindValue(5, $this->armor);
            $sth->bindValue(6, $this->intellect);
            $sth->bindValue(7, $this->stamina);
            $sth->bindValue(8, $this->resistance);
            $sth->bindValue(9, $this->agility);
            $sth->bindValue(10, $this->fatigue);
            $sth->bindValue(11, $this->max_fatigue);
            $sth->execute();
        }
        
		$sth = $conn->prepare(update_statements::PLAYER_UPDATE);
		//"UPDATE `player` SET `money`=?, `level`=?, `location`=?, `curr_exp`=?, `sublocation`=?, `state`=?, `flag`=?, `sex`=?, `class`=?, online=?, lastActivityTime=? WHERE `id`=?";
		// in other case we must have some for changing name or something?
		$sth->bindValue(1, $this->GetMoney(), PDO::PARAM_INT);
		$sth->bindValue(2, $this->GetLevel(),PDO::PARAM_INT);
		$sth->bindValue(3, $this->GetLocation(),PDO::PARAM_INT);
		$sth->bindValue(4, $this->GetCurrXp(),PDO::PARAM_INT);
		$sth->bindValue(5, $this->GetSublocation(),PDO::PARAM_INT);
		$sth->bindValue(6, $this->getState(),PDO::PARAM_INT);
		$sth->bindValue(7, $this->flag,PDO::PARAM_INT);
		$sth->bindValue(8, $this->getSex(),PDO::PARAM_INT);
		$sth->bindValue(9, $this->class,PDO::PARAM_INT);
		$sth->bindValue(10, $this->online,PDO::PARAM_INT);
		$sth->bindValue(11, $this->last_activity_time,PDO::PARAM_INT);
		$sth->bindValue(12, $this->GetId(),PDO::PARAM_INT);
		//money=?, level=?, location=?, curr_exp=?, sublocation=?, state=?
		//$execute_params = array($this->GetMoney(), $this->GetLevel(), $this->GetLocation(), $this->GetCurrXp(), $this->GetSublocation(),$this->getState(),$this->flag, $this->getSex(), $this->class $this->GetId());
		$sth->execute();
		$this->UpdateStats();
		$this->UpdateProfessionSkill();
		//$this->setLastActivityTime();
    }
    
    
    
    public function getState()
    {
        return $this->state;
    }
    
    public function getClassName()
    {
        switch ($this->class)
        {
            case player_classes::HUNTER:
                return "Охотник";
            break;
            case player_classes::DETECTIVE:
                return "Детектив";
            break;
            case player_classes::READERS:
                 return "Хранитель знаний";
            break;
            case player_classes::WITCHHUNTER:
                return "Охотник на ведьм";
            break;
                
        }
    }
    
    public function getFlag()
    {
        return $this->flag;
    }
    
    public function setFlag($newFlag)
    {
        $this->flag=$newFlag;
        $this->UpdatePlayer();
    }
    
    public function levelUp()
    {        
        $this->setFlag(player_flags::LEVELUP);    
        $this->UpdatePlayer();
    }
    
	public function UpdateLocationSublocation()/*: void */
	{
		$conn=$this->connect();
		$sth = $conn->prepare(update_statements::PLAYER_UPDATE_LOCATION_SUBLOCATION);
		$sth->bindValue(1, $this->location, PDO::PARAM_INT);
		$sth->bindValue(2, $this->sublocation, PDO::PARAM_INT);
		$sth->bindValue(3, $this->GetId(), PDO::PARAM_INT);
		$sth->execute();
		//$this->setLastActivityTime();
		//var_dump($conn->errorInfo());

	}

	public function UpdateStats()/*: void */
	{
		$conn=$this->connect();
		$sth = $conn->prepare(update_statements::PLAYER_UPDATE_STATS);
		$execute_params =array($this->GetHp(), $this->GetMaxHp(), $this->GetAccuracy(), $this->GetArmor(), $this->GetIntellect(), $this->GetStamina(), $this->GetResistance(), $this->GetAgility(), $this->GetFatigue(),$this->getMaxFatigue(), $this->GetId());
        $sth->execute($execute_params);
	}
	
	public function getRumor($rumor_id) //maybe change it to haveRumor???? bool
	{
		$conn=$this->connect();

		$sth = $conn->prepare(select_statements::PLAYER_GET_RUMOR);
		$execute_params = array($rumor_id, $this->GetId());
		$sth->execute($execute_params);

		return $sth->rowCount();

	}

	public function collectRumor($rumor_id)
	{
		if ($this->getRumor($rumor_id))
		    return;
		
	    $conn=$this->connect();
        
		$sth = $conn->prepare(insert_statements::PLAYER_COLLECT_RUMOR);
		$execute_params = array($rumor_id, $this->GetId());
		$sth->execute($execute_params);
		//$this->setLastActivityTime();

	}
    
	public function isHaveQuest($quest_id)
	{
	    $current_quests = $this->PlayerQuests();
	    if (!empty($current_quests))
	    {
	        foreach ($current_quests['quest_id'] as $key=>$value)
	        {
	            if ($quest_id==$value)
	            {
	                return TRUE;
	            }
	        }
	    }
	    return FALSE;
	}
	
    public function AddQuest($quest_id)
    {
        $current_quests = $this->PlayerQuests();
        if ($this->isHaveQuest($quest_id))
            return FALSE;
           // array_push($this->player_quests, $quest_id);
            
        $conn=$this->connect();

        $player_id = $this->GetId();
        $questMgr = new QuestMgr($quest_id);
        // TODO UPDATE TO CURRENT!
        if (!$questMgr->checkRequirements($this))
        {
			//echo "Чего-то не хватает, чтобы взяться за это дело..."; //TODO DROP IT!
			return FALSE;
		}
        	
		
        $sth = $conn->prepare(insert_statements::ADD_QUEST);
            //quest_id,scores, complete, player_id 
        $execute_params =array($quest_id, '0','0',$player_id,time(),0); //TODO MAKE IT BINDVALUE!
        $sth->execute($execute_params);
        //$this->setLastActivityTime();
        
            
    return TRUE;
    }
    
    public function getLastActivityTime()
    {
        return $this->last_activity_time; // FUCK U!
    }
    
    public function GetCurrentQuestScores($quest_id)
    {
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::PLAYER_CURRENT_Q_SCORES);
        $execute_params =array($this->GetId(),$quest_id) ;
        $sth->execute($execute_params);
        $scores=0;
        while ($row=$sth->fetch(PDO::FETCH_ASSOC))
        {
            $scores = $row['scores'];
        }
        
    return $scores;
    }

    public function UpdateCurrentScores($quest_id, $scores)
    {
      	$conn = $this->connect();
       	$current_scores = $this->GetCurrentQuestScores($quest_id);
        $sth = $conn->prepare(update_statements::PLAYER_UPDATE_CURR_Q_SCORES);
        $upd_scores = $scores;
        $execute_params = array($upd_scores, $this->GetId(), $quest_id);
        $sth->execute($execute_params);
        //$this->setLastActivityTime();
    }

    public function GetInventory()
    {
    	//$inventory = new Inventory($this->GetId());

		return $this->inventory->GetInventory();

    }
    
    public function getInventoryInstance()
    {
        return $this->inventory;
    }
    
    public function getSex()
    {
        return $this->sex;
    }
    
    public function setSex($sex)
    {
        $this->sex = $sex;
        
        $this->UpdatePlayer();
        
        //print ($this->sex." playerclass sex is setted affer update <br> \n");
         
    }
        
}