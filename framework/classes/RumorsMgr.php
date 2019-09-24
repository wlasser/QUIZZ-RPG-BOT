<?php
class RumorsMgr
{
	use ConfigMgr;
    private $link, $id;
    
    public function setLink($link)
    {
        $this->link =$link;
    }
    
    public function getLinkFromHere()
    {
        if (isset($this->link))
            return $this->link;
    }
    
	public function getLink($id)
	{
		$conn = $this->connect();
        $sth = $conn->prepare(select_statements::GET_RUMOR_TEXT);
        $execute_params =array($id) ;
        $sth->execute($execute_params);
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
        	
        	$this->setLink($row['link']);
        	$this->setId($id);
        	return $this->getLinkFromHere();

        }
	}

	public function getText($id)
	{
		$conn = $this->connect();
        $sth = $conn->prepare(select_statements::GET_RUMOR_TEXT);
        $execute_params =array($id) ;
        $sth->execute($execute_params);
        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
        	return $row['text'];

        }
	}
	
	public function setId($id)
	{
	    $this->id = $id;
	}
	
	public function getRumorId()
	{
	   return $this->id;
	}

	public function parseRumor($text, $conv_id=0, $dialog_id=0, $npc_id=0) // maybe dont needed!
	{
		$links = array();
		if (preg_match_all('/(#[^\s]+)\s/iU', $text, $output))
		{
    		foreach ($output[1] as $key=>$value)
    		{
    			$clean = str_replace('#','',$value);
    			$clean = str_replace('.','',$clean);
    			$clean = str_replace(',','',$clean);
    			$clean = str_replace('!','',$clean);
    			$clean = str_replace('?','',$clean);
    			$clean = str_replace(':','',$clean);
    			$clean = str_replace(';','',$clean);
    			$links[] = "&#10071;".$this->getLink($clean)."&#10071;";
    		}
    		$ready = str_replace($output[1], $links, $text);

		return $ready;
		}
		else
		    return FALSE;
	}

}