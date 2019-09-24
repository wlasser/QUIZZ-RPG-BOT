<?php
trait ConfigMgr
{
	private $host, $user, $pass, $database, $port;
        private $distance_modifer;
        private $chance_modifer;
        private $inventory_size;
        private $directory;
        
	private function LoadConfig()/*: void */
	{
		//include_once 'config.php';
        // TODO:: REVIEW THAT IS AS POSSIBLE!
		$this->host='';
		$this->user='';
		$this->pass='';
		$this->port =3306;
		$this->database='bot_game';
		$this->directory='';
		//echo $this->directory;
		
                $this->distance_modifer=0.7;
                $this->chance_modifer = 10;
                $this->inventory_size = 15;
	}

	public function connect()/*: void */
	{
		$this->LoadConfig();
		Try{
		    //print $this->pass;
		    $conn = new PDO('mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->database, $this->user, $this->pass);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e)
		{
		    echo "Connection failed: ".$e->getMessage();
		}
		
		
		$conn->query(other_statements::SET_UTF_CHARSET);
		
	return $conn;
	}
    
	public function SafeString($string)
	{
	    $string = stripslashes($string);
	    $string = strip_tags($string);
	    
	return $string;
	}
	
	public function ShaPass($user,$pass)
	{
	    $user = strtoupper($user);
	    $pass = strtoupper($pass);
	
	    return SHA1($user.':'.$pass);
	}
	
	public function getDirectory()
	{
	    $this->LoadConfig();
	    return $this->directory;
	}
	
        public function GetDistanceModifer()/*:int */
        {
            $this->LoadConfig();
            return $this->distance_modifer;
        }
        
        public function GetChanceModifer()/*:int */
        {
            $this->LoadConfig();
            return $this->chance_modifer;
        }
        
        public function GetInventorySize()/*:int*/
        {
            $this->LoadConfig();
            return $this->inventory_size;
        }

}
?>