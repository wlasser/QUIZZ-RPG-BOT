<?php
function GetButtonsForQuest(Player $player, $quest_id)
{
    $buttons = array();
    $questMgr = new QuestMgr($quest_id);
    $questSteps = new QuestStepsMgr();
    $scores= $player->GetCurrentQuestScores($quest_id);
    
    $questTmp = $questSteps->GetShit($quest_id, $scores);
        
    foreach ($questTmp['variants'] as $key=>$value)
    {
        
        //$show_it=$value;
        
        if ($questTmp['req_type'][$key])
        {
            $check = $questSteps->checkRequirements($questTmp['req_type'][$key], $questTmp['req_thing'][$key], $player);
            //this part of code - sucks, like noone sucks!
            if ($check)
            {
                switch ($check)
                {
                   
                    case variants_type::ITEM:
                        $button_caption = mb_strimwidth($value,0,40);
                        $buttons[]=[[["choise" => $quest_id."_".$key."_i".$questTmp['req_thing'][$key]], $button_caption, "red"]];
                    break;
                    case variants_type::RUMORS:
                        $button_caption = mb_strimwidth($value,0,40);
                        $buttons[]=[[["choise" => $quest_id."_".$key."_"."r".$questTmp['req_thing'][$key]], $button_caption, "green"]];
                    break;
                    case variants_type::PLAYER_CLASS:
                        $button_caption = mb_strimwidth($value,0,40);
                        $buttons[]=[[["choise" => $quest_id."_".$key.'_'."c".$questTmp['req_thing'][$key]], $button_caption, "blue"]];
                    break;
                    case variants_type::NPC_FIGHT:
                        $player->initQuestFight($questTmp['req_thing'][$key], $quest_id, $key, 0);
                        if (!$player->isQuestFightComplete($questTmp['req_thing'][$key], $quest_id))
                        {
                            $button_caption = messages_strings::START_BATTLE;//mb_strimwidth($value,0,40);
                            $buttons[]=[[["choise" => $quest_id."_".$key."_"."b".$questTmp['req_thing'][$key]], $button_caption, "red"]];
                        }   
                        else
                        {
                            //$show_it = 'There is passed, when quest battle is complete!!!';
                        }
                   break;
                   case variants_type::MONEY:
                        $button_caption=mb_strimwidth($value,0,40);
                        $button_caption.="-".$questTmp['req_thing'][$key];
                        $buttons[]=[[["choise" => $quest_id."_".$key."_m".$questTmp['req_thing'][$key]], $button_caption, "red"]];
                   break;
                }
            }
        
        }
        else
        {

                if ($value)
                {
                    
                    $button_caption = mb_strimwidth($value,0,40);
                    $buttons[]=[[["choise" => $quest_id."_".$key], $button_caption, "white"]];
                    
                }
        }
        
    }
    
    return $buttons;
}

function checkForComplete($scores, Player $player, QuestMgr $questTemplate)
{
    $quest_id = $questTemplate->GetQuestId();
    
    // all that is not good, there is need to be rewrited - first as advanced check, next - as subsystem or something
    if ($scores>=event_quest::MAX_QUEST_SCORE)
    {
    
        switch ($player->questCompleted(($quest_id)))
        {
            case TRUE:
                //die ('Вы завершили задание');
                $result= 100;    
            break;
            case FALSE: // @FIXME move it to some advanced check, now it's bad
                switch ($scores)
                {
                    case 100:
                        //continue;

                    break;
                    case 1000: // hunter
                        $player->createPlayerDefaultStats(player_classes::HUNTER);
                        break;
                    case 2000: // detective
                        $player->createPlayerDefaultStats(player_classes::DETECTIVE);
                        break;
                    case 3000: // readers
                        $player->createPlayerDefaultStats(player_classes::READERS);
                        break;
                    case 4000: // witchunter
                        $player->createPlayerDefaultStats(player_classes::WITCHHUNTER);
                    break;
                }
            break;
        }
        
        $result = $questTemplate->completeQuest($quest_id, $player);
    }

    if (!empty($result) && $result!=(-500))
        return $result;
    
    return false;
}

function makeQuestStep(Player $player, $quest_id, $choise_id)
{
    $questSteps = new QuestStepsMgr();
    $questMgr = new QuestMgr($quest_id);
    $scores= $player->GetCurrentQuestScores($quest_id);
    $collectResult = $questSteps->CollectScores($choise_id, $quest_id, $scores, $player);
    switch ($collectResult)
    {
        case quest_result::ERROR_INVENTORY:
            return quest_result::ERROR_INVENTORY;
        break;
        case quest_result::TIME_FAIL:
            return quest_result::TIME_FAIL;
        break;
        case quest_result::QUEST_FAIL:
            return quest_result::QUEST_FAIL;
        break;
    }
    $new_scores = $player->GetCurrentQuestScores($quest_id);
    $complete_result = checkForComplete($new_scores, $player, $questMgr);
    if (!$complete_result)
        return TRUE;
    
    if ($complete_result==100)
    {
        return -1;
    }
    
    if ($complete_result!=100)
    {
        return $complete_result;
    }
        
}

function BaseCheck($id)
{
    
    $loginSystem = new LoginSystem();
    $login_result = $loginSystem->Login($id);
    if ($login_result)
    {
        $account = new AccountSystem($id);
        $player = new Player($account->getAccountCharacter());
        return $player;
    }
    else
        return FALSE;
    
}

function getVariantText(Player $player, $quest_id)
{
    $questMgr = new QuestMgr($quest_id);
    $questSteps = new QuestStepsMgr();
    $scores= $player->GetCurrentQuestScores($quest_id);
    $questTmp = $questSteps->GetShit($quest_id, $scores);
    return $questTmp['greetings'];
}
