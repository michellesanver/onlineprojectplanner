 <div class="wiki_inner_page">
     <h1>
        View history: <?php echo $page->Title; ?> (version <?php echo $page->Version; ?>)
    </h1>

    <div style="clear:both;height:1px;">&nbsp;</div>

    <?php echo $page->Text; ?> 

    <br/>
    <p><a href="javascript:void(0);" onclick="wikiWidget.load('/pages/get/<?php echo $page->Wiki_page_id; ?>', true);"><< Back to current version</a></p> 
    
    <div class="wiki_page_footer">
            <span class="wiki_page_footer_left">
                Page title: <span class="text"><?php echo $page->Title; ?></span><br/>
                Version: <span class="text"><?php echo $page->Version; ?></span>
            </span>
            <span class="wiki_page_footer_right">    
                <?php if (empty($page->Updated)==false): ?>
                    Updated: <span class="text"><?php echo (empty($page->Updated)==false ? $page->Updated : 'n/a'); ?></span>
                <?php else: ?>
                    Created: <span class="text"><?php echo $page->Created; ?></span>
                <?php endif; ?>
                <br/>
                Author: <span class="text"><? echo $page->Firstname.' '.$page->Lastname; ?></span>
            </span>
    </div>
</div>