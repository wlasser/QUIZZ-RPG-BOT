<?php
interface player_ingame
{
    const ONLINE = 1;
    const OFFLINE = 0;
}

interface hernya
{
    const THATISHAPPEN = 1;
    const NOPE = 0;
}

interface update_timers
{
    const UPDATE_ONLINE_PLAYER = "+5 min";
    const UPDATE_FATIGUE_PLAYERS = "+7 min";
    
}

interface restore_values
{
    const FATIGUE_RESTORE = "1";
}

interface reward_types
{
    const ITEM =1;
    const PROFESSION = 2;
}

interface variants_type
{
    const ITEM = 1;
    const RUMORS = 2;
    const NPC_FIGHT = 3;
    // what that shit is it?
    // i can use player classes for that? that need as
    const PLAYER_CLASS = 4;
    const MONEY =5;
    
}