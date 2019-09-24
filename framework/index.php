<html>
<head>
<title>Supernatural Text-RPG</title>

<script src="js/jquery.min.js"></script>
<link href="css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jplayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="js/jplayer/jplayer.playlist.min.js"></script>
<script src="js/jquery.qtip.min.js"></script>
<script type="text/javascript" src="js/alertify.js"></script>
<script src="js/script.js"></script>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" /> -->
<link rel="stylesheet" type="text/css" href="css/jquery.qtip.min.css" />
<link rel="stylesheet" type="text/css" href="css/playerStuff.css" />
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){

	new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_1",
		cssSelectorAncestor: "#jp_container_1"
	}, [
		{
			title:"IntroOutro1",
			mp3:"http://localhost/rpg/sounds/radio/stukalin-durst-d1.mp3"
		},
		{
			title:"Thin Ice",
			mp3:"http://localhost/rpg/sounds/radio/stukalin-durst-d2.mp3"
		}
	], {
		swfPath: "js/jplayer",
		supplied: "oga, mp3",
		wmode: "window",
		useStateClassSkin: true,
		autoBlur: false,
		smoothPlayBar: true,
		keyEnabled: true
	});
});
//]]>
</script>
<script>
//document.oncontextmenu = function (){return false};

$(document).on('show.bs.modal','#gameModal', function (e) {
	//alert("123");
    var link = $(e.relatedTarget);
    $(this).find(".modal-body").load(link.attr("href"));
});
</script>

</head>
<body>
<div class="modal fade" id="gameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div id="mediaplayer">
<div id="jquery_jplayer_1" class="jp-jplayer"></div>
<div id="jp_container_1" class="jp-audio" role="application" aria-label="media player">
	<div class="jp-type-playlist">
		<div class="jp-gui jp-interface">
			<div class="jp-controls">
				<button class="jp-previous" role="button" tabindex="0">previous</button>
				<button class="jp-play" role="button" tabindex="0">play</button>
				<button class="jp-next" role="button" tabindex="0">next</button>
				<button class="jp-stop" role="button" tabindex="0">stop</button>
			</div>
			<div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			</div>
			<div class="jp-volume-controls">
				<button class="jp-mute" role="button" tabindex="0">mute</button>
				<button class="jp-volume-max" role="button" tabindex="0">max volume</button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			</div>
			<div class="jp-time-holder">
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
			</div>
			<div class="jp-toggles">
				<button class="jp-repeat" role="button" tabindex="0">repeat</button>
				<button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<li>&nbsp;</li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span>Update Required</span>
			To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
		</div>
	</div>
</div>
</div>
</div>
<?php
error_reporting(6);
$start = microtime(true);
// todo
/*
 * необходимо сделать базовую реализацию квестов. старт есть, осталось только придумать как это будет выглядеть
 * пофиксить все классы
 * !оптимизировать код
 * ---
 *
 * ыыыыыыыыыыыыыыыыы
 */

include 'includes.php';

$accountSystem = new AccountSystem();

if (!$accountSystem->checkSession())
    die (header("Location: autorization.php"));

$player_id = $accountSystem->getAccountCharacter();
    
if ($player_id==0)
{
    die('<a data-remote="false" data-toggle="modal" data-target="#gameModal" href="create.php">Создать персонажа</a>');
}
$player = new Player($player_id);

if ($player->getFlag()==player_flags::FRESH)
    die('<a data-remote="false" data-toggle="modal" data-target="#gameModal" href="create.php">Создать персонажа</a>');

//$player_id = 1; // fix me

$recipeMgr = new RecipeMgr();

$garageMgr = new GarageMgr();
$garage = $garageMgr->LoadGarage($player_id);

$player_location = $player->GetLocation();
$player_sublocation = $player->GetSublocation();
$sublocationMgr = new SublocationMgr($player_location); // what????
                                                        
// var_dump($sublocationMgr);

