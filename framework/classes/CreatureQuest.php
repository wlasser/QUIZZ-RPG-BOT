<?php
class CreatureQuest{
	use ConfigMgr;
	//get some shit? but how?

	public function getQuest($auto_id)
	{

		$conn = $this->connect();

        $sth = $conn->prepare(select_statements::CREATURE_QUEST);
        $execute_params =array($auto_id) ;
        $sth->execute($execute_params);
        $quest_info = array();
		//
		while ($row = $sth->fetch(PDO::FETCH_ASSOC))
		{
			//return $row['quest_id'];
			$quest_info['quest_id'][]=$row['quest_id'];
		}
		return $quest_info;	
	}

}