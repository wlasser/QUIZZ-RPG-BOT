<?php
class Item
{
    use ConfigMgr;
    
	private $id, $name, $description, $min_dmg, $max_dmg, $special, $charges, $icon, $type, $specType, $price_buy, $price_sell;
    private $stackable, $max_count;
        
	private $param = array();
	private $param_type = array();


	public function __construct($id)/* :void */
	{
		$this->LoadItem($id);
	}

	public function LoadItem($id)/*: void */
	{
		if (!$id)
                    return NULL;

		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::LOAD_ITEM);
		$execute_params =array($id) ;
		$sth->execute($execute_params);
				
			while ($row = $sth->fetch(PDO::FETCH_ASSOC))
			{
				$this->id=$id;
				$this->name=$row['name'];
				$this->description=$row['description'];
				$this->min_dmg=$row['min_dmg'];
				$this->max_dmg=$row['max_dmg'];
				$this->special=$row['special'];
				$this->charges=$row['charges'];
				$this->icon=$row['icon'];
				$this->type=$row['type'];
                $this->stackable = $row['stackable'];
                $this->max_count = $row['max_count'];
                $this->price_buy = $row['price_buy'];
                $this->price_sell = $row['price_sell'];
                $this->specType = $row['specType'];
                                
					for ($x=1;$x<=4;++$x)
					{
                        if (!$row['param_'.$x])
							continue;
                        $this->param[$x]=$row['param_'.$x];
					}

					for ($x=1;$x<=4;++$x)
					{
                       	if (!$row['param_type_'.$x])
							continue;
                     	$this->param_type[$x]=$row['param_type_'.$x];
					}

			}
	}

	public function GetParamType()
	{
		return $this->param_type;
	}
	public function GetParamValue()
	{
		return $this->param;
	}

	public function isWeapon($Inventory)
	{
	    //$inventory=new Inventory();
		if ($this->type==item_types::MEELEE)
		{
			return true;
		}
		
		if ($this->type==item_types::GUN)
		{
		    if ($this->checkForBullets($Inventory, $this))
		        return true;
		    //const PISTOL_BULLETS = 10;
		    
		}
	}
	
	
	
    
	public function checkForBullets($inventory, $Item)
	{
	    $type = $Item->GetType();
	    $inventory_array = $inventory->GetInventory();
	    $ammo_array = array();
	    
	       switch ($type)
           {
               case item_types::GUN:
                   foreach ($inventory_array['item_id'] as $key=>$value)
                   {
                       //echo $value;
                       $bullet = new Item($value);
                       
                       if ($bullet->GetType()==item_types::PISTOL_BULLETS)
                           $ammo_array['total_id'][] = $inventory_array['total_id'][$key];
                       //else $ammo_array['total_id'][]=0;
                           //return true;
                       
                   }
               break;
           }
           return $ammo_array;
	}
	
	
	public function GetDescription()/* :string */
	{
		return $this->description;
	}

	public function GetId()/* :int */
	{
		return $this->id;
	}

	public function GetName()/* :string */
	{
		return $this->name;
	}
        
    public function IsStackable()
    {
        return $this->stackable;
    }
        
    public function GetMaxCount()
    {
        return $this->max_count;
    }
        
	public function GetType()/* :int */
	{
		return $this->type;
	}

	public function getSpecialType()
	{
		return $this->specType;
	}

	public function GetCharges()/* :int */
	{
		return $this->charges;
	}

	public function GetMinDmg()/* :int */
	{
		return $this->min_dmg;
	}

	public function GetMaxDmg()/* :int */
	{
		return $this->max_dmg;
	}

	public function GetIcon()/* :string */
	{
		if ($this->icon)
                    return "images/icons/".$this->icon;
		else
                    return "images/icons/default.png"; // dont have it!
	}

	public function GetSpecial()/* :boolean */ // rename it it IsSpecial()
	{
		return $this->special;
	}
        
        public function GetPriceSell()
        {
            return $this->price_sell;
        }
        
        public function GetPriceBuy()
        {
            return $this->price_buy;
        }
}