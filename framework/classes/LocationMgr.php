<?php
class LocationMgr
{
    use ConfigMgr;
	//private $position = array();

	/*public function __construct($player_id)
	{

	}
	*/
	
	public function GetLocationName($location_id) /* :string */
	{
		$conn=$this->connect();

		$sth = $conn->prepare(select_statements::GET_PLAYER_LOCATION);
		$execute_params =array($location_id) ;
		$sth->execute($execute_params);
		$name;
			while ($row = $sth->fetch(PDO::FETCH_ASSOC))
			{
				$name=$row['name'];	
			}

	return $name;

	}

	public function GetAvailableLocation($location_id)/* :int */
	{
		$conn=$this->connect();

		$sth = $conn->prepare(select_statements::GET_LOCATIONS);
		$execute_params =array($location_id) ;
		$sth->execute($execute_params);
		$location=array();

			while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
				if ($row['id']==$location_id || $row['id']==100) // hack for purgatory
                                    continue;

				$location['id'][]=$row['id'];
				$location['name'][]=$row['name'];
				$location['x'][]=$row['x'];
				$location['y'][]=$row['y'];
			}

	return $location;
	}

	public function GetCoordinates($location_id)
	{
		$conn=$this->connect();

		$sth = $conn->prepare(select_statements::GET_COORDS);
		$execute_params =array($location_id) ;
		$sth->execute($execute_params);
		$coord=array();

			while ($row = $sth->fetch(PDO::FETCH_ASSOC)){
				$coord['x']=$row['x'];
				$coord['y']=$row['y'];

			}

	return $coord;
	}


	public function GetDistance(array $curr_pos, array $end_pos) /* :int */
	{

		$distance = (int) sqrt(($end_pos['x']-$curr_pos['x'])^2 +($end_pos['y']-$curr_pos['y'])^2)*10;

	return $distance;
	}

	public function GetFatigueDistance($distance) /* :int */
	{

	return (int)$distance*$this->GetDistanceModifer(); // or move it to defines?
	}
}
?>