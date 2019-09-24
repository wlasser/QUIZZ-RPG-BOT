<?php
class ConversationMgr
{
    use ConfigMgr;

    public $conversation = array();

    public function __construct($conv_id)
    {
    	$this->loadConversation($conv_id);
    }

    public function loadConversation ($conv_id)
    {

		$conn = $this->connect();
        
        $sth=$conn->prepare(select_statements::LOAD_CONVERSATION);
        $execute_params = array($conv_id);
        $sth->execute($execute_params);
            while ($row = $sth->fetch(PDO::FETCH_ASSOC))
            {
            	$this->conversation['id'][] = $row['id'];
            	$this->conversation['dialog_id'][]=$row['dialog_id'];
            	$this->conversation['dialog_text'][]=$row['dialog_text'];
            }

    }

    public function getDialogText($dialog_id)
    {
    	//echo $dialog_id;
    	//if (!$dialog_id || $dialog_id==1)
    		//return FALSE; 
    	//var_dump($this->conversation['dialog_id']);
    	if (empty($this->conversation))
    		return false;
    	foreach ($this->conversation['dialog_id'] as $key=>$value)
    	{
    		//echo $value;
    		if ($dialog_id==$value)
    			return $this->conversation['dialog_text'][$key];
    	}
    }

    public function getMaxDialogId($dialog_id)
    {
    	//if (!$dialog_id || $dialog_id==1)
    	//	return FALSE;
    	if (empty($this->conversation))
    		return false;
    	return count($this->conversation['dialog_id']);


    }

}