echo "Гараж:  <br> \n";
// print_r($garage);
if ($garage)
{
foreach ($garage['vehicle_id'] as $key => $value) {
    $vehicleMgr = new VehicleMgr($value);
    echo $vehicleMgr->GetName() . '<br>';
}
}
echo '<div id="changeLoc">' . "<hr> <br> \n";
printf("Ваше местоположение: %s <br> \n", $locationMgr->GetLocationName($player_location));
printf("Ваше местонахождение в городе: %s <br> \n", $sublocationMgr->getSublocationNameFromId($player_sublocation));
// printf("Текущие координаты: x=%s <br> \n", $player->GetCoords($player->GetLocation()['x']));
/* echo $player->GetCoords($player->GetLocation())['x']); */

echo "<hr> <br> \n";
printf("Выбор для перемещения: <br> \n");
echo '<select name="location_select" onChange="location_select(this)">';
echo '<option value="" disabled selected style="display:none;">Список для выбора</option>';
$available_locations = $locationMgr->GetAvailableLocation($player->GetLocation());

foreach ($available_locations['name'] as $key => $value) {
    $distance = $locationMgr->GetDistance($locationMgr->GetCoordinates($player_location), $locationMgr->GetCoordinates($available_locations['id'][$key]));
    // echo "<option value=".$radio_id[$key].">".$value."</option> \n";
    printf('<option value="%s">%s -- Дистанция - %s км, усталость - %s </option>', $available_locations['id'][$key], $value, $distance, $locationMgr->GetFatigueDistance($distance));
}

echo "</select> \n";
echo "<br> \n";

$sublist = $sublocationMgr->getListForLocation();
echo '<br><hr><br>';

printf("Выбор для перемещения в городе: <br> \n");
echo '<select name="sublocation_select" onChange="sublocation_select(this)">';
echo '<option value="" disabled selected style="display:none;">Список для выбора</option>';
foreach ($sublist['name'] as $key => $value) {
    $fatigue_loose = $sublist['fatigue_loose'][$key];
    if ($sublist['id'][$key] == $player_sublocation)
        continue;
    printf('<option value="%s">%s - усталость - %s </option>', $sublist['id'][$key], $value, $fatigue_loose);
}
echo "</select><br><hr></div><br>";
// ----------------------------------------
echo '<div id="playerStats">';
printf("Имя: %s <br> \n Деньги: %s <br> \n", $player->GetName(), $player->GetMoney());
printf("Текущий опыт = %s/%s<br> \n", $player->GetCurrXp(), ExpMgr::GetNextLvlExpValue($player->GetLevel()));
printf("Текущий уровень = %s <br> \n", $player->GetLevel());
printf("<hr> \n Характеристики персонажа: <br> \n ");
printf("Проворность: %s <br> \n", $player->GetAccuracy());
printf("Интеллект: %s <br> \n", $player->GetIntellect());
printf("Сопротивление: %s <br> \n", $player->GetResistance());
printf("Броня: %s <br> \n", $player->GetArmor());
printf("Здоровье: %s/%s <br> \n", $player->GetHp(), $player->GetMaxHp());
printf("Усталость: %s/%s <br> <hr> \n", $player->GetFatigue(), $player->getMaxFatigue());
echo "</div> \n";
// --------------------------------------

printf("Профессия: %s Навык: %s", $player->GetProfession(), $player->GetProfessionSkill());
echo "<hr> <br> \n";

echo "<b>Инвентарь:</b> <br> \n";
echo '<div id="inventory">';
echo "</div><br> \n";
// квесты!
$available_quests = $player->GetAvailableZoneQuest();
$player_quests = $player->PlayerQuests();

$current_quests = array_diff($available_quests, $player_quests['quest_id']);
echo '<div id="questBox">';
echo "<h3>Ваши задания:</h3> <br>\n";
foreach ($player_quests['quest_id'] as $key => $value) 
{
	//TODO:: need to delete this magic number; and possible rename model
    if ($player_quests['complete'][$key] == quest_status::COMPLETED) 
        continue;
    $quest = new QuestMgr($value);
    // if ($quest->HaveConversation()){
    // $convMgr = new ConversationMgr(0, $quest->GetConversation());
    // $step = $player->GetCurrentQuestStep();
    // }  data-remote="false"
    printf('<a data-remote="false" data-toggle="modal" data-target="#gameModal" href="includes/do.php?what=view_quest&quest_id=%s" quest-id="%s">%s</a> <br>', $quest->GetQuestId(), $quest->GetQuestId(), $quest->GetTitle());
}
echo '<div class="conversation-response"></div>';
echo '</div>';
printf('<b>Задания, доступные в текущей локации:</b> <br>');

