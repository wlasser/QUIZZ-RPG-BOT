<?php
class ShopMgr
{
    use ConfigMgr;
    
    private $location = array();
    
    private $vendor= array();
        
    public function __construct($location) 
    {
        $this->LoadShop($location);
        
    }
    
    public function LoadShop($location)
    {
        $this->location = $location;
        $conn = $this->connect();
        
        $sth=$conn->prepare(select_statements::LOAD_SHOP);
        $execute_params = array($location);
        $sth->execute($execute_params);
        
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
                
            $this->vendor['id'][]=$row['id'];
            $this->vendor['item'][]=$row['item'];
            $this->vendor['price'][]=$row['price'];
            $this->vendor['count'][]=$row['count'];
        }
    }
    
    public function GetItemFromVendorList($id)
    {
        foreach ($this->vendor['id'] as $key=>$value)
        {
            if ($value==$id)
            {
                return $this->vendor['item'][$key];
            }
        }
    }
    
    public function GetVendorItemList()
    {
        return $this->vendor['item'];
    }
    
    public function GetPrice($id)
    {
        foreach ($this->vendor['item'] as $key=>$value)
        {
            if ($value==$id)
                return $this->vendor['price'][$key];
        }
    }
    
    
    
}

