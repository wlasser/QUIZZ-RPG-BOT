
<?php

// так, что делать то?
/*
 * что блдяь111
 *
 * так у нас есть нпс, у нас есть выбор оружия
 * выбор огнестрельного оружия допускает выбор патронов для этого оружия
 * как это сделать?
 * оружие менять можно - допустим каждые 3 шага
 * как это сделать?
 *
 * Итак базис набросал, что теперь?
 * Теперь нужно отрисовку ходов сделать.
 * Выбор оружия
 * Так. Мне нужно, в случае пистолета - использовать пули. Как это сделать?
 *
 * делая шаг - нужно передать кто ходит?
 * сделав шаг - получаем результат? и ответку, логируем это
 *
 *
 *
 */
// '<p><a href="battle.php?quest_id='.$quest_id.'&npc_id='.$quest_tmp['req_thing'][$key].'&quest_variant='.$key.' rel="modal:open"">
include_once 'includes.php';
$configHelper = new ConfigHelper();
if ($configHelper->getCurrentScriptName() != 'index.php' || $configHelper->getCurrentScriptName() == 1) //magic number, can we move it on defines?
    die('Не возможно выполнить действие!');
    $weapon_total_id = $_POST['weapon_total_id'] ?? '';
// echo $weapon_total_id;
/*
            quest_id : quest_id,
			npc_id: npc_id,
			quest_var: quest_var
*/
$account = new AccountSystem();
$player_id = $account->getAccountCharacter() ;
// 
if (isset($_POST['npc_id'])) {
    setcookie("npcId", $_POST['npc_id']);
    $npc_id = $_POST['npc_id'];
}
// if ()
if (isset($_COOKIE['npcId']))
    $npc_id = $_COOKIE['npcId'];
    
    //echo "<B> NPC ID!!!:".$npc_id." </b><br><br> \n";
    $quest_id = $_POST['quest_id'] ?? '';
    $quest_variant = $_POST['quest_var'] ?? '';

if ($weapon_total_id > 0 && $npc_id > 0) {
        $bullet_total_id = $_POST['bullet_total_id'] ?? '';
    
    $player = new Player($player_id);
    $npc = new Creature($npc_id);
    $inventory = new Inventory($player_id);
    
    $battleMgr = new BattleMgr($player, $npc);
    
    $battleMgr->makeStep($weapon_total_id, $bullet_total_id);
    
    /*
     * if ($bullet_total_id>0)
     * die ('Пуля:'.$bullet_total_id);
     */
} else {
    // $npc_id = '';
    
    // $who_start = rand(1,2);
    // $npc_id = $_COOKIE['npcId'] ?? $_GET['npc_id'] ?? '';
    // echo $who_start;
    $player = new Player($player_id);
    $npc = new Creature($npc_id);
    
    setcookie("npcId", $npc->GetId());
    $inventory = new Inventory($player_id);
    
    // print_r($inventory);
    echo '<select name="weapon_select" onChange="weapon_select(this)">';
    echo '<option value="" disabled selected style="display:none;">Выберите оружие</option>';
    foreach ($inventory->GetInventory()['item_id'] as $key => $value) {
        $item = new Item($value);
        
        if ($item->isWeapon($inventory))
            printf('<option value="%s" id="weapon_select">%s</option>', $inventory->GetInventory()['total_id'][$key], $item->GetName());
    }
    echo "</select>";
    
    $battleMgr = new BattleMgr($player, $npc);
    echo '<div id="select_bullet"></div>';
    echo '<div id ="battleLog"></div>';
    echo '<input type="button" name="continue" id="battleStep" value="Сделать шаг">';
    // echo $npc_id;
}

?>



