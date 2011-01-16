<?php

header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';

echo '<result>';

echo '<status>'.$status.'</status>';

if(empty($messages) == false)
{
    foreach($messages as $message)
    {
        echo '<message>'.htmlspecialchars($message).'</message>';
    }
}

if(isset($rooms) && empty($rooms) == false)
{
    echo '<rooms>';

    foreach($rooms as $room)
    {
        echo '<room>';
        echo '<key>'.htmlspecialchars($room["Key"]).'</key>';
        echo '<title>'.htmlspecialchars($room["Title"]).'</title>';
        echo '</room>';
    }

    echo '</rooms>';
}

if(isset($cashe) && empty($cashe) == false)
{
    echo '<items>';

    foreach($cashe->items->item as $item)
    {
        echo '<item>';
        echo '<user>'.htmlspecialchars($item->user).'</user>';
        echo '<message>'.htmlspecialchars($item->message).'</message>';
        echo '<datetime>'.htmlspecialchars($item->datetime).'</datetime>';
        echo '</item>';
    }

    echo '</items>';
}

echo '</result>';

?>

        