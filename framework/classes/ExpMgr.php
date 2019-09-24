<?php
class ExpMgr
{
	public static function GetNextLvlExpValue($lvl)/* :int */ // why it's static?
	{
                //HARDCODE. can be moved... or not!
		switch ($lvl){
			case 1:
                return 100;
            break;
			case 2:
                return 300;
            break;
			case 3:
                return 700;
            break;
			case 4:
			    return 1400;
			break;
			case 5:
                return 3000;
            break;
		}

	}
}