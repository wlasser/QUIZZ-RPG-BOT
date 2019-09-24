<?php
include '../includes.php';
$configHlpr = new ConfigHelper();

if ($configHlpr->getCurrentScriptName()==1)
    die ('АЗАЗАЗА');

    
/* fixme */
$account = new AccountSystem();
$player = new Player($account->getAccountCharacter());

if ($player->getFlag()==1)
    die ("Can't do something, coz leveluped!");



$action = $_POST['action'] ?? '';
$action_2 = $_GET['what'] ?? '';

if ($action_2=='view_quest')
{
	//echo $action_2;
	$player_id = $player->GetId();
	$quest_id=$_GET['quest_id'];
    $quest = new QuestMgr($quest_id);
    //$player = new Player($player_id);
    echo "<b>".$quest->GetTitle()."</b><br><hr> \n";
    echo $quest->QuestView()."<br><hr> \n";
    echo '<a href="#" data-qi="'.$quest_id.'" class="accept_quest_btn">Начать задание</a> ';
	//var_dump($_POST);
	// /echo 'test';
}

switch ($action)
{       
        case 'accept_quest':
                //fixme
                $player_id = $player->GetId();
                $quest_id=$_POST['quest_id'];
            
                //$player= new Player($player_id);
            
                $result = $player->AddQuest($quest_id);
                $quest = new QuestMgr($quest_id);
            
                if ($result){
                    printf("Задание %s принято.", $quest->GetTitle ());
                }
                else 
                {
                    echo "К сожалению вы не можете принять это задание.";
                }
        break;
        case 'register':
           $loginsystem = new LoginSystem();
            //echo 'test';
           $login = $_POST['login'];
           $email = $_POST['email'];
           $password = $_POST['password'];
           $password2 = $_POST['password2'];
            
            $loginsystem->Register($login, $email, $password, $password2);
            
        break;
        case 'login':
            $loginsystem = new LoginSystem();
            $login=$_POST['login'];
            $password = $_POST['password'];
            
            $loginsystem->Login($login, $password);
            
        break;
        case 'select_weapon':
            $player_id = $player->GetId();
            $weapon_total_id = $_POST['weapon_id'];
            //echo $weapon_total_id;
            //$player = new Player($player_id);
            $inventory = new Inventory($player_id);
            $item_id = $inventory->GetItemIdFromTotalId($weapon_total_id);
            $item = new Item($item_id);
            
            echo $item->GetName()."<br>\n";
            
            $bullet_array = $item->checkForBullets($inventory, $item); 
            
            if ($bullet_array)
            {
               foreach ($bullet_array['total_id'] as $key=>$value)
               {
                   $bullet_id = $inventory->GetItemIdFromTotalId($value);
                   $bullet = new Item($bullet_id);
                   printf('<input type="radio" id="bullet_select" name="bullet" value="%s">%s',$value, $bullet->GetName());
               }
                //echo '<input type="radio" name="bullet" value="'..'">'.
            }
            //else die('123'); 
            
        break;
		case 'change_sublocation':
		{
			$player_id = $player->GetId();
			$new_sublocation = $_POST['sublocation'];
			//echo $new_sublocation;
			//$player = new Player($player_id);
			$player_location = $player->GetLocation();
			$current_sublocation = $player->GetSublocation();
			$sublocationMgr = new SublocationMgr($player_location);
			$fatigue_loose = $sublocationMgr->getFatigueLoose($new_sublocation);
			//echo 'asdasdasdasd';
			if ($player->modifyFatigue($fatigue_loose))
			{
				$player->SetSublocation($new_sublocation);
				echo 'Вы успешно совершили перемещение по локации!';
			}
			else 
				echo 'Не возможно, вы слишком устали!';
			

		}
		break;
		case 'update_stats':
			$player_id = $player->GetId();
			//$player = new Player($player_id);
			printf("Имя: %s <br> \n Деньги: %s <br> \n",$player->GetName(), $player->GetMoney());
			printf("Текущий опыт = %s/%s<br> \n", $player->GetCurrXp(), ExpMgr::GetNextLvlExpValue($player->GetLevel()));
			printf("Текущий уровень = %s <br> \n", $player->GetLevel());
			printf("<hr> \n Характеристики персонажа: <br> \n ");
			printf("Проворность: %s <br> \n", $player->GetAccuracy());
			printf("Интеллект: %s <br> \n", $player->GetIntellect());
			printf("Сопротивление: %s <br> \n", $player->GetResistance());
			printf("Броня: %s <br> \n", $player->GetArmor());
			printf("Здоровье: %s/%s <br> \n", $player->GetHp(), $player->GetMaxHp());
			printf("Усталость: %s/%s <br> <hr> \n", $player->GetFatigue(), $player->getMaxFatigue());
		break;
       
        case 'drop_item':
            $total_id=$_POST['total_id'];
            $player_id = $player->GetId();
            $inventory = new Inventory($player_id);
            $inventory->RemoveFromInventory($total_id, true);
            echo 'Предмет выброшен';
            break;
	case 'load_item':
		$total_id = $_POST['total_id'];
        //$player = new Player();       
		$inventory = new Inventory ($player->GetId());
        $item_id = $inventory->GetItemIdFromTotalId($total_id);
        $item = new Item($item_id);
        //echo "<hr>".$inventory->GetCharges($total_id)."<hr>";
                //echo $item_id;
		echo "<u>".$item->GetName()."</u> <br> \n";
		echo $item->GetDescription()."<br> \n ";
		if ($inventory->GetCharges($total_id))
			echo "<hr> \n Можно использовать :".$inventory->GetCharges($total_id)."/".$item->GetCharges()."<br> \n";
                if ($inventory->GetCount($total_id))
                        echo "<hr> \n Количество этих предметов: ". $inventory->GetCount($total_id);
	break;
        case 'load_shop_item':
            $player_id = $player->GetId();
            $shop_id =  $_POST['shop_id'];
            
            //$player = new Player($player_id);
            
            $shop = new ShopMgr($player->GetLocation());
            
            $item_id = $shop->GetItemFromVendorList($shop_id);
            
            $item = new Item($item_id);
            
            echo $item->GetName()."<hr>";
            echo $item->GetDescription()."<hr>";
            echo "Стоимость: ".$shop->GetPrice($item->GetId());

        break;
        case 'buy_item':
        {
            $player_id = $player->GetId();
            $buy_item = $_POST['buy_item'];
            //$player = new Player($player_id);
            $item = new Item($buy_item);
            
            //public function BuyItem($item_id, $player, $item)
            $inventory = new Inventory($player_id);
            
            //$inventory->BuyItem($buy_item, $player,$item);
            $inventory->BuyItem($item, $player);
            //echo $buy_item;
        }
        break;
	case 'use_item':
	{
		// ха, плеера надо брать из сессии!

		// $player_id = $_POST['player_id'];
                //echo $_POST['invent_id'];
       
        
        $inventory = new Inventory($account->getAccountCharacter());
		$player = new Player($account->getAccountCharacter());
		$total_id = $_POST['total_id'];
		$item = new Item($inventory->GetItemIdFromTotalId($total_id));
		
		echo $inventory->UseItem($total_id, $player);
	}
	break;
	case 'load_inventory':
	{
		$player_id = $account->getAccountCharacter();//this need to be make with safeString or something and that need to be getting from $_SESSION

		$inventory = new Inventory($player_id);
		$inventory_items = $inventory->GetInventory();
		if (!$inventory_items)
		    die ('У вас пока ничего нет');
        echo "<table border=1>";
        echo "<tr>";
		foreach ($inventory_items['item_id'] as $key=>$value)
	    {
            if ($key>0 && $key%4==0)
                echo "</tr><tr>";
			$item = new Item($value);
			if ($item->GetIcon())
				printf ('<td><img src="%s" class="test" data-id="%s" data-dd="%s"></td>',$item->GetIcon(),$value, $inventory_items['total_id'][$key]);
         }
         echo "</tr>";
         echo "</table>";
	}      
	break;
	case 'change_radio':
	{
		$radio_id = $_POST['radio_id'];
		$radioMgr = new RadioMgr(0,$radio_id);
                //$radioMgr->LoadRadio($radio_id, $radio_id);

		$messages = $radioMgr->GetMessages();

			if (empty($messages))
				die ("Радиоволны нет");

		$string="";

		$quested=0;
		$before="";
		$after="";

		foreach ($messages as $key=>$value)
		{
			if ($radioMgr->IsQuest($key))
		    {
			     $quested=1;
			
				 $before="<a href=#>";
				 $after="</a>";
		    }
			else 
			{
				$quested=0;
				$before="";
				$after="";

			}
			$string.=$before.$value.$after."<br><hr><br>";
		}
		printf('<marquee behavior="scroll" direction="up" bgcolor="" scrollamount="1" width="250"> %s</marquee>',$string);
	}
	break;
}
