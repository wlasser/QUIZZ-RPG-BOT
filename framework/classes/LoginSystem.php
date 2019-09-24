<?php
class LoginSystem
{
    use ConfigMgr;
    
    public function Register($login) //bool
    {
        $login = $this->SafeString($login);

        $time = time();
        $ip=$_SERVER['REMOTE_ADDR'];
        
        $conn = $this->connect();
        /* $sth = $conn->prepare(select_statements::ACC_SEL_EMAIL);
        $sth->bindValue(1, $email);
        $sth->execute();
        
        if ($sth->rowCount())
            die("email уже используется"); */
        
        $sth = $conn->prepare(select_statements::ACC_SEL_LOGIN);
        $sth->bindValue(1, $login);
        $sth->execute();
            
        if ($sth->rowCount())
            return reg_results::REGISTER_FAIL;
        
           $email='fromvk@vk.vk';
           $shaPassword = 0;
           
        
        $sth=$conn->prepare(insert_statements::INS_REGISTER);
        $sth->bindValue(1, $login);
        $sth->bindValue(2, $email);
        $sth->bindValue(3, $shaPassword);
        $sth->bindValue(4, $ip);
        $sth->bindValue(5, $time);
        $sth->bindValue(6, 0); // that is set character 0, needed to create character
        $sth->execute();
        
        // this is can be improved to login!
        
        return reg_results::ALL_OK;
                
        
        
        
    }
    
    public function Login($login)
    {
        $login = $this->SafeString($login);
        //$password = $this->SafeString($password);
        
        $shaPass = 0; //$this->ShaPass($login, $password);
        
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::ACC_SEL_PASS);
        $sth->bindValue(1, $login);
        $sth->bindValue(2, $shaPass);
        $sth->execute();
        
        if ($sth->rowCount())
        {
            //session_start();
            //$_SESSION['account']=$login;
            return reg_results::ALL_OK;
            //header("Location: index.php");
        }
        else 
            return reg_results::REGISTER_FAIL;
        
        
        
        
        
    }
}