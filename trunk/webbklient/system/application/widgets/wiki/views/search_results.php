
<h1>Search results</h1>

<?php if (empty($results)): ?>
    <p><b>No results for '<?php echo $term; ?>'</b></p>
<?php else: ?>

    <p><b>Found <?php echo count($results); ?> results for '<?php echo $term; ?>':</b></p>

    <p>
    <?php foreach($results as $row): ?>
    <a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'loadURL', {'url':'/pages/get/<?php echo $row->Wiki_page_id.'/'.$instance_id; ?>', 'partial':true});"><?php echo $row->Title; ?></a><br />
    <?php endforeach; ?>
    </p>

    <br />
    
<?php endif; ?>

