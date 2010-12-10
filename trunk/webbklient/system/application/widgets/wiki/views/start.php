
    <div class="wiki_left_bar">
        <div class="wiki_logo"></div>
        <strong>Pages:</strong>
        <?php if (empty($wiki_menu)==false): ?>
        
                <ul>
                <li><a href="javascript:void(0);" onclick="wikiWidget.load('/pages');">Home</a></li>
                <?php foreach($wiki_menu as $row): ?>
                    <li><a href="javascript:void(0);" onclick="wikiWidget.load('<?php echo '/pages/get/'.$row->Wiki_page_id; ?>', true);"><?php echo $row->Title; ?></a></li>
                <?php endforeach; ?>
                <li><a href="javascript:void(0);" onclick="wikiWidget.load('/pages/get/99', true);">errortest</a></li>
                </ul>
                
        <?php else: ?>
            <br/><br/><em>No pages found</em>
        <?php endif; ?>
        
    </div>

    <div class="wiki_main_content">
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
        <p>Wiget changelog....</p>
        
    </div>

