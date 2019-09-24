<?php
// statements

interface select_statements
{
    //achievmgr
    const ACHIEV_LOAD_CRITERIA_PLAYER = "SELECT * FROM `player_achievements_log` WHERE `criteriaType`=? AND `criteriaId`=? AND `player_id`=?";
    
    //`instances
    //  uid, player_id, instance_type, map, start_time, end_time, compeleted
    // what we need to do? we need to form instance for some? what is map? how it work?\
    const INSTANCE_LOAD_DATA = "";
    //queue mgr
    const QUEUE_MGR_SEL_PLAYER_DUNGEON = "SELECT * FROM `queue_info` WHERE `player_id`=? AND `dungeon_id`=?";
    const QUEUE_MGR_SEL_DUNGEON_STATUS = "SELECT * FROM `queue_info` WHERE `dungeon_id`=? AND `status`=?";
    const QUEUE_MGR_SEL_DUNGEON_ROOM = "SELECT * FROM `queue_rooms` WHERE `dungeon_id`=? AND `status`=?";
    const QUEUE_MGR_SEL_DUNGEON_ROOM_MEMBERS = "SELECT * FROM `queue_info` WHERE `dungeon_id`=? AND `status`=? OR `status`=? AND `id`=?"; 
    //const QUEUE_MGR_SEL_FREE_ROOM = "SELECT * FROM `queue_rooms` WHERE `status`=?";
    //account
    const ACC_SEL_EMAIL="SELECT * FROM `accounts` WHERE `email`=?";
    const ACC_SEL_LOGIN = "SELECT * FROM `accounts` WHERE `login`=?";
    const ACC_SEL_PASS = "SELECT * FROM `accounts` WHERE `login`=? AND `password`=?";
    const ACC_SEL_FROM_CHAR_ID = "SELECT * FROM `accounts` WHERE `character`=?";
    
	// player
	const PLAYER_LOAD_QUEST_NPC_FIGHT = "SELECT * FROM `player_quest_fight` WHERE `quest_id`=? AND `player_id`=? AND `quest_variant`=?"; // idk
	const PLAYER_SELECT_NAME = "SELECT * FROM player WHERE name=?";
	const PLAYER_LOAD_DATA = "SELECT * FROM player WHERE id=?";
	const PLAYER_LOAD_STATS = "SELECT * FROM player_stats WHERE player_id=?";
	const PLAYER_LOAD_QUESTS = "SELECT * FROM player_quests where player_id=?";
    const PLAYER_LOAD_PROFESSION = "SELECT * FROM player_profession WHERE player_id=?";
    const PLAYER_CURRENT_Q_SCORES = "SELECT * FROM player_quests WHERE player_id=? AND quest_id=?";
    const PLAYER_GET_RUMOR = "SELECT * FROM players_rumors_collect where rumors_id=? AND player_id=?";
    //const GET_Q_VARIANT_SCORE = "SELECT * FROM quest_variants WHERE quest_id=? and scores=?";
    //game manager
    const LOAD_ALL_ONLINE_PLAYERS = "SELECT * FROM `player` WHERE `online`=1"; //TODO mb that can be some change?
    const LOAD_ALL_PLAYERS = "SELECT * FROM player";
    const LOAD_ALL_PLAYERS_LOC_SUBLOC_ONLINE = "SELECT * FROM `player` WHERE `online`=1 and `location`=? and `sublocation`=?";
    // battle
    const GET_PLAYER_BATTLE_ON_STATE = "SELECT * FROM player_battle WHERE player_id=? AND state=?";
    const LOAD_PLAYER_BATTLE = "SELECT * FROM player_battle WHERE player_id=? AND enemy_id=?";
    // loot
    const LOAD_LOOT_TABLE = "SELECT * FROM loot_template WHERE id=?";

