<?php
class SublocationMgr
{
	use ConfigMgr;

	public $sublocations_list = array();
	
	public function __construct($location)
	{
		$this->loadSublocations($location);
	}

	public function getListForLocation()
	{
		return $this->sublocations_list;
	}


	public function loadSublocations($location)
	{
		$conn = $this->connect();
        
        $sth=$conn->prepare(select_statements::GET_SUBLOCATION);
        $execute_params = array($location);
        $sth->execute($execute_params);
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
          	$this->sublocations_list['id'][]=$row['id'];
           	$this->sublocations_list['name'][] = $row['name'];
           	$this->sublocations_list['fatigue_loose'][]=$row['fatigue_loose'];
           	$this->sublocations_list['isShop'][]=$row['isShop'];

        }
	}		

	public function getFatigueLoose($id)
	{
		foreach ($this->sublocations_list['id'] as $key=>$value)
		{
			if ($id == $value)
				return $this->sublocations_list['fatigue_loose'][$key]; //there is terrible, need to change it or? or not.
		}
	}
	public function getSublocationNameFromId($id)
	{
		foreach ($this->sublocations_list['id'] as $key=>$value)
		{
			if ($id==$value)
				return $this->sublocations_list['name'][$key];
		}
	}


}