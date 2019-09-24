<?php
interface wall_type
{
    const WAY = 0;
    const WALL = 1;
    const DOOR = 2;
    const WEIRD = 3;
    const ENEMY = 4;
}

interface map_creatings_result
{
    const MANY_WAYS = 1;
    const SAFE_LINE=2;
}