<?php
class Creature extends Abstract_class
{
    use ConfigMgr;

    private $id, $name, $hp, $max_hp;
    private $type;
    private $description, $loot_id, $normal_damage, $minDamage, $maxDamage, $damage_school, $sickness_type, $loose_fatigue ;
    
    private $resistance = array();
    private $resistance_type = array();
    
   // private $drop_list = array();

    public function __construct($id) /*void:*/
    {
        $this->LoadCreature($id);
    }
     
    public function GetId()
    {
        return $this->id;
    }
    
    public function getResistanceTypes()
    {
        return $this->resistance_type;
    }
    
    public function getResisance()
    {
        return $this->resistance;
    }
    
    
    public function LoadCreature($id) /*void:*/
    {
        $conn = $this->connect();

    	$sth = $conn->prepare(select_statements::LOAD_CREATURE_BY_ID);
    	$execute_params =array($id) ;
    	$sth->execute($execute_params);
        
            while ($row = $sth->fetch(PDO::FETCH_ASSOC))
            {
                $this->id = $id;
                $this->SetName($row['name']);
                $this->SetHP($row['hp']);
                $this->SetMaxHp($row['hp']); // wait i dont know how to change hp value when battle. !!!
                $this->loot_id= $row['loot_id'];
                $this->description= $row['description'];
                $this->minDamage = $row['minDamage'];
                $this->maxDamage = $row['maxDamage'];
                $this->damage_school = $row['damage_school'];
                $this->type=$row['type'];
                $this->normal_damage=$row['normal_damage'];
                $this->sickness_type = $row['sickness_type'];
                $this->loose_fatigue = $row['looseFatigue'];

                for ($x=1;$x<=3;++$x)
                {
                    $this->resistance[$x]=$row['resistance'.$x];
                    $this->resistance_type[$x]=$row['resistance_type'.$x];
                }
              
            }
    }
    
    public function getLooseFatigue()
    {
        return $this->loose_fatigue;
    }
    
    public function GetType()/*:int*/
    {
        return $this->type;
    }
    
    public function GetTypeCaption()/* :string */
    {
        switch ($this->type){
            case creature_type::HUMANOID:
                return "Человек";
                break;
            case creature_type::GHOST:
                return "Призрак";
                break;
            case creature_type::WEREWOLF:
                return "Оборотень";
                break;
        }
    }

    public function GetDescription()/*:string*/
    {
        return $this->description;
    }
    
    public function GetResistance($id)/*:int*/
    {
        return $this->resistance[$id];
    }
    
    public function GetResistanceType($id)
    {
        return $this->resistance_type[$id];
    }
    
    public function GetMagicDamage()
    {
        return $this->damage;
    }
    
    public function GetLootId()
    {
        return $this->loot_id;
    }
    
    public function GetDamageSchool()
    {
        return $this->damage_school;
    }
    
    public function getMinDamage()
    {
        return $this->minDamage;
    }
    
    public function getMaxDamage()
    {
        return $this->maxDamage;
    }
    
    public function GetNormalDamage()
    {
        return $this->normal_damage;
    }
}