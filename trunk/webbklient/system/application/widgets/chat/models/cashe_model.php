<?php
 
Class Cashe_model extends Model
{

    private $_template = "cashe_template";

    /**
    * Used to get cashe
    * -
    * -
    */

    private function _GetCasheURL($key)
    {
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
    * Used to read latest cashe
    * -
    * -
    */

    function ReadLatestCashe($key, $updated)
    {
        $file = $this->_GetCasheURL($key);

        if(file_exists($file) != false)
        {
            $cashe = @simplexml_load_file($file);

            if($cashe != false)
            {
                if(strtotime($updated) < strtotime($cashe->lastupdated[0]))
                {
                    // Create sorted xml from template

                    $template = $this->_GetCasheURL($this->_template);

                    if(file_exists($file) != false)
                    {
                        $latest = @simplexml_load_file($template);
                        $items = $latest->items[0];

                        foreach ($cashe->items->item as $cashed)
                        {
                            if(strtotime($cashed->datetime) > strtotime($updated))
                            {
                                $item = $items->addChild("item", "");
                                $item->addChild("user", $cashed->user);
                                $item->addChild("message", $cashed->message);
                                $item->addChild("datetime", $cashed->datetime);
                            }
                        }
                    }

                    return $latest;
                }
            }
        }

        return false;
    }

    /**
    * Used to write cashe
    * -
    * -
    */

    function WriteCashe($key, $currentUser, $currentId, $currentMessage, $currentDateTime)
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
                $item = $items->addChild("item", "");
                $user = $item->addChild("user", htmlspecialchars($currentUser));
                $user->addAttribute("id", htmlspecialchars($currentId));
                $item->addChild("message", htmlspecialchars($currentMessage));
                $item->addChild("datetime", htmlspecialchars($currentDateTime));

                // Update <lastupdated>

                $cashe->lastupdated[0] = htmlspecialchars(($currentDateTime));

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
                $item = $items->addChild("item", "");
                $user = $item->addChild("user", htmlspecialchars($currentUser));
                $user->addAttribute("id", htmlspecialchars($currentId));
                $item->addChild("message", htmlspecialchars($currentMessage));
                $item->addChild("datetime", htmlspecialchars($currentDateTime));

                // Update <lastupdated>

                $cashe->lastupdated[0] = htmlspecialchars($currentDateTime);

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
  