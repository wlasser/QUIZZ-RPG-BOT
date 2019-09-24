<?php
class BattleMgr
{
	use ConfigMgr;
	private $player, $npc;
	
	 // tested fast use on purgatory it's deal damage without weapon // purgatory didn't work at is!
	 
	 //that maybe use it like private?
	private $battle_id , $who_start, $step, $enemy_id, $enemy_hp, $last_damage,  $player_id, $state, $start_time, $end_time;

	public function __construct(Player $player, Creature $npc)
	{
		$this->initBattleField($player, $npc);
		
		$this->player=$player;
		$this->npc = $npc;
	}

	public function initBattleField()
	{
	   // we can modify this state with buffs or cards????
		$this->who_start = rand(1,2);
		//----------------------------
		//$this->player = $Player;
		//$this->npc = $Npc;
		$this->step = 0;
		$this->player_id = $this->player->GetId();
		$this->enemy_id = $this->npc->GetId();
		$this->state = 0;
		$this->start_time = time();
		$this->enemy_hp = $this->npc->GetHp();
		$this->last_damage = 0; // can be reused by some feauture...
		
		////battleid, who_start, player_id, enemy_id, step, state, start_time, end_time
		
		$conn = $this->connect();
		//player_id=? state=?
		//echo ""."";
		//printf("<b>Npc ID : %s </b><br>",$this->enemy_id);
		$sth = $conn->prepare(select_statements::LOAD_PLAYER_BATTLE);
		$sth->bindValue(1, $this->player_id, PDO::PARAM_INT);
		$sth->bindValue(2, $this->enemy_id, PDO::PARAM_INT);
		$sth->execute();
		//echo "<b>".$sth0->rowCount()."</b><br>";
		if (!$sth->rowCount())
		{
    		$this->startNewBattle();
		}
		
		else
		{
		    $this->continueBattle();
		}
	}
		
	public function getStartTime()
	{
	    return $this->start_time;		    	    
	}
		
	public function checkForLeave()
	{
	    $current_time=time();
	    $time_too_check = strtotime("+5 min",$this->getStartTime());
	    if ($current_time>=$time_too_check&&!$this->end_time)
	        return TRUE;
	    
	return FALSE;
	}

	public function startNewBattle()
	{

        $conn = $this->connect();
		$sth = $conn->prepare(insert_statements::INIT_BATTLE);
		$sth->bindValue(1, $this->who_start, PDO::PARAM_INT);
		$sth->bindValue(2, $this->player_id, PDO::PARAM_INT);
		$sth->bindValue(3, $this->enemy_id, PDO::PARAM_INT);
		$sth->bindValue(4, $this->enemy_hp, PDO::PARAM_INT);
		$sth->bindValue(5, $this->last_damage, PDO::PARAM_INT);
		$sth->bindValue(6, $this->step, PDO::PARAM_INT);
		$sth->bindValue(7, $this->state, PDO::PARAM_INT);
	    $sth->bindValue(8, $this->start_time, PDO::PARAM_INT);
		$sth->execute();
	}
		
	public function continueBattle()
	{
        $this->loadBattle();
		    
        if ($this->state==battle_state::NOT_COMPLETE)
		{
            $isLeave = $this->checkForLeave();
		    
		    if ($isLeave)
		    {
                $this->state=battle_state::LEAVE;
		        $this->updateBattle();
		        return battle_errors::BATTLE_LEAVE;
		    }
		}
		    
		// TODO dat is maybe not necessary!    
		if ($this->state==battle_state::LEAVE)
		{
            return battle_errors::BATTLE_LEAVE;
		}
		    
		if (!$this->canStartNewBattle())
		{
            switch ($this->state)
		    {
                case battle_state::LOOSE:
		        case battle_state::WIN:
                    return battle_errors::BATTLE_FINISHED;
                break;
		    }
		}
        else
        {
            $this->startNewBattle();
        }   
	}
		
	public function canStartNewBattle()
	{
	    $this->loadBattle();
	    switch ($this->state)
	    {
	        case battle_state::LOOSE:
	        case battle_state::WIN:
	            if (time()>=strtotime('+15 min', $this->end_time))
	                return true;
	        break;
	    }
		    
	    return false;
	}
	public function makeStep($weapon_total_id=0, $bullet_total_id=0) //?????????
	{
	    $this->loadBattle($this->player, $this->npc);
	    $step = $this->step;
	    $who_start = $this->who_start;
		switch ($who_start)
		{
            case battle_side::PLAYER:
    		    $this->playerStep($weapon_total_id, $bullet_total_id);
    		    $this->checkPlayerState(); // there is maybe unnecesarry
		    break;
		    case battle_side::ENEMY:
                $this->npcStep();
                $this->checkPlayerState(); // there is maybe unnecesarry
            break;
        }      
	}
		
	public function checkPlayerState()
	{
	    // maybe move it to another check, forexample battle_check???
	    // that looks like shitty shit????!!!!
	    switch ($this->player->getState())
	    {
	        case player_state::DEAD:
	        case player_state::PURGATORY:
	            $this->finishBattle(battle_state::LOOSE);
	        break;
	    }
	}
		
	public function getPlayer()
	{
	    return $this->player;
	}
		
	public function getNpc()
	{
	    return $this->npc;
	}
		
