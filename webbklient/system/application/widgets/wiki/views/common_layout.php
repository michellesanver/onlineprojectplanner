
    <div class="wiki_left_bar">
        <div class="wiki_logo"></div>
        <strong>Wiki:</strong>
        <ul id="mainNavigation">
            <li><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'loadURL', {'url':'/pages/index/<?php echo $instance_id; ?>'});">Home</a></li> 
            <li><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'loadURL', {'url':'/pages/search/<?php echo $instance_id; ?>', 'partial':true});">Search</a></li>
            <li><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'loadURL', {'url':'/pages/create/<?php echo $instance_id; ?>'});">New page</a></li> 
        </ul>
        
        <br/>
        
        <strong>Pages:</strong>
        <?php if (empty($wiki_menu)==false): ?>
        
                <ul id="navigation">
                <?php foreach($wiki_menu as $row): ?>
                    <li>
                        <a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'loadURL', {'url':'<?php echo '/pages/get/'.$row->Wiki_page_id; ?>/<?php echo $instance_id; ?>', 'partial': true});"><?php echo $row->Title; ?></a>
                        <?php if (isset($row->children) && empty($row->children)==false): ?>
                            <ul>
                            <?php foreach($row->children as $row2): ?>
                                <li><a href="javascript:void(0);" onclick="Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'loadURL', {'url':'<?php echo '/pages/get/'.$row2->Wiki_page_id; ?>/<?php echo $instance_id; ?>', 'partial': true});"><?php echo $row2->Title; ?></a></li>
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
