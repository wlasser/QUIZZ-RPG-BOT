<?php
//TODO MAKE IT DIFF LANGUAGES!
$start = microtime(true);
require_once "api/src/autoload.php"; //Подключаем библиотеку

use DigitalStar\vk_api\VK_api as vk_api; // Основной класс
use DigitalStar\vk_api\Group as group; // Работа с группами с ключем пользователя
use DigitalStar\vk_api\Auth as Auth; // Авторизация
use DigitalStar\vk_api\Post as Post; // Конструктор постов
use DigitalStar\vk_api\Message as Message; // Конструктор сообщений
use DigitalStar\vk_api\VkApiException as VkApiException; // Обработка ошибок
require_once 'config_vk_bot.php';
include 'framework/includes.php';
require_once 'locales_ru.php';
require_once 'quest_form.php';
require_once 'buttons_base.php'; //TODO that is need to be some split or rewrited for more dynamic view!

//**********CONFIG**************
const VK_KEY = ""; //ключ доступа сообщества
const ACCESS_KEY = ""; //ответ на confirm
const VERSION = "5.80"; //версия api
//******************************





$one_time=true; // setting click on buttons - when clicked - disappear!

$vk = new vk_api(VK_KEY, VERSION); // создание экземпляра класса работы с api, принимает ключ и версию api
$data = json_decode(file_get_contents('php://input')); //Получает и декодирует JSON пришедший из ВК
if ($data->type == 'confirmation')
{ //Если vk запрашивает ключ
    
    exit(ACCESS_KEY); //Завершаем скрипт отправкой ключа
}

function show_char_create_buttons()
{
    //that is can be implemented, but not here!
}
 //Говорим vk, что мы приняли callback
$vk->sendOK(); // why?

