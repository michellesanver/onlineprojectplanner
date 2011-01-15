<?php

header("Content-type: text/xml");

echo '<result>';

echo '<status>'.$status.'</status>';

if(empty($messages) == false)
{
    foreach($messages as $message)
    {
        echo '<message>'.htmlentities($message).'</message>';
    }
}

if(isset($rooms) && empty($rooms) == false)
{
    echo '<rooms>';

    foreach($rooms as $room)
    {
        echo '<room>';
        echo '<key>'.htmlentities($room["Key"]).'</key>';
        echo '<title>'.htmlentities($room["Title"]).'</title>';
        echo '</room>';
    }

    echo '</rooms>';
}

if(isset($cashe_loaded) && empty($cashe_loaded) == false)
{
    echo '<items>';

    foreach ($cashe_loaded->items->item as $item)
    {
        echo '<item>';
        echo '<user>'.htmlentities($item->user).'</user>';
        echo '<message>'.htmlentities($item->message).'</message>';
        echo '<datetime>'.htmlentities($item->datetime).'</datetime>';
        echo '</item>';
    }

    echo '</items>';
}

echo '</result>';

?>

        