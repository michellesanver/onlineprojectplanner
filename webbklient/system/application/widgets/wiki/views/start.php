
     <?php if (isset($status) && isset($status_message)): ?>
        <div class="<?php echo $status; ?>"><b><?php echo $status_message; ?></b></div>
        <br />
    <?php endif; ?>
    
        <h1>Wiki</h1>

        <p>
            <span class="wiki_subtitle">New pages:</span>
            <?php if (empty($new_pages)==false): ?>
            
                <?php foreach($new_pages as $row): ?>
                    <a href="javascript:void(0);" onclick="wikiWidget.load('<?php echo '/pages/get/'.$row->Wiki_page_id; ?>', true);"><?php echo $row->Title; ?></a> <small> by <?php echo $row->Firstname.' '. $row->Lastname; ?> at <?php echo $row->Created; ?></small><br/>
                <?php endforeach; ?>
                
            <?php else: ?>
            
                <em>No new pages</em>
                
            <?php endif; ?>
        </p>
        
        <br/>
        <p>
            <span class="wiki_subtitle">Last updated pages:</span>
            <?php if (empty($last_updated_pages)==false): ?>
            
                <?php foreach($last_updated_pages as $row): ?>
                    <a href="javascript:void(0);" onclick="wikiWidget.load('<?php echo '/pages/get/'.$row->Wiki_page_id; ?>', true);"><?php echo $row->Title; ?></a> <small> by <?php echo $row->Firstname.' '. $row->Lastname; ?> at <?php echo $row->Updated; ?></small><br/>
                <?php endforeach; ?>
                
            <?php else: ?>
                <em>No updated pages</em>
            <?php endif; ?>
        </p>
        
        <p><br /></p>
        <p style="clear:both;"><hr size="1" /></p>
        <br/>
        <h2>News</h2>
        <?php if (empty($changelog)==false ): ?>
        
            <?php foreach ($changelog->news as $row): ?>
                <p>
                    <strong><?php echo $row->title; ?></strong> <small>(<?php echo $row->date.' by '.$row->author; ?>)</small><br />
                    <?php echo $row->text; ?>
                </p>
            <?php endforeach; ?>
            
        <?php else: ?> 
        
            <em>No entries found</em>     
            
        <?php endif; ?>
        
        <br/>
        <br/>
        
    </div>


    