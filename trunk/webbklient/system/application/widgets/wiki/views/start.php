
    <div class="wiki_left_bar">
        <div class="wiki_logo"></div>
        <strong>Wiki:</strong>
        <ul id="mainNavigation">
            <li><a href="javascript:void(0);" onclick="wikiWidget.load('/pages');">Home</a></li> 
            <li><a href="javascript:void(0);" onclick="wikiWidget.load('/pages/create', true);">New page</a></li> 
        </ul>
        
        <br/>
        
        <strong>Pages:</strong>
        <?php if (empty($wiki_menu)==false): ?>
        
                <ul id="navigation">
                <?php foreach($wiki_menu as $row): ?>
                    <li>
                        <a href="javascript:void(0);" onclick="wikiWidget.load('<?php echo '/pages/get/'.$row->Wiki_page_id; ?>', true);"><?php echo $row->Title; ?></a>
                        <?php if (isset($row->children) && empty($row->children)==false): ?>
                            <ul>
                            <?php foreach($row->children as $row2): ?>
                                <li><a href="javascript:void(0);" onclick="wikiWidget.load('<?php echo '/pages/get/'.$row2->Wiki_page_id; ?>', true);"><?php echo $row2->Title; ?></a></li>
                            <?php endforeach; ?>  
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ul>
                
        <?php else: ?>
            <br/><br/><em>None found</em>
        <?php endif; ?>
        
    </div>

    <script type="text/javascript">
        $("#mainNavigation").treeview({
            persist: "location",
            collapsed: true,
            unique: true
        });
        
        $("#navigation").treeview({
            persist: "location",
            collapsed: true,
            unique: true
        }); 
    </script>
    
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


    