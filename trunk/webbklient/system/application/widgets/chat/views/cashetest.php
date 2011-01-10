    
<h1>Cashe test...</h1>

<?php echo '<p>'.$result.'</p>'; ?>

<?php if(empty($cashe) == false ) { ?>

    <?php foreach ($cashe->items->item as $item) { ?>

        <p>
            User: <?php echo $item->user; ?><br />
            Message: <?php echo $item->message; ?><br />
            Date/Time: <?php echo $item->datetime; ?><br />
        </p>

    <?php } ?>

<?php } ?>