    //rumors
    const GET_RUMOR_TEXT = "SELECT * FROM rumors WHERE id=?";
    // creature
    const LOAD_CREATURE_BY_ID = "SELECT * FROM creature_template WHERE ID=?";
    const GET_CREATURES = "SELECT * FROM creatures WHERE location=? AND sublocation=?";
    //recipe
    const SELECT_ALL_RECIPE = "SELECT * FROM recipe WHERE create_item=? ";
	//quests
	const LOAD_QUEST = "SELECT * FROM quest_template WHERE id=?";
	const GET_LOCATION_QUEST = "SELECT * FROM quest_template WHERE location=?";
    const GET_VARIANTS_TEXT = "SELECT * FROM quest_variants WHERE quest_id=? AND scores=?";// there need to be reviewed
    const CREATURE_QUEST = "SELECT * FROM creature_quest_start WHERE autoid_creature=?";
	// conversation
	const LOAD_CONVERSATION = "SELECT * FROM conversations WHERE id=?";
	const LOAD_CONVERSATION_ID = "SELECT * FROM conversations WHERE conv_id=?";

	// item
	const LOAD_ITEM = "SELECT * FROM item_template WHERE id=?";

	// inventory
	const LOAD_INVENTORY = "SELECT * FROM player_inventory WHERE player_id=?";
	const GET_LAST_POSITION = "SELECT MAX(position) FROM player_inventory WHERE player_id=?";
       //const GET_TOTALID = "SELECT * FROM ";
	//locationMgr
	const GET_LOCATIONS = "SELECT * FROM locations";
	const GET_PLAYER_LOCATION = "SELECT * FROM locations WHERE id=?";
	const GET_COORDS= "SELECT * FROM locations WHERE id=?";

	// sublocations
	const GET_SUBLOCATION = "SELECT * FROM sublocations WHERE location=?";

	//radioMgr
	const LOAD_RADIO = "SELECT * FROM radio WHERE location=? OR global=1"; // that's means - global is true and loaded ewerywhere
	const LOAD_FM_STATION = "SELECT * FROM fm_id WHERE radio_id=?";

	//vehicleMgr
	const LOAD_VEHICLE = "SELECT * FROM vehicles WHERE id=?";
	// vehicleshop
	const LOAD_VEH_SHOP = "SELECT * FROM vehicle_shop WHERE location=?";
        
        // shop vendor
    const LOAD_SHOP = "SELECT * FROM vendor_shop WHERE location = ?";
        
        // garage mgr
    const LOAD_GARAGE = "SELECT * FROM player_garage WHERE player_id=?";

}

interface other_statements
{
	const SET_UTF_CHARSET = "SET NAMES utf8";
}

interface update_statements
{
    // queue 
    const UPDATE_QUEUE_ROOM_STATUS = "UPDATE `queue_rooms` SET `status`=? WHERE `id`=?";
    const UPDATE_QUEUE_PLAYER_STATUS = "UPDATE queue_info SET status=? WHERE player_id=? AND id=?";
    
	// inventory
	const UPDATE_CHARGE = "UPDATE `player_inventory` SET `charges`=? WHERE `player_id`=? AND `total_id`=?";
    const UPDATE_INVENTORY = "UPDATE `player_inventory` SET `item_id`=?, `count`=?, `charges`=? WHERE `player_id`=? AND `total_id`=?";
	//player
	const PLAYER_MODIFY_HP = "UPDATE `player_stats` SET `hp`=? WHERE `player_id`=?";
	const FINISH_QUEST = "UPDATE `player_quests` SET `complete`=?, `time_finished`=?  WHERE `player_id`=? AND `quest_id`=?";
	const PLAYER_UPDATE_STATS = "UPDATE `player_stats` SET `hp`=?, `max_hp`=?, `accuracy`=?, `armor`=?, `intellect`=?, `stamina`=?, `resistance`=?, `agility`=?, `fatigue`=?, `maxFatigue`=? WHERE `player_id`=?";
	const PLAYER_UPDATE_LOCATION_SUBLOCATION = "UPDATE player SET location=?, sublocation=? WHERE id=?";
    const PLAYER_UPDATE_PROFESSION_SKILL ="UPDATE `player_profession` SET `skill`=? WHERE `player_id`=? AND `profession`=?";
    const PLAYER_UPDATE = "UPDATE `player` SET `money`=?, `level`=?, `location`=?, `curr_exp`=?, `sublocation`=?, `state`=?, `flag`=?, `sex`=?, `class`=?, `online`=?, `lastActivityTime`=? WHERE `id`=?";
    const PLAYER_UPDATE_LAST_ACTIVITY = "UPDATE player SET `lastActivityTime`=? WHERE `id`=?";
    const PLAYER_UPDATE_CURR_Q_SCORES = "UPDATE `player_quests` SET `scores`=? WHERE `player_id`=? and `quest_id`=?";
    const PLAYER_UPDATE_QUEST_FIGHT = "UPDATE `player_quest_fight` SET `complete`=? WHERE `player_id`=? AND `npc_id`=? AND `quest_variant`=? AND `quest_id`=?"; // idk now
    //battle
    const BATTLE_UPDATE = "UPDATE `player_battle` SET `who_start`=?,`enemy_hp`=?,`last_damage`=?,`step`=?, `state`=?, `end_time`=? WHERE `battle_id`=?";
    //account
    //accounts` SET `character`='0' WHERE  `autoId`=2;
    const UPDATE_ACCOUNT = "UPDATE `accounts` SET `character`=? WHERE `autoId`=?";
    
