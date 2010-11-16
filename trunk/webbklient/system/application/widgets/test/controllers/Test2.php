<?php

class Test2 extends Controller {

    function __construct()
    {
        parent::Controller();    
    }
    
    function index()
    {
        echo "inside test-widget secondary controller";
    }
    
}

?>
