    
<h1>Cashe test...</h1>

<?php if(empty($cashed_data) == false ) { ?>

    <?php foreach ($cashed_data->item as $item) { ?>

        <p>
            User: <?php echo $item->user; ?><br />
            Message: <?php echo $item->message; ?><br />
            Date/Time: <?php echo $item->datetime; ?><br />
        </p>

    <?php } ?>

<?php } else { ?>

    <p>No cashe found...</p>

<?php } ?>
