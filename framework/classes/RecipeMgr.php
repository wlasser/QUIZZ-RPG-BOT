<?php

// need to get item from inventory, when clicked.
// get all possible reagents for that recipe
// check recipe ingredients in inventory (maybe)

//need to get all items from one row, to other row and check all stuff.. how it to do?

class RecipeMgr
{
    use ConfigMgr;

    //public function

    public function MakeRecipe($item)
    {


    }

    public function GetItemsForRecipe($create_item)
    {
        $conn = $this->connect();
        $sth = $conn->prepare(select_statements::SELECT_ALL_RECIPE);
        $execute_params = array($create_item);
        $sth->execute($execute_params);

        while ($row = $sth->fetch(PDO::FETCH_ASSOC))
        {
            //$recipe_create['']
            $recipe_req['first'][] = $row['item_id_1'];
            $recipe_req['second'][] = $row['item_id_2'];
            if ($row['item_id_3'])
                $recipe_req['third'][]= $row['item_id_3'];
        }


    return $recipe_req;
    }


    public function GetListRecipe()
    {
        // all in one????
    }
    public function GetAvailableRecipes($Player)
    {
        $inventory = $Player->GetInventory();
        //$recipes
        foreach ($inventory['item_id'] as $key=>$value)
        {

            //echo $value;
        }
        // need to get all recipe.
        // check if have resources for creation?

    }

    public function GetAvailableRecipe($Player)
    {



    }



}