    //game
    const UPDATE_ONLINE_PLAYER = "UPDATE `player` SET `online`=? WHERE `id`=?";
    // achievmentMgr
    const UPDATE_ACHIEVE_LOG = "UPDATE `player_achievements_log` SET `actionCount`=`actionCount`+1, `lastTime`=? WHERE `player_id`=?, `criteriaType`=?, `criteriaId`=?";
}       


interface delete_statements
{
	//inventory
	const REMOVE_ITEM = "DELETE FROM `player_inventory` WHERE `player_id`=? AND `total_id`=?";

}

interface insert_statements
{
    //queue! room creating
    const INS_QUEUE_MGR_ROOM = "INSERT INTO `queue_rooms` VALUES ('',?,0)"; // id, dungeon id, status
    const INS_QUEUE_MGR_INFO = "INSERT INTO `queue_info` VALUES(?,?,?,?,?,?)"; // id, player_id, dungeon_id, start_time, end_time, player_status
    // creating player
    // id, name,class, money, level, location, curr_exp, sublocation, state, flag, sex, online, lastActivityTime
    const INS_DEFAULT_PLAYER = "INSERT INTO `player` VALUES('', ?, ? , ?, ?, ?, ?, ?, ?, ? ,?, ?, ?)";
    // player_id, hp, max_hp, accuracy, armor, intellect, stamina, resistance, agility, fatigue, maxFatigue,
    const INS_DEFAULT_PLAYERSTAT = "INSERT INTO `player_stats` VALUES (?, ?, ?, ? ,? ,?, ?, ?, ?, ?, ?)";
    // autoid, login, email, password, ip, date, character
    const INS_REGISTER = "INSERT INTO `accounts` VALUES ('',?,?,?,?,?,?)";
    //battleid, who_start, player_id, enemy_id, enemy_hp, last_damage, step, state, start_time, end_time
    const INIT_BATTLE = "INSERT INTO `player_battle` VALUES ('', ?, ?, ?,?,?,?,?,?,'')";
    // autoid, npc_id, player_id, quest_id, quest_variant, complete
    const INS_PLAYER_QUEST_FIGHT = "INSERT INTO `player_quest_fight` VALUES('',?,?,?,?,?)";
    // achievmentMgr
    // player_id, criteriaType, criteriaId, actionCount, startTime, lastTime
    const INS_ACHIEV_LOG = "INSERT INTO player_achievements_log VALUES('',?,?,?,?,?,?)";
    // total_id, player_id, item_id, count, charges, position
    const ADD_TO_INVENTORY = "INSERT INTO `player_inventory` VALUES ('',?,?,?,?)";
        //quest_id,step, complete, player_id, time_accepted, time_finished
    const ADD_QUEST = "INSERT INTO `player_quests` VALUES(?,?,?,?,?,?)";
    const PLAYER_COLLECT_RUMOR = "INSERT INTO `players_rumors_collect` VALUES ('',?,?)";
        
}