// надо проверить есть ли данный квест у персонажа и выводить если нет...
// это не используется! отладочная инфа
foreach ($current_quests as $key => $value) {
    $questMgr = new QuestMgr($value);
    // $skip=0;
    if ($questMgr->CheckLvlRequire($player)) // && !$player->IsQuestComplete($value)
{
        printf('<a href="#" class="available_quest" data-id="%s"> %s </a><br>', $value, $questMgr->GetTitle());
    }
}

$inventory = new Inventory($player_id);
echo '<div id="radio">';
printf("<hr> \n <b>Радиоволны: </b> <br> \n");
if ($inventory->HaveItemInInventory(3)) // radio in inventory
{
    $radioMgr = new RadioMgr($player->GetLocation());
    
    echo '<select name="radio_select" onChange="radio_select(this)">' . "\n";
    
    $radio_name = $radioMgr->GetName();
    $radio_id = $radioMgr->GetId();
    foreach ($radio_name as $key => $value)
        echo "<option value=" . $radio_id[$key] . ">" . $value . "</option> \n";
    echo "</select> \n";
    echo '<div id="radio_text"></div>' . "<br> \n";
} else
    echo "Для начала купите радиоприемник";
echo "</div> \n";

$vehicleShop = new vehicleShop($player_location);

printf("<hr> <b>Доступно в локации для покупки: </b> <br> \n");
foreach ($vehicleShop->GetVehicles() as $key => $value) {
    $vehicleMgr = new vehicleMgr($value);
    printf("%s <br> \n", $vehicleMgr->GetName());
}

$creaturePlacer = new CreaturePlacer($player_location, $player_sublocation);

$creatureList = $creaturePlacer->getCreatureList();

echo '<div id="npcBox">';
// var_dump($creatureList);
if (! $creatureList)
    echo 'Никого нет';
else {
    foreach ($creatureList['id'] as $key => $value) {
        // echo $value;
        $creature = new Creature($value);
        $conv_id = $creaturePlacer->getConversation($value);
        $auto_id = $creatureList['autoid'][$key];
		//var_dump($conv_id);
        if ($conv_id)
            // echo $conv_id; <a href="test.php" rel="modal:open">example</a>
            $link = '<a data-remote="false" data-toggle="modal" data-target="#gameModal" href="conversation.php?conv_id=' . $conv_id . '&npc=' . $auto_id . '">' . $creature->GetName() . "</a><br> \n";
        else
            $link = $creature->GetName() . "<br> \n";
            // if ($creaturePlacer->checkForConversation($value))
        echo $link;
    }
}
echo '</div>';

// $creature = new Creature(1);

// printf("Вы встретили существо: %s <br> \n И это: %s <br> \n", $creature->GetName(), $creature->GetTypeCaption());
echo "<br><br><hr>";

echo "<h3>Магазин:</h3>";

$shop = new ShopMgr($player->GetLocation());

$items = $shop->GetVendorItemList();
echo '<div class="shop">';
echo "<table border=1>";
echo "<tr>";
foreach ($items as $key => $value)
{
    if ($key > 0 && $key % 4 == 0) //there is table view
        echo "</tr><tr>";
    $item = new Item($value);
    echo '<td><img src="' . $item->GetIcon() . '"' . ' class="test-2" data-dd="' . $item->GetId() . '" ' . 'data-id="' . ++ $key . '"></td>' . "\n";
}
echo "</tr>";
echo "</table>";
echo '</div>';

/*
 * echo "<hr> Инвентарь, другой подход:<br> \n";
 * //var_dump($player->GetInventory());
 * foreach($player->GetInventory()['item_id'] as $key=>$value)
 * echo $value,"<br>";
 */

// debug!
echo '<br><br><br>Время выполнения скрипта: ' . (microtime(true) - $start) . ' сек.';
?>
    <br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>

<h3><a href="autorization.php?action=logout">Выйти</a></h3>
</body>
</html>