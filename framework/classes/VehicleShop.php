<?php
class VehicleShop
{
    use ConfigMgr;
	private $vehicle_id=array();
	private $price=array();

	public function __construct($location)/*: void */
	{
		$this->LoadShop($location);
	}

	public function LoadShop($location)/*: void */
	{
		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::LOAD_VEH_SHOP);
		$execute_params =array($location) ;
		$sth->execute($execute_params);

			while ($row=$sth->fetch(PDO::FETCH_ASSOC)){
				$this->vehicle_id[] = $row['vehicle_id'];
				$this->price[]=$row['price'];
			}

	}

	public function GetVehicles()
	{
		return $this->vehicle_id;
	}

}