<?php

class VehicleMgr
{
    use ConfigMgr;
    
	private $id, $name, $fuel, $speed, $comfort, $type;

	public function __construct($id)/* :void */
	{
		$this->LoadVehicle($id);
	}
        
        public function isHaveVehicle($player)
        {
         //   if ()
        }
	public function LoadVehicle($id)/*: void */
	{
		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::LOAD_VEHICLE);
		$execute_params =array($id) ;
		$sth->execute($execute_params);

			while ($row=$sth->fetch(PDO::FETCH_ASSOC)){
				$this->id = $id;
				$this->name=$row['name'];
				$this->fuel=$row['fuel'];
				$this->speed=$row['speed'];
				$this->comfort=$row['comfort'];
				$this->type=$row['type'];
			}
	}

	public function GetName()/* :string */
	{
		return $this->name;
	}

	public function GetMaxFuel()/* :int */
	{
		return $this->fuel;
	}

	public function GetSpeed()/* :int */
	{
		return $this->speed;
	}

	public function GetComfort()/* :int */
	{
		return $this->comfort;
	}

	public function GetType()/* :int */
	{
		return $this->type;
	}



}