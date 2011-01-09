<?php
 
Class Cashe_model extends Model
{

    /**
    * Used to get cashe
    * -
    */
    function _GetCashe($key)
    {
        $dir = dirname(__FILE__);

        if(file_exists($dir.'/'.$this->_changelog_filename))
        {
            return @simplexml_load_file($dir.'/'.$this->_changelog_filename);
        }
        else
        {
            return false;
        }
    }

    /**
    * Used to read cashe
    * -
    * -
    */

    function ReadCashe()
    {
        //
    }

    /**
    * Used to write cashe
    * -
    * -
    */

    function WriteCashe()
    {
        //
    }

}
  