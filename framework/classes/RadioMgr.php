<?php
class RadioMgr
{
    use ConfigMgr;
	// loading radio for current location. it's work if you have radio!
	// there need to be review, like radio can be many types?!
    
        // ok, there is constucting like $radio = new RadioMgr(location, fm_id)
        // fm_id can be nulled and skipped, then radiomgr load ALL available radio
        // TODO: improve this system, it's look shitty. Tottaly shitty!

	private $name =array();
	private $global = array();
	private $location = array();
	private $id = array(); // stop i dont know what is id mean? ok...

	private $message=array();
	private $quest=array();
	private $fm_id=array();
	private $message_id=array();


	public function __construct($location, $fm_id=0)/* :void */
	{
            if ($fm_id)
                $this->LoadFm($fm_id);
            else
                $this->LoadRadio($location);
	}

	public function LoadRadio($location)/*: void */ //hacky way, but dont know how do it better...
	{
		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::LOAD_RADIO);
		$execute_params =array($location);
		$sth->execute($execute_params);

			while ($row = $sth->fetch(PDO::FETCH_ASSOC))
			{
				$this->name[]=$row['name'];
				$this->global[]=$row['global'];
				$this->location[]=$location;
				$this->id[]=$row['id'];
			}                    

	}

	public function GetName()
	{
		return $this->name;
	}

	public function GetFmId()
	{
		return $this->fm_id;
	}

	public function GetLocation()
	{
		return $this->location;
	}

	public function GetId()
	{
		return $this->id;
	}

	public function LoadFm($radio_id)/*: void */
	{
		$conn = $this->connect();

		$sth = $conn->prepare(select_statements::LOAD_FM_STATION);
		// there is sucks!
		$execute_params =array($radio_id);
		$sth->execute($execute_params);

			while ($row = $sth->fetch(PDO::FETCH_ASSOC))
			{
				$this->message[]=$row['message'];
				$this->quest_id[]=$row['quest_id'];
				$this->fm_id[]=$row['radio_id'];
				$this->message_id=$row['message_id'];
			}


	}

	public function GetMessages()
	{
		return $this->message;
	}

	public function IsQuest($message_id)/* :boolean */
	{
		foreach ($this->quest_id as $key=>$value)
		{
			if ($value==1 && $message_id==$key) 
                return true;
		}

	return false;
	}

}