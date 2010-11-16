<?php

class Testuser extends Model {

    function __construct()
    {
        parent::Model();
    }
    
    function editMe()
    {
        echo "inside Testuser model";
    }
}
 
?>
