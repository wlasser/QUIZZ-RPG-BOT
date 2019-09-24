<?php
abstract class Abstract_class
{
    protected $_hp;
    protected $_name;
    protected $_max_hp;
    protected $_id;
    
    public function SetId($id)
    {
        $this->_id=$id;
    }
    
    public function GetId()
    {
        return $this->_id;
    }


    public function SetHP($hp)
    {
        $this->_hp=$hp;
    }
    
    public function SetName($name)
    {
        $this->_name=$name;
        
    }
    
    public function SetMaxHp($max_hp)
    {
        $this->_max_hp=$max_hp;
    }
    
    public function GetHp()
    {
        return $this->_hp;
        
    }
    
    public function GetMaxHp()
    {
        return $this->_max_hp;
    }
    
    public function GetName()
    {
        return $this->_name;
    }
}