<?php
interface account_result
{
    const ALL_OK = 1;
    const LOGIN_FAIL = 0;
}

interface player_defines
{
    const ERROR_SAME_NAME = 1;
    const CREATED = 2;

}

interface reg_results
{
    const ALL_OK = 1;
    const REGISTER_FAIL = 0;
}

interface battle_side
{
    const PLAYER = 1;
    const ENEMY = 2;
}

interface player_classes
{
    const NONE = 0;
    const HUNTER = 1;
    const DETECTIVE = 2;
    const READERS = 3; // need to fix caption, i didn't remember how is that named...
    const WITCHHUNTER = 4;
}

interface player_sex
{
    const MALE = 1;
    const FEMALE = 2;
}

interface player_state
{
    const DEAD = -1;
    const ALIVE = 0;
    const PURGATORY = 1;
}

interface player_flags
{
    const NEWLY = 10;
    const LEVELUP = 1;
    const FRESH = 2;
}

interface battle_state
{
    const NOT_COMPLETE = 0;
    const WIN = 1;
    const LOOSE = 2;
    const LEAVE = 3;
}

interface item_use_result
{
    const ITEM_USED = 0;

    const ITEM_EMPTY = 1;
    const INVENTORY_ERROR = 2;

}

interface inventory_result
{
    const ADDED = 1;
    const ERROR_MAX_COUNT = 2;
    const ADDED_NEW = 3;
}



// maybe that can be summed at once?

interface resistance_types
{
    const MEELEE = 1;
    const SALT = 2;
    const NORMAL_BULLETS = 3;
    const SILVER_BULLETS = 4;
    // to be continued

}

interface sickness_types
{
    const MEELEE = 1;
    const SALT = 2;
    const NORMAL_BULLETS = 3;
    const SILVER_BULLETS = 4;
}

interface damage_schools
{
    const NONE = 0;
    const MAGIC = 1;
    const FEAR = 2;
}


interface quest_result
{
    const ALL_OK = 5;
    const ERROR_INVENTORY = 2;
    const ERROR_FATIGUE = 3;
    const TIME_FAIL = 4;
    const QUEST_FAIL = 6;
}

interface item_types
{
    const OTHER = 0;
    const FOOD = 1;
    const MEELEE = 2;
    const GUN = 3;
    //
    const REAGENT_WEAPONMASTER = 5;
    const PROF_MISC = 6;
    const PISTOL_BULLETS = 10;
    //const
    //etc ...
}
interface spec_types
{
    const NORMAL = 1;
    const SILVER = 2;
    const SALT = 3;
    const NAPALM = 4;
    const HOLLY = 5;
    const GRINDSTONE = 6;
    const RADIO_OPERATOR_ITEM = 7;
}

interface food_param_types
{
    const REST_HP = 1;
    const REST_FATIGUE = 2;
}


interface vehicle_type
{
    const SEDAN = 1;
    const JEEP = 2;
    const PICKUP = 3;
}

interface quest_status
{
    const COMPLETED = 1;
    const NOT_COMPLETED = 0;
    const FAILED = -1;
}

interface require_type_quests
{
    const ITEM = 1;
    const QUEST = 2;
    // const EXPLORE = 2;
    const FIND = 3;
    const BURN = 4;
    const RESEARCH = 5;

}
// that is not needed now, but maybe in future... but don't think so!
interface sublocation_quests
{
    const LIBRARY = 1;
    const HOME = 2;
    const GRAVEYARD = 3;
    const MOTEL = 4;
}

interface creature_type
{
    const HUMANOID = 1;
    const GHOST = 2;
    const WEREWOLF = 3;
    const SKINWALKER = 4; // перевертыш
    const WENDIGO = 5;
    const DAEMON = 6;
    const ANGEL = 7;
    const SOULEATER = 8; // to be continue
    const WITCH = 9;
    const VAMPIRE = 10;
    const LEVIATHAN = 11;
    const REAPER = 12;
    //const ARCHANGEL = 13;
    const JAIN = 14; // джины
    const ZOMBIE = 15;
    const POLTERGAIST = 16;

}

interface boss_type
{
    const ALFA = 1;
    const XROAD_DEMON = 2;
    const GODDIES = 3;
    const LEVIATHAN = 4;
    const LUCIFER = 5;
    const DARKNESS = 6;
    const APPO_HORSEMAN = 7;
    const ARCHANGEL = 8;
}

interface profession
{
    // NULL IS NOT SETTED!
    const WEAPONMASTER = 1;
    const OPERATOR = 2;

}

interface event_quest
{
    const MAX_QUEST_SCORE = 100; // that value can be changed
    const START_QUEST = 6; // creatung character quest
    const DEV_GREET = 7; // greeting's from dev'
}

interface queue
{
    const MAX_ACCEPTED_PLAYERS = 2;
}

interface queue_room_status
{
    const IN_PROGRESS = 0;
    const FORMED = 1;
    const ENDED = 2;
}

interface queue_status
{
    const IN_PROGRESS = 0;
    const ACCEPTED = 1;
    const DECLINED = 2;
}
?>