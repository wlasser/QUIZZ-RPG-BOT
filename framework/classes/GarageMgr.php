<?php
class GarageMgr
{
    use ConfigMgr;
    
    //private $id, $player_id, $vehicle_id, $corruption, $fuel;
    
    public function __construct() {
        //$this->LoadGarage($player_id);
    }
    
    public function LoadGarage($player_id)
    {
        $conn = $this->connect();
        
        $sth = $conn->prepare(select_statements::LOAD_GARAGE);
        $execute_params =array($player_id) ;
        $sth->execute($execute_params);
        if (!$sth->rowCount())
            return 0;
			while ($row=$sth->fetch(PDO::FETCH_ASSOC)){
                            //echo $row['player_id'];
                                $garage['player_id'][]=$row['player_id'];
				$garage['id'][] = $row['id'];
				$garage['vehicle_id'][]=$row['vehicle_id'];
				$garage['fuel'][]=$row['fuel'];
				$garage['coruption'][]=$row['coruption'];
			}
    return $garage;
    }

            
}

