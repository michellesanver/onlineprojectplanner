
<script type="text/javascript">
    function wiki_show_history() {
        $('.wiki_inner_page_edit').hide(); 
        $('.wiki_inner_page_history').show();
        $('.wiki_inner_page').hide()
    }

    function wiki_edit_page() {
        $('.wiki_inner_page_history').hide();
        $('.wiki_inner_page_edit').show();
        $('.wiki_inner_page').hide();
    }
    
    function wiki_abort_edit() {
        $('.wiki_inner_page_edit').hide();    
        $('.wiki_inner_page').show();
    }
    
    function wiki_close_history() {
        $('.wiki_inner_page_history').hide();     
        $('.wiki_inner_page').show();
    }
    
    function wiki_show_history_page(id) {
        wikiWidget.load('/pages/get_history/'+id, true);
    }
    
    function wiki_save_page() {
        
        
    }
</script>

 <div class="wiki_inner_page">
     <h1>
        <?php echo $page->Title; ?>
        <span class="wiki_admin_links">
            [ <a href="javascript:void(0);" onclick="wiki_edit_page();">Edit</a> | <a href="javascript:void(0);" onclick="wiki_show_history();">History</a>]
        </span>
    </h1>

    <div style="clear:both;height:1px;">&nbsp;</div>

    <?php echo $page->Text; ?>

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

<div class="wiki_inner_page_edit">
     <h1>
        Edit: <?php echo $page->Title; ?>
        <span class="wiki_admin_links">
            [ <a href="javascript:void(0);" onclick="wiki_show_history();">History</a> ]
        </span>
    </h1>

    <div style="clear:both;height:1px;">&nbsp;</div>
    
    <form name="wiki_edit_form" id="wiki_edit_form">
    
        <table cellpadding="3" cellspacing="3">
            <tr>
                <td>Title:</td>
                <td><input type="text" name="wiki_edit_title" id="wiki_edit_title" value="<?php echo $page->Title; ?>" /></td>
            </tr>
            <tr>
                <td valign="top">Text:</td>
                <td><textarea rows="10" cols="55" id="wiki_edit_text" name="wiki_edit_text"><?php echo $page->Text; ?></textarea></td>
            </tr>
            <tr>
                <td>Tags:</td>
                <td><input type="text" name="wiki_edit_tags" id="wiki_edit_tags" value="TODO..." /></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="button" onclick="wiki_save_page();" value="Save" /> <input type="button" onclick="wiki_abort_edit();" value="Cancel" /></td>
            </tr>
        </table>
    </form>
    
</div>


<div class="wiki_inner_page_history">
     <h1>
        History: <?php echo $page->Title; ?>
        <span class="wiki_admin_links">
            [ <a href="javascript:void(0);" onclick="wiki_edit_page();">Edit</a> ]
        </span>
    </h1>

    <div style="clear:both;height:1px;">&nbsp;</div>
    
    <br/>
    <table class="wiki_history">
        <tr class="head">
            <td>Title</td>
            <td>Version</td>
            <td>Created</td>
            <td>Updated</td>
            <td>Author</td>
            <td></td>
        </tr>
        <?php foreach($history as $row): ?>
        <tr>
            <td><?php echo $row->Title; ?></td>
            <td><?php echo $row->Version; ?></td>
            <td><?php echo $row->Created; ?></td>
            <td><?php echo (empty($row->Updated)==false ? $row->Updated : 'n/a'); ?></td>
            <td><?php echo $row->Firstname.' '.$row->Lastname; ?></td>
            <td><?php if (empty($row->Wiki_page_history_id)==false): ?> &nbsp; <a href="javascript:void(0);" onclick="wiki_show_history_page(<?php echo $row->Wiki_page_history_id; ?>);">view</a><?php endif; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br/>
    <p><a href="javascript:void(0);" onclick="wiki_close_history();"><< Back to page</a></p>
    
</div>