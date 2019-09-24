<?php
interface step_result
{
    const DONE = 1;
    const CANT_MOVE=2;
    const FAIL = 3;
    const COMPLETE = 4;
    const DOOR_OPENED = 5;
}