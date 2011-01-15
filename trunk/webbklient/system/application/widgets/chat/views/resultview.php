<?php

header("Content-type: text/xml");

echo '<result>';

echo '<status>'.$status.'</status>';

if(empty($messages) == false)
{
    foreach($messages as $message)
    {
        echo '<message>'.$message.'</message>';
    }
}

if(isset($rooms) && empty($rooms) == false)
{
    echo '<rooms>';

    foreach($rooms as $room)
    {
        echo '<room>';
        echo '<key>'.$room["Key"].'</key>';
        echo '<title>'.$room["Title"].'</title>';
        echo '</room>';
    }

    echo '</rooms>';
}

echo '</result>';

?>

        