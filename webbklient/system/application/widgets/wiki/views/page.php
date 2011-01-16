
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
    <?php else: ?>
        <?php $len = count($page->Tags);
            for($n=0; $n<$len; $n++): ?>
                <a href="javascript:void(0);" onclick="wiki_search_tag('<?php echo $page->Tags[$n]->Tag; ?>');"><?php echo ucfirst($page->Tags[$n]->Tag); ?></a>
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
    
    <form name="wiki_edit_form" class="wiki_edit_form">
    
        <table cellpadding="3" cellspacing="3">
            <tr>
                <td valign="top">Title:</td>
                <td><input type="text" name="wiki_edit_title" id="wiki_edit_title" value="<?php echo $form_title; ?>" /></td>
            </tr>
            <tr>
                <td valign="top">Text:</td>
                <td><textarea rows="17" cols="60" id="wiki_edit_text" name="wiki_edit_text"><?php echo $form_text; ?></textarea></td>
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
                <td><br/><input type="button" onclick="wiki_submit_changes();" value="Save" /> <input type="button" onclick="wiki_abort_edit();" value="Cancel" /></td>
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

<div id="wiki-dialog-confirm" title="Delete this page?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you absolutely sure you want to delete this page and all history? Action is permanent.</p>
</div>

<div id="wiki-dialog-insertimage" title="Insert image" style="display:none;">
    <a href="javascript:void(0);" onclick="wiki_set_image_tab(1);">Select image</a> | <a href="javascript:void(0);" onclick="wiki_set_image_tab(2);">View images</a> | <a href="javascript:void(0);" onclick="wiki_set_image_tab(3);">Upload image</a>
    <p><hr /></p>
    <table cellpadding="3" cellspacing="3" id="wiki_image_tab1">
        <tr>
            <td>Select image:</td>
            <td>
            <select id="wiki_wysiwyg_images">
                <?php if ( empty($wysiwyg_images) == false ) : ?>
                    <option value=""> - - - - - </option> 
                    <?php foreach ($wysiwyg_images as $row): ?>
                        <option value="<?php echo $row; ?>"> <?php echo $row; ?> </option> 
                    <?php endforeach; ?>
                    
                <?php else: ?>
                    <option value=""> No images </option>
                <?php endif; ?>
            </select>
            </td>
        </tr>
        <tr>
            <td>Width:</td>
            <td><input type="text" id="wiki_wysiwyg_width" size="5" value="" /></td>
        </tr>
        <tr>
            <td>Height:</td>
            <td><input type="text" id="wiki_wysiwyg_height" size="5" value="" /></td>
        </tr>
        <tr>
            <td>Float:</td>
            <td><select id="wiki_wysiwyg_float"><option value="">none</option><option value="left">left</option><option value="right">right</option></select></td>
        </tr>
    </table>
    <div id="wiki_image_tab2" style="display:none;">
        <?php if ( empty($wysiwyg_images) == false ) : ?> 
            
            <?php $num=1; foreach ($wysiwyg_images as $row): ?>
                <p id="wiki_image_view_<?php echo $num; ?>"><image src="<?php echo $wysiwyg_upload_path.'/'.$row; ?>" /><br/><small><?php echo $row; ?> | <a href="javascript:void(0);" style="color:red;" onclick="wiki_delete_image('<?php echo $row; ?>', 'wiki_image_view_<?php echo $num++; ?>', '<?php echo $this->Wiki->getImageMD5($row, $instance_id); ?>');">delete image</a></small></p>
            <?php endforeach; ?>
            <div id="wiki_new_uploaded_images"></div>    
        <?php else: ?>
        
                <p>No images</P>
                
        <?php endif; ?>
        
    </div>
    <iframe id="wiki_image_tab3" style="display:none;width:425px;height:225px;border:0;" src="<?php echo site_url().'/widget/wiki/pages/upload/'.$instance_id ;?>"></iframe>
</div>

<div id="wiki-dialog-confirm2" title="Delete this image?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you absolutely sure you want to delete the image '<span id="wiki_delete_image_filename"></span>'? Action is permanent.</p>
</div>

<div id="wiki-dialog-processing" title="Please wait" style="display:none;">
    <p>Please wait while removing image...</p>
</div>

<div id="wiki-dialog-processing-message" title="Remove image" style="display:none;"><p>Image was removed.</p></div><div id="wiki-dialog-processing-message2" title="Error: remove image" style="display:none;"><p>Unable to remove image.</p></div>

