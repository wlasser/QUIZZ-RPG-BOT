<?php
class CreaturePlacer
{
	use ConfigMgr;

	public $creatures_list = array();

	public function __construct ($location, $sublocation)
	{
		$this->loadCreatures($location,$sublocation);
	}

	public function getCreatureList()
	{
		//echo 'what?';
		return $this->creatures_list;
	}

	public function loadCreatures($location, $sublocation)
	{
		$conn = $this->connect();
        
        $sth=$conn->prepare(select_statements::GET_CREATURES);
        $execute_params = array($location, $sublocation);
        $sth->execute($execute_params);
            while ($row = $sth->fetch(PDO::FETCH_ASSOC))
            {
            	$this->creatures_list['autoid'][]=$row['autoid'];
            	$this->creatures_list['id'][] = $row['id'];
            	$this->creatures_list['conv_id'][]=$row['conv_id'];
            	$this->creatures_list['sublocation'][]=$row['sublocation'];
            	$this->creatures_list['location'][]=$row['location'];

        	}
	}
    
	public function getAutoidFromId($npc_id)
	{
	    foreach ($this->creatures_list['id'] as $key=>$value)
	    {
	        if ($value == $npc_id)
	            return $this->creatures_list['autoid'][$key];
	    }
	}
	
	public function getConversationIdFromAutoId($auto_id)
	{
	    foreach ($this->creatures_list['autoid'] as $key=>$value)
	    {
	        if ($value == $auto_id)
	            return $this->creatures_list['conv_id'][$key];
	    }
	}
	
	public function getNpcIdFromAutoId($auto_id)
	{
	    foreach ($this->creatures_list['autoid'] as $key=>$value)
	    {
	        if ($value == $auto_id)
	            return $this->creatures_list['id'][$key];
	    }
	}
	
	
	public function getConversation($id)
	{

		foreach ($this->creatures_list['id'] as $key=>$value)
		{
			if ($id==$value && $this->creatures_list['conv_id'][$key]>0)
			{

				return $this->creatures_list['conv_id'][$key];
			}
		}
	}
}