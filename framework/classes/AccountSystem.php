<?php
class AccountSystem
{
    use ConfigMgr;
    
    private $id, $login, $email, $character, $ip, $password;
    
    public function __construct($peer_id=0)
    {
        if (!$peer_id)
            return;
        
        $this->loadAccount($peer_id);
    }
    
    public function loadAccount($peer_id)
    {
        //session_start();
        // THAT TIME WE ASSOCIATED ALL ACCOUNTS TO PEER ID FROM VK_BOT!
        // that maybe is not safety, but nevermind.. that is totaly shit with safety????
        if (isset($peer_id))
        {
            $account_name = $peer_id;
            $conn = $this->connect();
            
            $sth = $conn->prepare(select_statements::ACC_SEL_LOGIN);
            $sth->bindValue(1, $account_name, PDO::PARAM_STR);
            $sth->execute();
            
            if (!$sth->rowCount())
                return account_result::LOGIN_FAIL;
            
            while ($row=$sth->fetch(PDO::FETCH_ASSOC))
            {
                $this->id = $row['autoId'];
                $this->login = $row['login'];
                $this->email = $row['email'];
                $this->character=$row['character']; //dat is bitch!
                //$this->password = $row['password'];
            }            
        }
        return account_result::ALL_OK;
     }
     
     
     public function getAccountName()
     {
       //if ($this->checkSession())
           return $this->login;
     }
       
     public function getAccountCharacter()
     {
        //if ($this->checkSession())
            return $this->character;
     }
      
     public function checkSession()
     {
         //session_start();
           
         if (empty($_SESSION['account']) || $this->login!=$_SESSION['account'])
             return false;
       
         return true;   
     }
        
     public function setAccountCharacter($char_id)
     {
         $this->character = $char_id;
         $this->updateAccountInfo();
     }
    
     
     public function getAccIdFromCharId($char_id)
     {
         $conn = $this->connect();
         $sth = $conn->prepare(select_statements::ACC_SEL_FROM_CHAR_ID);
         $sth->bindValue(1, $char_id);
         $sth->execute();
         
         while ($row = $sth->fetch(PDO::FETCH_ASSOC))
         {
             return $row['login'];
         }
         
     }
     public function updateAccountInfo()
     {
          
          // const ACCOUNT_UPDATE = "UPDATE accounts SET login=?, email=?, password=?, character=? WHERE autoId=?";
          // блядь чо за хуйнана1111
         $conn = $this->connect();
         $sth = $conn->prepare(update_statements::UPDATE_ACCOUNT);
/*          $sth->bindValue(1, $this->login, PDO::PARAM_STR);
         $sth->bindValue(2, $this->email, PDO::PARAM_STR);
         $sth->bindValue(3, $this->password, PDO::PARAM_STR); //dat is bad! */
         $sth->bindValue(1, $this->character);
         $sth->bindValue(2, $this->id);
         $sth->execute();
         //print_r($conn->errorInfo());
     }
      
     public function unsetSession()
     {
         //session_start();
         $_SESSION['account'] = 0;
         unset($_SESSION['account']);
	  session_unset();
	  session_destroy();
     }
}