<script type="text/javascript">

      $('#wiki_edit_text').wysiwyg({
          controls: {
            justifyFull: { visible : false },
            h1: { visible : false },
            insertImage: { visible : false },
            customImageDialog: {
                visible: true,
                exec: wiki_do_image_dialog,
                className: 'insertImage'
            }
          },
          resizeOptions: {}
      });          
       
     <?php if (isset($show_edit) && $show_edit == true): ?>
        wiki_edit_page();
     <?php endif; ?>
    
    function wiki_do_image_dialog() {
        $( "#wiki-dialog-insertimage").dialog({
            resizable: true,
            height: 400,
            width: 500,
            modal: true,
            zIndex: 3999,
            buttons: {
                "Ok": function() {
                    $( this ).dialog( "close" );
                    
                    var upload_path = '<?php echo $wysiwyg_upload_path; ?>/';
                    var selected_image = document.getElementById('wiki_wysiwyg_images').value;
                    if (selected_image != "") {
                        
                        var w = document.getElementById('wiki_wysiwyg_width').value;
                        var h = document.getElementById('wiki_wysiwyg_height').value;
                        var f = document.getElementById('wiki_wysiwyg_float').value;
                        
                        var attributes = null;
                        
                        if (w!= "" && h != "") {
                            attributes = { width: w, height: h };    
                        } else if (w!= "" && h != "" && f != "") {
                            attributes = { width: w, height: h, style: 'float: '+f };    
                        } else if (f != "") {
                            attributes = { style: 'float: '+f };    
                        }
                        
                        $('#wiki_edit_text').wysiwyg('insertImage', upload_path+selected_image, attributes);
                        
                        document.getElementById('wiki_wysiwyg_width').value = "";
                        document.getElementById('wiki_wysiwyg_height').value = "";
                        document.getElementById('wiki_wysiwyg_float').value = "";
                        document.getElementById('wiki_wysiwyg_images').value = "";
                    }
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
        
        return false;
    }
     
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
   
    function wiki_search_tag(tag) {
        var resultDivClass = wikiWidget.pageContentDivClass;
        wikiWidget.search('', tag, resultDivClass, true); 
    }
    
    function wiki_delete_page(){
        $( "#wiki-dialog-confirm" ).dialog({
            resizable: false,
            height: 200,
            width: 500,
            modal: true,
            zIndex: 3999,
            buttons: {
                "Continue": function() {
                    $( this ).dialog( "close" );
                    wikiWidget.load('/pages/delete/<?php echo $page->Wiki_page_id; ?>/<?php echo $delete_token; ?>/<?php echo $instance_id; ?>');
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }
    
    function wiki_submit_changes() {
        document.getElementById('wiki_edit_text').value = $('#wiki_edit_text').wysiwyg('getContent');   
        wikiWidget.post('wiki_edit_form','/pages/update/<?php echo $page->Wiki_page_id; ?>', true);
    }
    
    function wiki_set_image_tab(num) {
        if (num==undefined || num=="") return;
        num = parseInt(num);
        $('#wiki_image_tab1').hide();
        $('#wiki_image_tab2').hide();
        $('#wiki_image_tab3').hide();
        $('#wiki_image_tab'+num).show();
        
        return false;
    }
    
    function wiki_add_uploaded_image(filename) {
        if (filename != undefined && filename != "") {
            var imageHTML = "<p><image src=\"<?php echo $wysiwyg_upload_path.'/'; ?>" + filename + "\" /><br/><small>" + filename + "</small></p>";    
            $('#wiki_new_uploaded_images').append(imageHTML);
            
            var mySelect = document.getElementById("wiki_wysiwyg_images");
            try {
                mySelect.add(new Option(filename, filename), null);
            } catch(e){ // IE
                mySelect.add(new Option(filename, filename)); 
            }
        }
    }
    
    delete_image_hide = "";
    delete_image_filename = "";
    
    function wiki_delete_image(filename, viewId, token) {
        delete_image_hide = viewId;
        delete_image_filename = filename;
        
        $('#wiki_delete_image_filename').text(filename);
        $("#wiki-dialog-confirm2").dialog({
            resizable: false,
            height: 200,
            width: 500,
            modal: true,
            zIndex: 3999, 
            buttons: {
                "Continue": function() {
                    $( this ).dialog( "close" );
                    
                    $('#wiki-dialog-processing').dialog({
                        resizable: false,     
                        height: 175,
                        width: 350,
                        modal: true,
                        zIndex: 3999, 
                        buttons: { }
                    });
                    
                    var url = SITE_URL + '/widget/wiki/pages/delete_image';
					var postdata = {'filename': filename, 'token': token, 'instance_id': '<?php echo $instance_id; ?>'};
					
                    ajaxRequests.post(postdata, url,'delete_ok_callback', 'delete_error_callback', true);
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }
    
    function delete_error_callback(data) {
        $('#wiki-dialog-processing').dialog( "close" );    
       Desktop.show_errormessage( unescape(data) );
    }
    
    function delete_ok_callback(data) {
        $('#wiki-dialog-processing').dialog( "close" );        
        
        var msgId = "wiki-dialog-processing-message";
        if (data.indexOf('Error') != -1) {
            msgId = 'wiki-dialog-processing-message2';    
        } else {
            $('#'+delete_image_hide).hide();
        }
        
        $('#'+msgId).dialog({
            resizable: false,     
            height: 175,
            width: 350,
            modal: true,
            zIndex: 3999, 
            buttons: {
                "Ok": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }
</script>