if (isset($data->type) and $data->type == 'message_new')
{
    $id = $data->object->from_id; //Получаем id пользователя, который написал сообщение
    $message = $data->object->text;
    if (isset($data->object->peer_id))
        $peer_id = $data->object->peer_id; // Получаем peer_id чата, откуда прилитело сообщение
    else
        $peer_id = $id;
            //if (BaseCheck($peer_id))
            $player = BaseCheck($peer_id); //THAT IS ROOT CHECK! - THAT NOT NEEDED AS CONTINUES?
            if ($player)
            {
                $achievMgr = new AchievmentMgr($player);
            }
            
            if (isset($data->object->payload))
            {  //получаем payload
                $payload = json_decode($data->object->payload, true);
            } 
            else
            {
                $payload = null;
            }
            $loginSystem = new LoginSystem();

            $message_array = explode(' ',$message);
            //TODO SOON AS POSSIBLE, AND IT CRITICAL! REWRITE AUTORIZATION LOGIC!
            // TODO here is can be perfomance tweak as split functions! (need to some thinking);
            if (isset($message))
            {
               $command = mb_strtolower($message_array[0]);
/*                  if (!empty($command))
                {
                    $account = new AccountSystem();
                    $loginSystem = new LoginSystem();
                    
                    //$player = BaseCheck($id)
                    $vk->sendButton($peer_id, "комманды игрока: ")
                } */
                
                switch ($command)
                {
                    case 'test':
                        if ($player)
                        {
                            if ($player->GetId()==1)
                            {
                                $inventory = $player->getInventoryInstance();
                                $inventory->UseItem(0, $player, 25);
                                $vk->sendButton($id, "Pewpew", [[BTN_CONTINUE]]);
                                
                            }
                        }
                        /* $mess = new Message($vk);
                        //$mess->setMessage("test");
                        //$mess->addImage("/var/www/html/bot/www/images/1.jpg");
                        try 
                        {
                            //$mess->send($peer_id);
                            //$vk->sendMessage($id, "what?");
                            $filename = 'images/1.jpg';
                            //LogDebug::log_msg(realpath($filename));
                            //$vk->sendImage($id, "1.jpg");
                            $vk->sendImage($id, $filename);
                        }
                        catch (VkApiException $e)
                        {
                            LogDebug::log_msg($e->getMessage());
                        } */
                        //$vk->sendButton($id, "what?", [[BTN_CONTINUE]]);
                        //$vk->sendMessage($id, "here?");
                    break;
                    case 'перейти':
                        {
                            // there is need to get sublocation list as buttons!
                    
                            //$player = BaseCheck($id);
                    
                            if ($player)
                            {
                                $player_sublocation = $player->GetSublocation();
                                $sublocationMgr = new SublocationMgr($player->GetLocation());
                                $sublist = $sublocationMgr->getListForLocation();
                                $buttons = array();
                                $message_send = "";
                                foreach ($sublist['name'] as $key => $value)
                                {
                    
                    
                                    if ($sublist['id'][$key] == $player_sublocation)
                                        continue;
                                    $fatigue_loose = $sublist['fatigue_loose'][$key];
                                        //[[["choise" => $quest_id."_".$key], $button_caption, "red"]];
                                    $button_caption = messages_strings::GO.$value;
                                    $message_send.= $value.messages_strings::FATIGUE.$fatigue_loose." \n";
                                    $buttons[] = [[["change_subloc"=>$sublist['id'][$key]], $value, "blue"]];
                                }
                                $vk->sendButton($id, $message_send, $buttons, $one_time);
                            }                    
                        }
                    break;
                    case 'кто':
                    {
                        //$player=BaseCheck($id);

                        if ($player)
                        {
                            $game = new Game();
                            $online_players = $game->getOnlinePlayersForCurrentLocSubloc($player->GetLocation(), $player->GetSublocation());
                            LogDebug::log_msg($online_players);
                            if ($online_players)
                            {
                                $message_send=messages_strings::PLAYERS_AROUND."\n";
                                $count=0;
                                foreach ($online_players['id'] as $key=>$value)
                                {
                                    // addition check for player self?
                                    $testAcc = new AccountSystem(0);
                                    if ($value!=$player->GetId())
                                    {
                                        $message_send.=$value.")".$online_players['name'][$key]." \n";
                                        ++$count;
                                        $message_send.="\n\n".messages_strings::SMS_COMMAND_TEXT; // TODO maybe use names?
                                    }
                                }
                            }
                            if ($count==0)
                                $message_send=messages_strings::NOTHING_HERE."\n";
                            $vk->sendMessage($id, $message_send);
                        } 
                    }
                    break;
                    case 'осмотр':
                    case 'смотр':
                        if ($player)
                        {
                            //TODO that is need to be some write as it per one day, or somelike!
                            $queueMgr = new QueueMgr($player);
                            $instance = $queueMgr->getInstance();
                            $line = $instance->getMapLine();
                            $buttons_send = array();
                            foreach ($line as $key=>$value)
                            {
                                // TODO SOME ADDITIONAL!
                                $test = $key+1;
                                $buttons_send[]=[["makeStep"=>$test], "Путь № ".$test, "blue"];
                            }
                            $vk->sendButton($id, "Сделайте выбор", [$buttons_send], $one_time);
                            
                            
                            
                            
                        }
                    break;
                    case 'смс':
                    {
                        
                        foreach ($message_array as $key=>$value)
                        {
                            if ($key==0||$key==1)
                                continue;
                            $message_for_send.=$value." ";
                        }
                        
                        $char_id = $message_array[1];
                        
                        
                        $player = BaseCheck($peer_id);
                        if ($player)
                        {
                            $acc = new AccountSystem(0);
                            $send_to = $acc->getAccIdFromCharId($char_id);
                            
                            if ($send_to && $player->GetId()!=$char_id)
                            {
                                $player_to = new Player($char_id);
                                
                                if ($player_to->GetLocation()==$player->GetLocation() && $player->GetSublocation()==$player_to->GetSublocation())
                                {
                                    $message_send = mb_strimwidth($message_for_send,0,45);
                                    $send_from = $player->GetName();
                                    $vk->sendMessage($send_to, messages_strings::MESSAGE_FROM.$send_from." : ".$message_send);
                                    $vk->sendMessage($peer_id, messages_strings::MESSAGE_SENDED);
                                }
                                
                            }
                            
                        }
                        
                    }
                    break;
                    case 'вопрос':
                    {
                        if (count($message_array)>3)
                        {
                            $vk->sendMessage($id, "не то");
                            return;
                        }
                        $auto_id = $message_array[1];
                        
                        $player = BaseCheck($id);
                        if ($player)
                        {
                            $creatureQuest = new CreatureQuest();
                            $rumorsMgr = new RumorsMgr(); // that not needed now, but nwm
                            
                            $dialog_id =1;
                            
                            
                            
                            $creaturePlacer = new CreaturePlacer($player->GetLocation(), $player->GetSublocation()); 
                            $npc_id = $creaturePlacer->getNpcIdFromAutoId($auto_id);
                            
                            if (!$npc_id)
                            {
                                $vk->sendButton($id, "Никого нет", [[BTN_CONTINUE]]);
                                return;
                            }
                            
                            $creature = new Creature($npc_id);
                            $conv_id = $creaturePlacer->getConversationIdFromAutoId($auto_id);
                            
                            $convMgr = new ConversationMgr($conv_id);
                            $message_send = "&#9993;".$creature->GetName()."&#9993; \n".$convMgr->getDialogText($dialog_id);
                            $buttons = array();
                            //$message_send = "done";
                            $buttons[]=[["conversation"=>$npc_id.'_'.($dialog_id+1).'_'.$conv_id], character_message::KNOW_MORE, "red"];
                            //$vk->sendMessage($id, $message_send);
                            $vk->sendButton($id, $message_send, [$buttons], $one_time);
                            
                            
                            
                        }
                    }
                    break;
                    case 'назвать':
                    {
                         /* if ($player && $player->GetName())
                            return; */
                            
                        if (count($message_array)>3)
                        {
                            $vk->sendMessage($id, "не то");
                            return;
                        }

                        
                        $fullName = $message_array[1].' '.$message_array[2];
                        // there is the fucking something shit!
                        if (!$fullName || strlen($fullName)<5)
                        {
                            $vk->sendMessage($id,messages_strings::NAME_ERROR);
                            $vk->sendMessage($id, character_message::CHARACTER_SET_NAME_TEXT);
                            return;
                        }
                        $account = new AccountSystem($id); 
                        $login_result = $loginSystem->Login($id);

                        if ($login_result && !$account->getAccountCharacter())
                        {
                            $player = new Player(0);
                            $player->createPlayer($fullName);
                            $account->setAccountCharacter($player->GetId());
                            $vk->sendButton($id, character_message::CHARACTER_SELECT_SEX_MESSAGE, [[BTN_CREATE_CHARACTER_FEMALE],[BTN_CREATE_CHARACTER_MALE],[BTN_CREATE_CHARACTER_OTHER]],$one_time);
                        }
                        else
                        {
                            $player = new Player($account->getAccountCharacter());
                            if ($player->getFlag()==player_flags::FRESH && $player->getSex()==0)
                                $vk->sendButton($id, character_message::CHARACTER_SELECT_SEX_MESSAGE, [[BTN_CREATE_CHARACTER_FEMALE],[BTN_CREATE_CHARACTER_MALE],[BTN_CREATE_CHARACTER_OTHER]],$one_time);
                            else 
                                LogDebug::log_error("some weird here");
                                //$vk->sendMessage($id, "нечего делать");
                        }
                    }
                    break;
                    case 'clean':
                    case 'clear':
                    case 'клин':
                    case 'клир':
                    {
                        try
                        {
                            $vk->sendButton($peer_id, "Убираем кнопки",-1);
                        }
                        catch (VkApiException $e)
                        {
                            LogDebug::log_error($e->getMessage());
                        }
                                        
                        //$vk->sendButton($id, 'Убираем кнопки',0,0,1);
                        //$vk->sendMessage($peer_id, 'тест'); 
                    
                    }
                    break;
                    case 'quest':
                    case 'квест':
                    case 'задание':
                    case 'куинфо':
                    {
                        $internal_quest_id = (int)$message_array[1];
                                                
                        //$player=BaseCheck($id);
                        if ($player)
                        {
                            $inventory = $player->GetInventory();
                            $quest_id=$player->getQuestIdFromList($internal_quest_id);
                            if (!$quest_id)
                            {
                                $vk->sendButton($id, "Нет задания", [[BTN_CONTINUE]]);
                                return;
                            }
                            $questTemplate = new QuestMgr($quest_id);
                       
                            $quest_name = $questTemplate->getName();
                            $quest_title = $questTemplate->GetTitle();
                            $quest_objective = $questTemplate->getObjective();
                            $rewards = $questTemplate->getRewards();
                            $money = $questTemplate->getRewardMoney();
                            $message_send = "Информация по заданию: \n";
                            $message_send.="??????????????????????????? \n".
                            "Задание: \n".
                            $quest_name."\n".
                            "!----------------------------------------! \n".
                            "Суть: \n".
                            $quest_title."\n".
                            "@----------------------------------------@ \n".
                            "Задачи: \n".
                            $quest_objective."\n".
                            "@----------------------------------------@ \n";
                            
                            if ($money>0)
                            {
                                  $message_send.=
                                  "----------------Деньги:----------------- \n".
                                  $money." \n";
                            }

                            if (!empty($rewards))
                            {
                                $message_send.="Предметы, которые вы получите: \n";
                                foreach ($rewards as $key=>$value)
                                {
                                    $item = new Item($value);
                                    $message_send.=$key.")".$item->GetName()."\n";
                                    //TODO MAKE A DIFFERENT COUNTER FOR THAT!
                                    //TODO AS SOON AS POSSIBLE!
                                }
                            }
                            
                            $buttons = array();
                            //[["choise" => $quest_id."_".$key], $button_caption, "red"];
                            //$buttons[] = [["choise"=>$quest_id."_new"], main_messages::BUTTON_STATRT_TEXT, "blue"];
                            //$test = "blabllala";
                            $buttons[]=[["choise"=>$quest_id.'_new'], main_messages::BUTTON_START_TEXT, "red"];
                            $vk->sendButton($id, $message_send,[$buttons], $one_time); // there is need to write button for quest
                        }
                        
                    }
                    break;
                   
                    case 'меню':
                    {
                          //if (count$message_array) it's like $contentMgr->view('menu') or something!
                          
                    }
                    break;
                    case 'кто':
                    {
                        //$player = BaseCheck($id);
                        
                        if ($player)
                        {
                            $player_location=$player->GetLocation();
                            $player_sublocation = $player->GetSublocation();
                            $creaturePlacer = new CreaturePlacer($player_location, $player_sublocation);
                            $creatureList = $creaturePlacer->getCreatureList();
                            if (!$creatureList)
                            {
                                $vk->sendMessage($id, "Никого вокруг нет");
                                return;
                            }
                            $send_message=null;
                            foreach ($creatureList['id'] as $key =>$value)
                            {
                                $creature = new Creature($value);
                                $conv_id = $creaturePlacer->getConversation($value);
                                $auto_id = $creatureList['autoid'][$key];
                                $send_message.=$creature->GetName() . " \n";
                            }
                            
                            if ($send_message)
                                $vk->sendMessage($id, $send_message);
                            
                        }
                    }
                    break;
                    case 'квест': //TODO WHAT IS IT?
                    {
                        if (count($message_array)>2)
                        {
                            $vk->sendMessage($id, "не то");
                            return;
                        }
                        
                        $quest_id_from_player_list = $message_array[1];
                        //$loginSystem=new LoginSystem();
                        //$account = new AccountSystem($id);
                        //$login_result = $loginSystem->Login($id);
                        //$player = BaseCheck($id);
                        if ($player)
                        {
                            //$player = new Player($account->getAccountCharacter());
                            $inventory = $player->GetInventory();
                            $player_quests = $player->PlayerQuests();
                            //$quest_id = $quest_id_from_player_list; // that is been having as quest storage link?
                            $quest_id =  $player->getQuestIdFromList($quest_id_from_player_list);
                            
                            if ($player->isHaveQuest($quest_id))
                            {
                            
                              
                              $counter = count($send_buttons);
                              if (!$counter)
                              {
                                  
                                  return;
                              }
                              $message_variants = getVariantText($player, $quest_id);
                              $vk->sendButton($id, $message_variants, $send_buttons, $one_time);
                            }
                            //if (count$message_array)
                        }
                    }
                    break;
                 }
            }
            $account = null;
            //Если нажата кнопка начать
            // THERE IS CAN BE SOME CHANGED! DIDN'T UNDERSTANT HOW NOW, COZ IM FCKING STONED!
            if (isset($message) && mb_strtolower($message) == character_message::CHARACTER_START_COMMAND || mb_strtolower($message) == "старт") // isset($payload['action']) or  
            { 
                
                if ($loginSystem->Login($id))
                {
                    $account = new AccountSystem($id);
                    if (!$account->getAccountCharacter())
                        $vk->sendButton($id, character_message::CHARACTER_SET_NAME_TEXT);
                    else
                        $vk->sendButton($id, character_message::CHARACTER_CONTINUE_TEXT, [[BTN_CONTINUE]],$one_time);
                }
                else 
                    $vk->sendButton($id, character_message::CHARACTER_START_MESSAGE, [[BTN_START]],$one_time);
            }
            else 
            {
                if ($payload != null)
                { // если payload существует
                    if ($payload['rumors']!=null)
                    {
                        $rumors_array = explode('_', $payload['rumors']);
                        $rumor_id = $rumors_array[0];
                        $npc_id=$rumors_array[1];
                        $dialog_id=$rumors_array[2];
                        $conv_id = $rumors_array[3];                       
                        $player = BaseCheck($id);
                        
                        if ($player)
                        {
                            $rumorMgr = new RumorsMgr();
                            $text = $rumorMgr->getText($rumor_id);
                            $link = $rumorMgr->getLink($rumor_id);
                            $message_send = $link." \n";
                            $message_send.=$text."\n";
                            $buttons = array();
                            $buttons[]=[["conversation"=>$npc_id.'_'.$dialog_id.'_'.$conv_id], "Я все понял, спасибо.", "blue"];
                            $player->collectRumor($rumor_id);
                            $vk->sendButton($id, $message_send, [$buttons]);
                        }
                    }
                    if ($payload['conversation']!=null)
                    {
                        $conv_array = explode('_',$payload['conversation']);
                        $npc_id = $conv_array[0];
                        $dialog_id = $conv_array[1];
                        $conv_id = $conv_array[2];
                        
                        
                        $player = BaseCheck($id);
                        if ($player)
                        {
                           
                            
                            $creature = new Creature($npc_id);
                            $convMgr = new ConversationMgr($conv_id);
                            $creatureQuest = new CreatureQuest();
                            $buttons = array();
                            $max_dialog_id = $convMgr->getMaxDialogId($dialog_id);
                            
                            if ($dialog_id>$max_dialog_id)
                                $dialog_id=1;
                            
                            $rumorsMgr = new RumorsMgr();
                            $rumors = $rumorsMgr->parseRumor($convMgr->getDialogText($dialog_id));
                            $buttons[]=[["conversation"=>$npc_id.'_'.($dialog_id+1).'_'.$conv_id], "Узнать больше", "red"];
                            $quest_buttons = array();
                            $creaturePlacer = new CreaturePlacer($player->GetLocation(), $player->GetSublocation());
                            $auto_id = $creaturePlacer->getAutoidFromId($npc_id);
                            
                            $hasQuests = $creatureQuest->getQuest($auto_id);
                            $playerQuests = $player->PlayerQuests();
                            if ($rumors)
                            {
                                $message_send="&#9993;".$creature->GetName()."&#9993; \n".$rumors;
                                $rumor_id = $rumorsMgr->getRumorId();
                                $buttons[]=[["rumors"=>$rumor_id.'_'.$npc_id.'_'.$dialog_id.'_'.$conv_id], $rumorsMgr->getLinkFromHere(), "green"];
                            }
                            else 
                            {
                                $message_send = "&#9993;".$creature->GetName()."&#9993; \n". $convMgr->getDialogText($dialog_id);
                                if ($dialog_id==$max_dialog_id && $hasQuests)
                                {                                    
                                        foreach ($hasQuests['quest_id'] as $key=>$value)
                                        {
                                            $skip=false;
                                            
                                            if ($playerQuests)
                                            {
                                                foreach($playerQuests['quest_id'] as $key2=>$value2)
                                                {
                                                    if ($value == $value2)
                                                        $skip=true;
                                                        //printf ('quest # %s <br>', $value);
                                                }
                                            }
                                            if (!$skip)
                                            {
                                                $questMgr = new QuestMgr($value);
                                                $quest_buttons[]=[[["accept_quest"=>$value.'_'.$dialog_id.'_'.$npc_id.'_'.$conv_id], $questMgr->getName(), "blue"]];
                                                $test.=$value;
                                            }
                                       }
                                    
                                }
                            }
                            if (!empty($quest_buttons))    
                                $vk->sendButton($id, $message_send, $quest_buttons, $one_time); 
                            else
                                $vk->sendButton($id, $message_send, [$buttons], $one_time);
                        }
                        
                        
                    }
                    if ($payload['makeStep']!=null)
                    {
                        if ($player)
                        {
                            $step = $payload['makeStep']-1;
                            //TODO that is need to be some write as it per one day, or somelike!
                            $queueMgr = new QueueMgr($player);
                            $instance = $queueMgr->getInstance();
                            $line = $instance->getMapLine();
                            $step_result = $instance->makeStep($step);
                            switch ($step_result)
                            {
                                case step_result::COMPLETE:
                                    $vk->sendButton($id, "Вы успешно прошли это подземелье, ваша награда: 50 опыта и 20 рублей", [[BTN_CONTINUE]]);
                                break;
                                case step_result::DONE:
                                    $line=$instance->getMapLine();
 
                                    $buttons_send=array();
                                    foreach ($line as $key=>$value)
                                    {
                                        $test = $key+1;
                                        // TODO SOME ADDITIONAL!
                                        $buttons_send[]=[["makeStep"=>$test], "Путь № ".$test, "blue"];
                                    }
                                    $vk->sendButton($id, "Вы выбрали верный путь, вы устали на 3 единицы", [$buttons_send]);
                                break;
                                case step_result::DOOR_OPENED:
                                    $line=$instance->getMapLine();
                                    
                                    $buttons_send=array();
                                    foreach ($line as $key=>$value)
                                    {
                                        $test = $key+1;
                                        // TODO SOME ADDITIONAL!
                                        $buttons_send[]=[["makeStep"=>$test], "Путь № ".$test, "blue"];
                                    }
                                    $vk->sendButton($id, "Вы открыли дверь, вы устали на 4 единицы", [$buttons_send]);
                                    break;
                                case step_result::FAIL:
                                    $buttons_send=array();
                                    foreach ($line as $key=>$value)
                                    {
                                    // TODO SOME ADDITIONAL!
                                        $test = $key+1;
                                        $buttons_send[]=[["makeStep"=>$test], "Путь № ".$test, "blue"];
                                    }
                                    //$vk->sendMessage($id, "what?");
                                    //$instance->shitTest();
                                    //$player->modifyFatigue(4);
                                    $vk->sendButton($id, "Вы выбрали неверный путь, вы устаёте на 5 единиц.", [$buttons_send]);
                                    //$vk->sendButton($id, "Не верный путь.", [[BTN_CONTINUE]]);
                                break;
                                case step_result::CANT_MOVE:
                                    $vk->sendButton($id, "В данный момент вы не можете двигаться. Вы слишком устали.");  
                                break;
                            }
                            
                            
                        }
                    }
                    if ($payload['accept_quest']!=null)
                    {
                        $accept_quest_array = explode('_', $payload['accept_quest']);
                        $quest_id = $accept_quest_array[0];
                        $dialog_id=$accept_quest_array[1];
                        $npc_id=$accept_quest_array[2];
                        $conv_id = $accept_quest_array[3];
                        $player = BaseCheck($id);
                        
                        if ($player)
                        {
                            $quest_accept_result = $player->AddQuest($quest_id);
                            
                            if ($quest_accept_result)
                            {
                                $buttons = array();
                                $buttons[]=[["conversation"=>$npc_id.'_'.$dialog_id.'_'.$conv_id], "Назад к диалогу.", "green"];
                                $buttons[]=[["choise"=>$quest_id.'_new'], "Приступить к заданию.", "white"];
                                $message_send = "Задание принято! \n";
                                $vk->sendButton($id, $message_send, [$buttons]);
                            }
                        }
                        
                    }
                    if ($payload['change_subloc']!=null)
                    {
                        /* 
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
                        */
                        if (!$player)
                        {
                            return;
                        }
                        
                        $player_location = $player->GetLocation();
                        $player_subloc = $player->GetSublocation();
                        $sublocationMgr = new SublocationMgr($player_location);
                        $new_subloc = $payload['change_subloc'];
                        $fatigue_loose = $sublocationMgr->getFatigueLoose($new_subloc);
                        
                        if ($player->modifyFatigue($fatigue_loose))
                        {
                            $player->SetSublocation($new_subloc);
                            $vk->sendButton($id, "Успешный переход", [[BTN_CONTINUE]], $one_time);
                        }
                        else 
                            $vk->sendButton($id, "Не возможно перейти, вы слишком устали", [[BTN_CONTINUE]], $one_time);
                        
                        
                    }
                    if ($payload['choise']!=null)
                    {
                        
                        $array_choise = explode('_',$payload['choise']);
                        $quest_id = $array_choise[0];
                        $choise_id = $array_choise[1];
                        $rumors_parse = explode('r',$array_choise[2]); //rumors
                        $item_parse = explode('i', $array_choise[2]);
                        $money_parse = explode('m', $array_choise[2]);
                        $rumor_id = $rumors_parse[1];
                        $item_id = $item_parse[1];
                        $moneyLoose = $money_parse[1];
                        $player = BaseCheck($id);
                        if ($player)
                        {
                            
                            $inventory = $player->getInventoryInstance();
                            if ($player->isHaveQuest($quest_id))
                            {
                                if ($rumor_id)
                                {
                                    $achievMgr->logQuestAchiev(quest_log::RUMORS_FINDED, $rumor_id);
                                }
                                
                                if ($item_id)
                                {
                                    if ($inventory->HaveItemInInventory($item_id))
                                    {
                                        $inventory->UseItem(0,$player, $item_id);
                                    }
                                    
                                    
                                }
                                
                                if ($moneyLoose)
                                {
                                    if ($player->GetMoney()>=$moneyLoose)
                                    {
                                        $player->modifyMoney(-$moneyLoose);
                                    }
                                }
                                    if ($choise_id=="new")
                                    {  
                                        $message_variants = getVariantText($player, $quest_id);
                                        $send_buttons = GetButtonsForQuest($player, $quest_id);
                                        if (!$send_buttons)
                                        {

                                            $scores=$player->GetCurrentQuestScores($quest_id);
                                            if ($scores==100)
                                            {
                                                $questMgr= new QuestMgr($quest_id);
                                                $result = checkForComplete($scores, $player,$questMgr);
                                                switch ($result)
                                                {
                                                    case quest_result::TIME_FAIL:
                                                        $vk->sendButton($id, "Время отведенное на задание закончилось, вы неможете его выполнить.",[[BTN_CONTINUE]], $onetime);
                                                    break;
                                                    case quest_result::ERROR_INVENTORY:
                                                        //return 'Нет свободного места!';
                                                        $vk->sendButton($id, "Нет свободного места!", [[BTN_CONTINUE]], $one_time);
                                                    break;
                                                    case quest_result::ERROR_FATIGUE:
                                                        $vk->sendButton($id, "Вы слишком устали, чтобы закончить начатое", [[BTN_CONTINUE]], $one_time);
                                                        //return 'Вы слишком устали, чтобы закончить начатое';
                                                    break;
                                                    case quest_result::ALL_OK:
                                                    case (-1):
                                                        //return 'Вы успешно завершили задание';
                                                        $vk->sendButton($id, "Вы успешно завершили задание.", [[BTN_CONTINUE]], $one_time);
                                                    break;
                                                    case (quest_result::QUEST_FAIL):
                                                        $vk->sendButton($id, "Задание провалено. Биваит.", [[BTN_CONTINUE]]);
                                                    break;
                                                    
                                                }
                                                //$result_quest_step = makeQuestStep($player, $quest_id, $choise_id);
                                            }
                                            
                                        }
                                        try
                                        {
                                            $vk->sendButton($id, $message_variants, $send_buttons, $one_time);
                                        }
                                        catch (VkApiException $e)
                                        {
                                            LogDebug::log_msg($e->getMessage());
                                        }
                                        
                                        return;
                                    }
                                    
                                    $result_quest_step = makeQuestStep($player, $quest_id, $choise_id);
                                    if ($result_quest_step==1)
                                    {
                                       
                                        $send_buttons = GetButtonsForQuest($player, $quest_id);
                                        $counter = count($send_buttons);
                                        
                                        if (!$counter)
                                        {
                                            return;
                                        }
                                        
                                        $message_variants = getVariantText($player, $quest_id);
                                        
                                        //$vk->sendMessage($id, $message_variants);
                                        $vk->sendButton($id, $message_variants, $send_buttons, $one_time);
                                    }
                                    else
                                    {
                                        
                                        switch ($result_quest_step)
                                        {
                                            case quest_result::ERROR_INVENTORY:
                                                //return 'Нет свободного места!';
                                                $vk->sendButton($id, "Нет свободного места!", [[BTN_CONTINUE]], $one_time);
                                            break;
                                            case quest_result::TIME_FAIL:
                                                $vk->sendButton($id, "Время отведенное на задание закончилось, вы неможете его выполнить.",[[BTN_CONTINUE]], $onetime);
                                            break;
                                            case quest_result::ERROR_FATIGUE:
                                                $vk->sendButton($id, "Вы слишком устали, чтобы закончить начатое", [[BTN_CONTINUE]], $one_time);
                                                //return 'Вы слишком устали, чтобы закончить начатое';
                                            break;
                                            case quest_result::ALL_OK:
                                            case -1:
                                                //return 'Вы успешно завершили задание';
                                                $vk->sendButton($id, "Вы успешно завершили задание.", [[BTN_CONTINUE]], $one_time);
                                            break;
                                            case (quest_result::QUEST_FAIL):
                                                $vk->sendButton($id, "Задание провалено. Биваит.", [[BTN_CONTINUE]]);
                                            break;
                                        }
                                    }
                            }
                        }
                    }
                    
                    
                    switch ($payload['actions'])
                    { //Смотрим что в payload кнопках
                        case 'start_first':
                        {
                            $quest_id = event_quest::START_QUEST;
                            $player = BaseCheck($id);
                            if ($player)
                            {
                                $inventory = new Inventory($player->GetId());
                                $player_quests = $player->PlayerQuests();
                                if ($player->isHaveQuest($quest_id))
                                {
                                    $send_buttons = GetButtonsForQuest($player, $quest_id);
                                    $counter = count($send_buttons);
                                    if (!$counter)
                                    {
                                        return;
                                    }
                                    $message_variants = getVariantText($player, $quest_id);
                                    $vk->sendButton($id, $message_variants, $send_buttons, $one_time);
                                }
                           }
                        }
                        break;
                        case 'Create': //TODO make a lower!
                            //$loginSystem = new LoginSystem();
                            $login = $id;
                            $result_register=$loginSystem->Register($login);
                            if ($result_register)
                            {
                                $account = new AccountSystem($id);
                                $acc_char = $account->getAccountCharacter();
                                if (!$acc_char)
                                    $vk->sendMessage($id, character_message::CHARACTER_SET_NAME_TEXT);                            }
                            else
                            {   
                                // TODO: that can be rewrited like $player = BaseCheck($id) and !$player, or not...
                                // TODO: THIS FUCKING SHIT IS SUX~!
                                
                                $login_result = $loginSystem->Login($id);
                                $account = new AccountSystem($id);
                                $acc_char = $account->getAccountCharacter();
                                if ($login_result && !$acc_char)
                                    $vk->sendButton($id, character_message::CHARACTER_SELECT_SEX_MESSAGE, 
                                        [[BTN_CREATE_CHARACTER_FEMALE],
                                        [BTN_CREATE_CHARACTER_MALE],
                                        [BTN_CREATE_CHARACTER_OTHER]],$one_time);
                                else 
                                    $vk->sendButton($id, "Продолжить", [[BTN_CONTINUE]]);
                                
                            }
                        break;
                        case 'select_sex_lol':                       
                            $vk->sendButton($peer_id, character_message::CHARACTER_RETURN_TEXT,[[BTN_CONTINUE]]);
                        break;
                        case 'select_sex_male':
                            $account = new AccountSystem($peer_id);
                            $player = BaseCheck($peer_id);
                            if ($player)
                            {
                                $player->setSex(player_sex::MALE);
                                $player->AddQuest(event_quest::START_QUEST);
                                $player->AddQuest(event_quest::DEV_GREET);
                                try
                                {
                                   $vk->sendButton($id, character_message::CHARACTER_CONTINUE_TEXT, [[BTN_START_FIRST]],$one_time);
                                }
                                catch (VkApiException $e)
                                {
                                    LogDebug::log_msg($e->getMessage());
                                }
                               
                                //TODO: I HATE PHP!
                            }
                        break;
                        case 'select_sex_female':
                            $account = new AccountSystem($id);
                            //$loginSystem = new LoginSystem();
                            $player = BaseCheck($id);
                            
                            if ($player)
                            {
                                $player->setSex(player_sex::FEMALE);
                                $player->AddQuest(event_quest::START_QUEST);
                                $player->AddQuest(event_quest::DEV_GREET);
                                $vk->sendButton($id, character_message::CHARACTER_CONTINUE_TEXT, [[BTN_START_FIRST]],$one_time);
                            }
                        break;
                        
                        case 'continue':
                            $player=BaseCheck($id);
                            $loginSystem = new LoginSystem();
                            // TODO REWRITE THAT SHIT! FASTER AS CAN IT BE!
                            $account = new AccountSystem($id);
                            if (!$loginSystem->Login($id))
                            {
                                try
                                {
                                    $vk->sendButton($peer_id, character_message::CHARACTER_START_MESSAGE, [[BTN_START]]);
                                }
                                catch (VkApiException $e)
                                {
                                    LogDebug::log_msg($e->getMessage());
                                }
                                return;
                            }
                            if (!$account->getAccountCharacter())
                            {
                                try
                                {
                                    $vk->sendButton($id, character_message::CHARACTER_SET_NAME_TEXT);
                                }
                                catch (VkApiException $e)
                                {
                                    LogDebug::log_msg("account is some shit here: ".$e->getMessage());
                                }
                                return;
                            }
                            
                            
                            if (!$player)
                            {
                                
                                $vk->sendButton($peer_id, character_message::CHARACTER_START_MESSAGE, [[BTN_START]]);
                                return;
                            }
                            
                            if ($player && !$player->GetName())
                            {
                                try
                                {
                                    $vk->sendButton($id, character_message::CHARACTER_SET_NAME_TEXT);
                                }
                                catch (VkApiException $e)
                                {
                                    LogDebug::log_msg($e->getMessage());
                                }
                                return;
                            }
                            
                            if ($player && $player->getSex()==0)
                            {
                                try
                                {
                                    $vk->sendButton($id, character_message::CHARACTER_SELECT_SEX_MESSAGE, [[BTN_CREATE_CHARACTER_FEMALE],[BTN_CREATE_CHARACTER_MALE],[BTN_CREATE_CHARACTER_OTHER]],$one_time);
                                }
                                catch (VkApiException $e)
                                {
                                    LogDebug::log_msg($e->getMessage());
                                }
                                return;
                            }
                            $className = $player->getClassName();
                            if ($player && !$className)
                            {
                                try
                                {
                                    $vk->sendButton($peer_id, "Определитесь со своей ролью в мире...",[[BTN_START_FIRST]]);
                                }
                                catch (VkApiException $e)
                                {
                                    LogDebug::log_msg($e->getMessage());
                                }
                                return;
                            }
                           
                            if ($player && !$player->GetName())
                            {
                                try 
                                {
                                    $vk->sendButton($peer_id, character_message::CHARACTER_SET_NAME_TEXT,-1);
                                }
                                catch (VkApiException $e)
                                {
                                    LogDebug::log_msg($e->getMessage());
                                }
                                    
                                return;
                            }
                            if ($player)
                            {
                                
                                $name = $player->GetName();
                                
                                $className = $player->getClassName();
                                $fatigue = $player->GetFatigue();
                                $max_fatigue = $player->getMaxFatigue();
                                $exp = $player->GetCurrXp();
                                $lvl = $player->GetLevel();
                                $money = $player->GetMoney();
                                //
                                $message_send = "Вас зовут: \n";
                                $message_send.=$name."\n";
                                $message_send.="Вы: ".$className." \n";
                                $message_send.="Усталость: ".$fatigue."/".$max_fatigue." \n";
                                $message_send.="Текущий опыт: ".$exp." \n";
                                $message_send.="Текущий уровень: ".$lvl." \n";
                                $message_send.="Деньги: ".$money." \n";
                                $message_send.= "Вы сейчас в городе: ".$locationMgr->GetLocationName($player->GetLocation())." \n";
                                $sublocationMgr = new SublocationMgr($player->GetLocation());
                                $player_sublocation = $player->GetSublocation();
                                $player_location = $player->GetLocation();
                                $player_sublocation_name = $sublocationMgr->getSublocationNameFromId($player_sublocation);
                                $message_send.="Ваше местоположение в городе: ".$player_sublocation_name." \n";
                                $message_send.="!!!!-------------------------!!!! \n";
                                // that is moment we will some fuckuped!
                                
                                $creaturePlacer = new CreaturePlacer($player_location, $player_sublocation);
                                
                                $creatureList = $creaturePlacer->getCreatureList();
                                if ($creatureList)
                                {
                                    $message_send.="Кто здесь находится: \n";
                                    //$message_send.="";
                                    foreach ($creatureList['id'] as $key => $value) 
                                    {
                                        // echo $value;
                                        $creature = new Creature($value);
                                        $conv_id = $creaturePlacer->getConversation($value);
                                        $auto_id = $creatureList['autoid'][$key];
                                        //var_dump($conv_id);
                                        if ($conv_id)
                                            // echo $conv_id; <a href="test.php" rel="modal:open">example</a>
                                            $link = "&#10067;(".$auto_id.")" . $creature->GetName() . " \n";
                                            else
                                                $link = $creature->GetName() . "\n";
                                         $message_send.=$link;
                                         
                                    }
                                    $message_send.="Для того, чтобы поговорить с персонажем, введите: вопрос Номер персонажа \n";
                                    $message_send.="!!!!-------------------------!!!! \n";
                                }
                                
                                $player_quests = $player->PlayerQuests();
                                if (!empty($player_quests))
                                {
                                    $message_send.=messages_strings::YOUR_QUESTS."\n";
                                    // TODO REWRITE THAT PLACE AS SOON AS CAN!
                                    foreach ($player_quests['quest_id'] as $key => $value)
                                    {
                                        if ($player_quests['complete'][$key] == quest_status::COMPLETED || $player_quests['complete'][$key] == quest_status::FAILED)
                                        {
                                            continue;
                                        }
                                        $questTemplate = new QuestMgr($value);
                                        // здесь появилась реально непонятная херня.
                                        // как мне выводить квесты просто как списком?
                                        // $player_quests['quest_id'][$key]
                                        $message_send.= $key.".) ".$questTemplate->getName()." \n";
                                    }
                                    
                                }
                                if (empty($player_quests))
                                    $message_send = "Нет заданий. \n";
                                
                                $inventory = $player->getInventoryInstance();
                                
                                $inventory_items = $inventory->GetInventory();
                                $message_send.="Инвентарь: \n";
                                if (empty($inventory_items))
                                    $message_send.="Нет вещей. \n\n";
                                else 
                                {
                                    $message_send.="Ваши предметы: \n";
                                    foreach ($inventory_items['item_id'] as $key=>$value)
                                    {
                                        $item = new Item($value);
                                        $charges = $inventory->GetCharges($inventory_items['total_id'][$key]);
                                        $additional = "";
                                        if ($charges)
                                        {
                                            $additional =" : (".$charges."/".$item->GetCharges().")";
                                        }
                                        $message_send.=$item->GetName().$additional." | ";
                                    }
                                }
                                $message_send.="\n";
                                $first_available_quest_player_id = $player->getFirstNotCompletedQuestPlayerKey();
                                
                                $quest_id = $player->getQuestIdFromList($first_available_quest_player_id);
                                
                                if($first_available_quest_player_id)
                                {
                                    $button = [["choise"=>$quest_id.'_new'], "Начать задание № ".$first_available_quest_player_id, "blue"];
                                }
                                else 
                                    $button = BTN_CONTINUE;
                                
                                $message_send.="Для того чтобы получить информацию по заданию введите: куинфо Номер квеста. \n";
                                $message_send.="Для того чтобы выйти в это меню - введите: старт или начать \n";
                                $message_send.="Для того чтобы посмотреть игроков вокруг - введите: кто \n";
                                $message_send.="Для перемещения в городе используйте команду перейти \n";
                                $message_send.="Для прохождения случайного подземелья используйте команду: осмотр \n";
                                $vk->sendButton($id, $message_send,[[$button]]/*,TODO: make quest button for first from list of allowed!*/);
                            }
                            else 
                            {
                                $vk->sendButton($peer_id, character_message::CHARACTER_SET_NAME_TEXT, [BTN_START]);
                            }
                        break;
                        default:
                        break;
                    }
                }
            } 
            if ($player)
            {
                $player->setOnline(player_ingame::ONLINE);
                $player->setLastActivityTime();
                if ((int)$peer_id==3539960)
                {
                    //$vk->sendMessage($peer_id, "Test");
                    if ($player->GetName())
                    {
                        //$vk->sendMessage($peer_id, "cписок команд: привет (номер персонажа), старт, куинфо № квеста, инфо");
                    }
                }
            
                
            }
}
//echo '<br><br><br>Время выполнения скрипта: ' . (microtime(true) - $start) . ' сек.';
LogDebug::log_msg('Время на выполнение: '.(microtime(true) - $start) . ' сек.');