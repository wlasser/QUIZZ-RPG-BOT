<?php
class LootMgr
{
	use ConfigMgr;

	public $loot_template = array();
    // what we need? we must get all for that loot and make it some additional
    // when player can loot many shit with random range of chance of something!

	public function __construct($npc_id)
	{
		$this->loadLootTable($npc_id);
	}

	public function loadLootTable($npc_id)
	{
		$conn=$this->connect();

        $sth = $conn->prepare(select_statements::LOAD_LOOT_TABLE);
        $execute_params =array($npc_id);
        $sth->execute($execute_params);
        //$coord=array();

        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
        	$this->loot_template['id'][]=$row['id'];
        	$this->loot_template['item'][]=$row['item'];
        	$this->loot_template['chance'][]=$row['chance'];
        	$this->loot_template['count'][]=$row['count'];

        }
	}

	public function getLoot()
	{
		return $this->loot_template;
	}

}