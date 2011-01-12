<?php
 
Class Cashe_model extends Model
{

    private $_template = "cashe_template";

    /**
    * Used to get cashe
    * -
    * -
    */

    function _GetCasheURL($key)
    {
        // TODO: Move folder down to the protected system/cashe folder

        $dir = dirname(__FILE__);

        return $dir.'/../cashe/'.$key.'.xml';
    }

    /**
    * Used to read cashe
    * -
    * -
    */

    function ReadCashe($key)
    {
        $file = $this->_GetCasheURL($key);

        if(file_exists($file) != false)
        {
            return @simplexml_load_file($file);
        }

        return false;
    }

    /**
    * Used to write cashe
    * -
    * -
    */

    function WriteCashe($key, $currentUser, $currentId, $currentMessage, $currentDatetime)
    {
        $file = $this->_GetCasheURL($key);
        $cashe = NULL;

        if(file_exists($file) != false)
        {
            $cashe = @simplexml_load_file($file);

            if($cashe != false)
            {
                // Cashe item

                $items = $cashe->items[0];
                $item = $items->addChild("item", '');
                $user = $item->addChild("user", $currentUser);
                $user->addAttribute('id', $currentId);
                $item->addChild("message", $currentMessage);
                $item->addChild("datetime", $currentDatetime);

                // Update <lastupdated>

                $cashe->lastupdated[0] = $currentDatetime;

                // Save

                if($cashe->saveXML($file) != false)
                {
                    return true;
                }
            }
        }
        else
        {
            $template = $this->_GetCasheURL($this->_template);
            $cashe = @simplexml_load_file($template);

            if($cashe != false)
            {
                // Cashe item

                $items = $cashe->items[0];
                $item = $items->addChild("item", '');
                $user = $item->addChild("user", $currentUser);
                $user->addAttribute('id', $currentId);
                $item->addChild("message", $currentMessage);
                $item->addChild("datetime", $currentDatetime);

                // Update <lastupdated>

                $cashe->lastupdated[0] = $currentDatetime;

                // Save

                if($cashe->saveXML($file) != false)
                {
                    return true;
                }
            }
        }

        return false;
    }

}
  