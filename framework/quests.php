<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 11.05.2018
 * Time: 13:45
 Короче. Нужно вывести на экран все доступные для локации квесты. Базис
 сука блядь!!!!1111
 охует как это зделоть111
 */

echo '<div id="form">';
//echo $_POST['choise'] ?? '';
//echo '123123';
include 'includes.php';
$account = new AccountSystem();
$player_id = $account->getAccountCharacter();

$quest_id = $_GET['quest_id'] ?? $_POST['quest_id'] ?? '';
$player  = new Player($player_id);
$inventory = new Inventory($player_id);
	$player_location = $player->GetLocation();
	$player_sublocation=$player->GetSublocation();
	$scores= $player->GetCurrentQuestScores($quest_id);
	$questsSteps = new QuestStepsMgr(); // нужно что-то для вывода по больше, меньше...
	$quest_template = new QuestMgr($quest_id);

// i need to complete quest
if (isset($_POST['check']))
{

	
	
	$questsSteps = new QuestStepsMgr(); // нужно что-то для вывода по больше, меньше...
	//$quest_tmp = $questsSteps->GetShit($player_id, $scores);
	if (isset($_POST['choise']))
	{
	    $payload_action='';
		// /echo $_POST['choise'];
	    if (isset($_POST['item']))
	    {
	        $result = $inventory->UseItem(0, $player, $_POST['item']);
	        if ($result == item_use_result::INVENTORY_ERROR || $result==item_use_result::ITEM_EMPTY)
	            die ('Предмет истощен, нельзя применить!');
	    }
		$scores= $player->GetCurrentQuestScores($quest_id);
		//if (isset($_POST['item']))
		//echo "2:".$scores."<br>";
		$questsSteps->CollectScores($_POST['choise'], $quest_id, $scores, $player);
		
		
		//die('something');
		//$scores= $player->GetCurrentQuestScores($quest_id);
	}

}


$scores= $player->GetCurrentQuestScores($quest_id);	
$quest_tmp = $questsSteps->GetShit($quest_id, $scores);



	// now i don't know about practice of it?
	if ($scores>=event_quest::MAX_QUEST_SCORE)
	{
		
		switch ($player->questCompleted(($quest_id)))
		{
			case TRUE:
				die ('Вы завершили задание');
			break;
			case FALSE: // @FIXME move it to some advanced check, now it's bad
				switch ($scores)
				{
			    case 1000: // hunter
			        echo "hunter";
			          $player->createPlayerDefaultStats(player_classes::HUNTER);
			          // TODO: DROP THAT FROM RELEASE!
			          $player->SetMoney(10000);
			        echo "created!";
			    break;
			    case 2000: // detective
			    break;
			    case 3000: // readers
			    break;
			    case 4000: // witchunter
			        echo "Withchunter!";
			        $player->createPlayerDefaultStats(player_classes::WITCHHUNTER);
			        // TODO: DROP THAT FROM RELEASE!
			        $player->SetMoney(10000);
			        echo "created!";
			    //adding items!
			    break;
		        
				}
			break;
		}
			
		
		$result = $quest_template->completeQuest($quest_id,$player, $inventory); 

		switch ($result)
		{
			case quest_result::ERROR_INVENTORY:
				die('Нет свободного места!');
			break;
			case quest_result::ERROR_FATIGUE:
				die('Вы слишком устали, чтобы закончить начатое');
			break;
			case quest_result::ALL_OK:
				die('Вы успешно завершили задание');
			break;
		}

		die('Вы получили награду за выполнение миссии. Так держать!'); 
	}

	if (!$quest_tmp)
		die ('Это фиаско...');	

echo $quest_tmp['greetings'];


foreach ($quest_tmp['variants'] as $key=>$value)
{
	$show_it=$value;

	if ($quest_tmp['req_type'][$key])
	{
		$check = $questsSteps->CheckRequirements($quest_tmp['req_type'][$key], $quest_tmp['req_thing'][$key], $inventory, $player);
		//this part of code - sucks, like noone sucks!
		if ($check)
		{
		      switch ($check)
		      {
		          
		          case variants_type::ITEM:
		              $show_it = '<p><input type="radio" id="item_used" data-dd="'.$quest_tmp['req_thing'][$key].'" name="choise" value="'.$key.'"><font color="red">'.$value."</font></p> \n";
		          break;
		          case variants_type::RUMORS:
		              $show_it = '<p><input type="radio" id="rumors" data-dd="'.$quest_tmp['req_thing'][$key].'" name="choise" value="'.$key.'"><font color="red">'.$value."</font></p> \n";	              
		          break;
		          case variants_type::NPC_FIGHT:
		              $player->initQuestFight($quest_tmp['req_thing'][$key], $quest_id, $key, 0);
		              if (!$player->isQuestFightComplete($quest_tmp['req_thing'][$key], $quest_id))
		              {
		                  $show_it = '<p><a class="battle_quest_btn" href="#" data-questid="'.$quest_id.'" data-npcid="'.$quest_tmp['req_thing'][$key].'" data-questvar="'.$key.'">'.$value.'</a></p>'."\n";
		              }
		              else 
		                  $show_it = 'There is passed, when quest battle is complete!!!';
		          break;
		      }
		}
		else
		{
			$show_it='';
		}
		
	}
	else
	{
		if (!$value)
			$show_it='';
		else
			$show_it='<p><input type="radio" name="choise" value="'.$key.'">'.$value."</p> \n";
	}
	//else
		//$spec = $value;
	//$spec = $questsSteps->CheckRequirements()
		//здесь нужно добавить финиш квеста!;
		// сброс квестов
	echo $show_it."\n";
	
}
?>
<input type="hidden" id="check" name="check" value="true">
<input type="hidden" id="quest_id" name="quest_id" value="<?php echo $quest_id ?>">
<input type="button" id="questBtn" value="Сделать выбор">
<div id="log"></div>
</div>
<!-- </form> -->
<?php ?> 