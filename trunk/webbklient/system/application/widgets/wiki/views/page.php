
 <?php if (isset($status) && isset($status_message)): ?>
    <div class="<?php echo $status; ?>"><b><?php echo $status_message; ?></b></div>
    <br />
<?php endif; ?>
    
 <div class="wiki_inner_page">
     <h1>
        <?php echo $page->Title; ?>
        <span class="wiki_admin_links">
            [ <a href="javascript:void(0);" onclick="wiki_edit_page();">Edit</a> | <a href="javascript:void(0);" onclick="wiki_delete_page();">Delete</a> | <a href="javascript:void(0);" onclick="wiki_show_history();">History</a>]
        </span>
    </h1>

    <div style="clear:both;height:1px;">&nbsp;</div>

    <?php echo $page->Text; ?>

    <p><br /><br /><small>Tags:
    <?php if (empty($page->Tags) ): ?>
        &nbsp;-
    <? else: ?>
        <? $len = count($page->Tags);
            for($n=0; $n<$len; $n++): ?>
                <a href="javascript:void(0);" onclick="wikiWidget.search('', '<?php echo $page->Tags[$n]->Tag; ?>');"><?php echo ucfirst($page->Tags[$n]->Tag); ?></a>
                <?php if ($n+1<$len): ?>,&nbsp;<?php endif; ?>
            <?php endfor; ?>
    <?php endif; ?>
   </small></p>
    
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
    </h1>
    
    <form name="wiki_edit_form" id="wiki_edit_form">
    
        <table cellpadding="3" cellspacing="3">
            <tr>
                <td valign="top">Title:</td>
                <td><input type="text" name="wiki_edit_title" id="wiki_edit_title" value="<?php echo $form_title; ?>" /></td>
            </tr>
            <tr>
                <td valign="top">Text:</td>
                <td><textarea rows="10" cols="60" id="wiki_edit_text" name="wiki_edit_text"><?php echo $form_text; ?></textarea></td>
            </tr>
            <tr>
                <td valign="top">Order:</td>
                <td>
                    <input type="text" name="wiki_edit_order" id="wiki_edit_order" size="5" value="<?php echo $form_order; ?>" />
                    <br /><small>(Optional, a number specifying page-order)</small>
                </td>
            </tr>            
            <tr>
                <td valign="top"><br />Tags:</td>
                <td>
                    <br /><input type="text" name="wiki_edit_tags" id="wiki_edit_tags" onchange="$('#wiki_edit_tags_update').val('true');" value="<?php echo $form_tags; ?>" />
                    <input type="hidden" id="wiki_edit_tags_update" name="wiki_edit_tags_update" value="false" />
                    <br /><small>(A comma-separated list)</small> 
                </td>
            </tr>
            <tr>
                <td valign="top">Parent:</td>
                <td><?php $current_parent = (empty($form_parent)==false ? $form_parent : ''); ?>
                    <select name="wiki_edit_parent" id="wiki_edit_parent">
                        <option value="">None</option>
                        <?php foreach($select_parents as $row): ?>
                            <option value="<?php echo $row->Wiki_page_id; ?>" <?php if ($current_parent != "" && (int)$current_parent==(int)$row->Wiki_page_id) { echo 'selected="selected"';  } ?> ><?php echo $row->Title; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br /><small>(select page if the new page should have a parent)</small> 
                </td>
            </tr>
            <tr>
                <td></td>
                <td><br/><input type="button" onclick="wikiWidget.post('wiki_edit_form','/pages/update/<?php echo $page->Wiki_page_id; ?>', true);" value="Save" /> <input type="button" onclick="wiki_abort_edit();" value="Cancel" /></td>
            </tr>
        </table>
    </form>
    
</div>


<div class="wiki_inner_page_history">
     <h1>
        History: <?php echo $page->Title; ?>
    </h1>
    
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
            <td><?php if (empty($row->Wiki_page_history_id)==false): ?> &nbsp; <a href="javascript:void(0);" onclick="wikiWidget.load('/pages/get_history/<?php echo $row->Wiki_page_history_id; ?>', true);">view</a><?php endif; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br/>
    <p><a href="javascript:void(0);" onclick="wiki_close_history();"><< Back to page</a></p>
    
</div>

<div id="dialog-confirm" title="Delete this page?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you absolutely sure you want to delete this page and all history? Action is permanent.</p>
</div>


<script type="text/javascript">

     $('#wiki_edit_text').css({'width':'425px', 'height':'200px'});
     $('#wiki_edit_text').resizable(); 
     
     <?php if (isset($show_edit) && $show_edit == true): ?>
        wiki_edit_page();
     <?php endif; ?>
     
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
    
    function wiki_delete_page(){
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height: 200,
            width: 500,
            modal: true,
            zIndex: 3999,
            buttons: {
                "Continue": function() {
                    $( this ).dialog( "close" );
                    wikiWidget.load('/pages/delete/<?php echo $page->Wiki_page_id; ?>/<?php echo $delete_token; ?>');
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }
    
</script>