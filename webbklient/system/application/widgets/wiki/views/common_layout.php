
    <div class="wiki_left_bar">
        <div class="wiki_logo"></div>
        <strong>Wiki:</strong>
        <ul id="mainNavigation">
            <li><a href="javascript:void(0);" onclick="wikiWidget.load('/pages');">Home</a></li> 
            <li><a href="javascript:void(0);" onclick="wikiWidget.load('/pages/search', true);">Search</a></li>
            <li><a href="javascript:void(0);" onclick="wikiWidget.load('/pages/create');">New page</a></li> 
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
                                <li><a href="javascript:void(0);" onclick="wikiWidget.load('<?php echo '/pages/get/'.$row2->Wiki_page_id; ?>');"><?php echo $row2->Title; ?></a></li>
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
    
    
    <div class="wiki_main_content"><?php echo $content; ?> </div>