	public function playerStep($weapon_total_id=0, $bullet_total_id=0)
	{
	    //printf("Оружиие: %s <br> \n Патроны: %s <br> \n",$weapon_total_id, $bullet_total_id);
	    // can add some feautere with mod this param
	    $this->who_start = 2;
	    ++$this->step;
	    
	    //that look like someshit at that moment :
	    // we can move it on $player->getInventory and there is been private array with instance of inv
	    $inventory = $this->getPlayer()->getInventoryInstance(); 
	    $weapon = new Item($inventory->GetItemIdFromTotalId($weapon_total_id));
	    $additional_damage = 0;
	    $resistance_type = $this->npc->getResistanceTypes();
	    $resistance = $this->npc->getResisance();    
		if ($bullet_total_id>0)
		{
            $bullet = new Item ($inventory->GetItemIdFromTotalId($bullet_total_id));
            if ($weapon->checkForBullets($inventory, $weapon) && !$bullet_total_id || !$inventory->HaveItemInInventory($bullet->GetId()))
                return battle_errors::SELECT_BULLET;
		    $additional_damage_min=$bullet->GetMinDmg();
		    $additional_damage_max = $bullet->GetMaxDmg();
		    $additional_damage = rand($additional_damage_min, $additional_damage_max);
		        
		    $bullet_school_type = $bullet->getSpecialType();
		        
		    foreach ($resistance_type as $key=>$value)
		    {
                if ($value==spec_types::NORMAL)
                    $reduce = $resistance[$key];
		    }
		        
		    $additional_damage = (int)$additional_damage/$reduce;
		        
		    $result = $inventory->UseItem(0, $this->player, $bullet->GetId());
		        
		    if ($result==item_use_result::INVENTORY_ERROR || $result==item_use_result::ITEM_EMPTY)
                return battle_errors::NO_BULLETS;
		        
        }
		    
		$result = $inventory->UseItem(0, $this->player, $weapon->GetId());
        if ($result==item_use_result::INVENTORY_ERROR || $result==item_use_result::ITEM_EMPTY)
            return battle_errors::CANT_USE_WEAPON;
		    
		$damageMin = $weapon->GetMinDmg();
		$damageMax = $weapon->GetMaxDmg();

		$weapon_school_type = $weapon->getSpecialType();
		    
		foreach ($resistance_type as $key=>$value)
		{
            if ($value==spec_types::NORMAL)
                $reduce = $resistance[$key];
		}
		    
		$damageMin = $additional_damage+$damageMin;
		$damageMax = $additional_damage+$damageMax;   
		    
		     //we can reduce min max... that's look intresting
		$damage = rand($damageMin, $damageMax);
		    
		$damage = (int)$damage/$reduce;
		    
		$loose_hp = $this->enemy_hp-$damage;
		$this->checkPlayerState();
		    
		if ($loose_hp<=0)
		{
            $this->enemy_hp=0;
		    $this->finishBattle(battle_state::WIN);
		    $this->player->ModifyFatigue($this->npc->getLooseFatigue());
        }
		else
            $this->enemy_hp=$loose_hp;
		    
		$this->updateBattle();
		    
		$return_values = array('player_hp'=>$this->getPlayer()->GetHp(),'enemy_hp'=>$this->enemy_hp);
		    
	return $return_values;	    
	}
		
	   
		
	public function finishBattle($state)
	{
	    $this->end_time = time();
	    $this->state = $state;
	    $this->updateBattle();
	}
		
	public function npcStep()
	{
	    $this->who_start=1;
	    ++$this->step;
	    //printf("Ход NPC");
	    $player_hp = $this->getPlayer()->GetHP();
	    $npcMinDamage = $this->getNpc()->getMinDamage();
        $npcMaxDamage = $this->getNpc()->getMaxDamage();
        $damage = rand($npcMinDamage, $npcMaxDamage);
        $this->getPlayer()->ModifyHp(-$damage);
        $this->checkPlayerState();
		$this->updateBattle();
		   
		$return_values = array('player_hp'=>$this->getPlayer()->GetHp(),'enemy_hp'=>$this->enemy_hp);
		    
	return $return_values;
	}
		
	public function updateBattle()
	{
	    $conn = $this->connect();
	    // "UPDATE player_battle SET who_start=?,enemy_hp=?,last_damage=?,step=?, state=?";
	    $sth = $conn->prepare(update_statements::BATTLE_UPDATE);
	    $sth->bindValue(1, $this->who_start, PDO::PARAM_INT);
	    $sth->bindValue(2, $this->enemy_hp, PDO::PARAM_INT);
	    $sth->bindValue(3, $this->last_damage, PDO::PARAM_INT);
	    $sth->bindValue(4, $this->step, PDO::PARAM_INT);
	    $sth->bindValue(5, $this->state, PDO::PARAM_INT);
	    $sth->bindValue(6, $this->end_time, PDO::PARAM_INT);
	    $sth->bindValue(7, $this->battle_id, PDO::PARAM_INT);
	    $sth->execute();    
	}
		
	public function loadBattle()
	{
	    $player_id =$this->player_id;
	    $enemy_id = $this->getNpc()->GetId();
	    $conn = $this->connect();
	    $sth = $conn->prepare(select_statements::LOAD_PLAYER_BATTLE);
	    //player_id=? AND enemy_id=? AND state=?
	    $sth->bindValue(1, $player_id, PDO::PARAM_INT);
	    $sth->bindValue(2, $enemy_id, PDO::PARAM_INT);
	    $sth->execute();
	    while ($row=$sth->fetch(PDO::FETCH_ASSOC))
	    {
	        $this->battle_id = $row['battle_id'];
	        $this->who_start = $row['who_start'];
	        $this->start_time = $row['start_time'];
	        $this->enemy_id = $row['enemy_id'];
	        $this->step = $row['step'];
	        $this->player_id = $row['player_id'];
	        $this->enemy_hp = $row['enemy_hp'];
	        $this->last_damage = $row['last_damage'];
	        $this->state = $row['state'];
	        $this->end_time = $row['end_time'];
	    }
		    
	}
		
}