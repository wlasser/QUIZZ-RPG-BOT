<?php
class Inventory
{
    use ConfigMgr;
	/*
            need to limit inventory. make define with max_inventory_slots
            implement pickup items and check it. update count? need to set max_count of items
         *  there is comming soon...
         *  
         *  now i add inventory instance to player class! that can be some new usage with it, and need to restruct many things
         *  
	*/
    
    // TODO: REWRITE THAT AS SOON AS POSSIBLE!
    private $player_id;

	private $inventory = array();
	public $inventoryJson=null;

	public function __construct($player_id)/* :void */
	{
	    $this->setPlayerId($player_id);
		$this->LoadInventory($player_id);
	}
	
	private function setPlayerId($player_id)
	{
	    $this->player_id = $player_id;
	}

	public function LoadInventory($player_id)/*: void */
	{
		if (!$player_id)
            return NULL;

		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::LOAD_INVENTORY);
		$execute_params =array($player_id) ;
		$sth->execute($execute_params);
		//$inventory = array();
		
       /*  if (!$sth->rowCount())
            return 0; */
        // json object?
        // ................................
		while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            
            $this->inventory['total_id'][]=$row['total_id'];
			$this->inventory['item_id'][]=$row['item_id'];
			$this->inventory['count'][]=$row['count'];
			$this->inventory['charges'][]=$row['charges']; //what the fuck???
                        //$this->inventory['position'][]=$row['position'];\
			$this->inventoryJson = json_encode($this->inventory);
        }
	}

	public function GetInventory()/*:array*/
	{
		return $this->inventory;
	}
    
	public function getFreeSlots()
	{
	    if (empty($this->inventory))
	        return $this->GetInventorySize();
	   return $this->GetInventorySize()-count($this->inventory['item_id']); // mb this rewrite for best view
	}
	
    public function CheckInventory()
    {
           // echo count($this->inventory['item_id']);
        if (count($this->inventory['item_id'])>=$this->GetInventorySize())
            return false;
           
    return true;
    }
        
	public function Charge($item_id)/*: void */
	{

		$conn = $this->connect();

		$sth = $conn->prepare(update_statements::UPDATE_CHARGE);
		// charges=? WHERE player_id=? AND item_id=?
		foreach ($this->inventory['item_id'] as $key=>$value)
        {
			if ($value==$item_id)
            {
                if ($this->inventory['charges'][$key]==0)
                {
                    $this->RemoveFromInventory($this->inventory['total_id'][$key]);
                    return;
                }
                else 
                {
					$this->inventory['charges'][$key]=$this->inventory['charges'][$key]-1;
					$execute_params =array($this->inventory['charges'][$key], $this->player_id, $this->inventory['total_id'][$key]);
					$sth->execute($execute_params);
					return;
				//$this->UpdateInventory();
                }
			}
		}
	}

	public function UpdateInventory($total_id)/*: void */
	{
        $conn = $this->connect();

		$sth = $conn->prepare(update_statements::UPDATE_INVENTORY);
                
        $update_item;
                
        foreach ($this->inventory['total_id'] as $key=>$value)
        {
            if ($value==$total_id)
                $update_item=$key;
        }
                    
        $execute_params = array(
                     $this->inventory['item_id'][$update_item],
                     $this->inventory['count'][$update_item],
                     $this->inventory['charges'][$update_item],
                     $this->player_id, 
                     $total_id);
                
        $sth->execute($execute_params);
	}
        //        fix it!!!!
        // как определить тотал_ид извне?
        // 2 - может быть 10 и 28
        // как определить какой именно?
        public function GetCount($total_id)
        {
            
	        foreach ($this->inventory['total_id'] as $key=>$value)
	        {
	            //$key = array_search($total_id, $this->inventory['total_id']);
	            
	            if ($value==$total_id)
	            {
	                return $this->inventory['count'][$key];
	            }
			}
        return NULL;
        }
    // that method can be fixed, buy remove $total_id=0, but i don't know how to do it now...    
	public function UseItem($total_id=0, Player &$player, $item_id=0) //maybe fix it with some changes? let's try...
	{
            // допустим там будет два значения 1, 12
            // как выбрать нужный?
            // как узнать позицию. для этого надо 
            // получить плеерид, тотал_ид
            // как узнать тотал_ид?
        if ($item_id)
        {
            $total_id = $this->GetTotalId($item_id);
        }
            
	    $item=new Item($this->GetItemIdFromTotalId($total_id));
        
        $skillGain=false;
        $result = 0;
		$item_id = $item->GetId();
		$charges=$this->GetCharges($total_id);
        $count = $this->GetCount($total_id);
		$type = $item->GetType();
		$param_type=$item->GetParamType();
		$param = $item->GetParamValue();

		if ($this->IsSpecial($item) && $charges==0)
        {
            // f that happens, need to pass...
            return item_use_result::ITEM_EMPTY;
        }

		if ($charges>0)
        {
          $this->Charge($item_id);
		  return item_use_result::ITEM_USED;
		}

		if ($charges==0 && !$this->IsSpecial($item))
        {
            switch ($type)
            {
                default:
                    $result=1;
                 break;
			     case item_types::FOOD:
                    for ($x=1;$x<=count($param_type);++$x)
                    {
				        switch ($param_type[$x])
                        {
                            case food_param_types::REST_HP:
                                $player->ModifyHp($param[$x]);
                                $result = 1; //what is a magic number????
						    break;
					        case food_param_types::REST_FATIGUE:
						        $player->ModifyFatigue($param[$x]);
                                $result = 1; //what is a magic number????
						    break;
				        }
                    }
            break;
                 case item_types::REAGENT_WEAPONMASTER:
                    if ($player->GetProfessionId()==profession::WEAPONMASTER && $this->PrepareForWork())
                    {
                        switch ($item->GetId())
                        {
                            case 9:
                                $get_item = $this->GetRandomItem($player);
                                $new_item = new Item($get_item);
                                $result = $this->PickupItem($new_item);
                                $skillGain = true;
                            break;
                            case 15:
                                $get_item=18;
                                $new_item=new Item($get_item);
                                $skillGain = false;
                                $result = $this->PickupItem($new_item);
                            break;
                        }
                    }
                    elseif (!$this->PrepareForWork()) 
                    {
                        return "Необходим любой складной нож!"; //fix me
                    }
                    else 
                    {
                        return "Нужно иметь профессию оружейника!";     // fix me                       
                    }
                break;
                case item_types::PROF_MISC:
                    if ($item->GetId()==10 || $item->GetId()==11)
                    {
                        $total_id = $this->GetTotalId($item->GetId());
                                    // need to implement some like recipe system for that...
                        if ($this->HaveItemInInventory(11) && $this->HaveItemInInventory(10))
                        {
                                        // need to rewrite it, first...
                            if ($item->GetId()==10)
                            {
                                $secondItem = $this->GetTotalId(11);
                            }
    //                                        $secondItem=new Item(11);
                            if ($item->GetId()==11)
                            {
                                $secondItem = $this->GetTotalId(10);
                            }
    //                                        $secondItem = new Item(10);
                            $skillGain = false;
                            echo $total_id;
                            $result = $this->PickupItem($this->Combinate($total_id, $secondItem)); //fix it!!!
                        }
                    }
                break;
            }
            
            if (!$result)
            {
                return item_use_result::INVENTORY_ERROR;
            }
            
            if ($result<>inventory_result::ERROR_MAX_COUNT)
            {
                $this->RemoveFromInventory($total_id);
                    
                if ($skillGain)
                {
                    $player->SkillUp();
                }
                
                return item_use_result::ITEM_USED;
            }
            else
            {
                return item_use_result::INVENTORY_ERROR;
            }
        }
	}

        public function useBullets($Item, $Player)
        {
            $type = $Item->GetType();
            
            switch ($type)
            {
                case item_types::GUN:
                    foreach ($this->inventory['item_id'] as $key=>$value)
                    {
                        $bullet = new Item($value);
                         
                        if ($bullet->GetType()==item_types::PISTOL_BULLETS)
                            $this->UseItem(0,Player, $value);
                            //return true;
                             
                    }
                    break;
            }
        }

        public function GetItemIdFromTotalId($total_id) /*:int*/
        {
//            $key = array_search($total_id,$this->inventory['total_id']);
//                if ($key){
//                    return $this->inventory['item_id'][$key];
//                    echo $this->inventory['item_id'][$key];
//                }
                
                foreach ($this->inventory['total_id'] as $key=>$value){
                    if ($total_id==$value){
                        return $this->inventory['item_id'][$key];
                    }
                }
        }
        
        
    public function GetTotalId($item_id)
    {
        $total_id = array();
            
        foreach ($this->inventory['item_id'] as $key=>$value)
        {
            if ($item_id==$value)
            {
                $total_id[]=$this->inventory['total_id'][$key];
            }
        }
            
        foreach ($total_id as $key=>$value)
        {
            $needle = $value;
        }
            
    return $needle;
    }
        
    public function PrepareForWork()
    {
        if ($this->HaveItemInInventory(19))
        {
            return TRUE;
        }
    return FALSE;
    }

    public function checkForBullets($Item)
    {
        $type = $Item->GetType();
        //return true;
           switch ($type)
           {
               case item_types::GUN:
                   foreach ($this->inventory['item_id'] as $key=>$value)
                   {
                       $bullet = new Item($value);
                       
                       if ($bullet->GetType()==item_types::PISTOL_BULLETS)
                           return true;
                       
                   }
               break;
           }
           
        //const PISTOL_BULLETS = 10;
        //foreach ($this->inventory[''])
        
    }
    
    public function Combinate($total_id_1, $total_id_2) //fix it!
    {
            // there is need get ID from item, and get total_id from it.

        $firstItem=new Item($this->GetItemIdFromTotalId($total_id_1));
        $secondItem = new Item($this->GetItemIdFromTotalId($total_id_2));
            
        $this->RemoveFromInventory($total_id_2);
            // there is hardcode again, need to rewrite something...
        if ($firstItem->GetId()==10||11 && $secondItem->GetId()==11||10)
        {
            $result = new Item(13);
        }
                
    return $result;
    }
        
    public function GetRandomItem(Player &$player)
    {
            //$get_item;
            
        $chance =$player->GetProfessionSkill();
        $rand = mt_rand(mt_rand(1,$this->GetChanceModifer()),$chance);

        if ($rand < 20)
            $get_item=12;
        if ($rand>=20)
            $get_item=10;
        if ($rand>=35)
            $get_item=11;
                
    return $get_item;
    }
        
	public function RemoveFromInventory($total_id, $bool=false)/*: void */
	{
        $inventory = $this->GetInventory();
        $item = new Item($this->GetItemIdFromTotalId($total_id));
                
        if ($bool)
        {
            $this->CompleteRemove($total_id);
        }
                
                
        if ($this->GetCount($total_id)>1 && $item->IsStackable())
        {
                    //$key = array_search($item->GetId(), $inventory['item_id']);
            foreach ($inventory['total_id'] as $key=>$value)
            {
                if ($value==$total_id)
                {
                    --$this->inventory['count'][$key];
                    $this->UpdateInventory($this->inventory['total_id'][$key]);
                return;
                }
            }
        }
        else 
        {
            $this->CompleteRemove($total_id);
        }

	}
        
    public function CompleteRemove($total_id)
    {
        $conn = $this->connect();
        $sth = $conn->prepare(delete_statements::REMOVE_ITEM);
        $execute_params =array($this->player_id, $total_id);
        $sth->execute($execute_params);
    }

	public function IsSpecial(Item &$item)/* :boolean */
	{
		if ($item->GetSpecial())
			return true;

	return false;
	}
        
    public function BuyItem($item, $player)
    {
            //$item = new Item($item_id);
           // echo $item->GetId();
            //echo $player->GetId();
        $item_id = $item->GetId();
        $price = $item->GetPriceBuy($item_id);
           //echo $price;
//           //echo $item_id;
        $money = $player->GetMoney();
           // echo $money;
        if ($money>=$price)
        {
            $this->AddItemById($item_id);
            $player->modifyMoney(-$price);
        }
        else
            echo 'недостаточно денег!';
    }
        
    public function AddItemById($item_id)
    {
        $item = new Item($item_id); // there is need to be fixed, coz some guys think it's wrong. but how?
        $this->PickupItem($item);
    }
        

	public function GetCharges($total_id)/* :int */
	{
           // $key = array_search($total_id, $this->inventory['total_id']);
		foreach ($this->inventory['total_id'] as $key=>$value)
		{
			if ($total_id==$value)
            {
                return $this->inventory['charges'][$key];
            }
		}
	}
        
    public function HaveItemInInventory($item_id)/* :boolean */
    {
        if (empty($this->inventory))
            return 0;
        
        foreach ($this->inventory['item_id'] as $key=>$value)
        {
            if ($value==$item_id)
            {
                return true;
            }
        }
                        
    return false;
    }
        
    public function GetFirstNotFulled(Item &$item)
    {
        $item_id = $item->GetId();
            
        $keys=array();
        
       if (empty($this->inventory))
            return -1; 
        
        foreach ($this->inventory['item_id'] as $key=>$value)
        {
            if ($item_id==$value)
            {
                $keys[]=$key;
            }
        }
            
        foreach ($keys as $key=>$value)
        {
            if ($this->GetCount($this->inventory['total_id'][$value])<$item->GetMaxCount())
            {
                return $this->inventory['total_id'][$value];
            }
            else
            {
                continue;
            }
        }
    return -1;
    }
        
    public function PickupItem(Item &$item) //fix it, fix it again and better
    {
        $item_id = $item->GetId();
        $inventory = $this->GetInventory();
 
        $freeItem = $this->GetFirstNotFulled($item);
        
            
        if($this->CheckInventory())
        {
                //$key = array_search($free_item, $this->inventory['item_id']);
            if (!$this->HaveItemInInventory($item_id) || $freeItem==-1)
            {
                $this->AddToInventory($item);
                return inventory_result::ADDED_NEW;
            }
            
            foreach ($inventory['total_id'] as $key=>$value)
            {
                if ($value==$freeItem && $item->IsStackable())
                {
                    if ($this->inventory['count'][$key]<$item->GetMaxCount())
                    {
                        ++$this->inventory['count'][$key];
                        $this->UpdateInventory($this->inventory['total_id'][$key]);
                        return inventory_result::ADDED;
                    }
                    elseif ($this->CheckInventory()) 
                    {
                        $this->AddToInventory($item);
                        return inventory_result::ADDED_NEW;
                    }
                    else
                    {
                        return inventory_result::ERROR_MAX_COUNT;
                    }
                }
                
            }
        }
    }
        
    public function AddToInventory(Item &$item)
    {
            
        if ($this->CheckInventory())
        {
            $conn = $this->connect();
            $sth = $conn->prepare(insert_statements::ADD_TO_INVENTORY);
                //// total_id, player_id, item_id, count, charges, position
            $execute_params =array($this->player_id, $item->GetId(),'1', $item->GetCharges()); // 1 is mean just one of count?
            $sth->execute($execute_params);
        }
        else
        {
            die('Can not add to inventory, coz is full!');
        }
            
    }
        
    public function GetLastPosition() // TODO not used!
    {
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::GET_LAST_POSITION);
        $execute_params = array($this->player_id);
        $sth->execute($execute_params);
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            $last_pos = $row['MAX(position)'];
        }
                
        return $last_pos;    
    }
}