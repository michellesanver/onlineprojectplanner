
    <?php if (isset($status) && isset($status_message)): ?>
        <div class="<?php echo $status; ?>"><b><?php echo $status_message; ?></b></div>
        <br />
    <?php endif; ?>

    <h1>Create a new wiki-page</h1>

    <form name="wiki_create_form" class="wiki_create_form">
    
        <table cellpadding="3" cellspacing="3">
            <tr>
                <td valign="top">Title:</td>
                <td><input type="text" name="wiki_create_title" id="wiki_create_title" size="30" value="<?php echo (isset($form_title) ? $form_title : ''); ?>" /></td>
            </tr>
            <tr>
                <td valign="top">Text:</td>
                <td><textarea rows="17" cols="60" id="wiki_create_text" name="wiki_create_text"><?php echo (isset($form_text) ? $form_text : ''); ?></textarea></td>
            </tr>
            <tr>
                <td valign="top">Order:</td>
                <td>
                    <input type="text" name="wiki_create_order" id="wiki_create_order" size="5" value="<?php echo (isset($form_order) ? $form_order : ''); ?>" />
                    <br /><small>(Optional, a number specifying page-order)</small>
                </td>
            </tr>
            <tr>
                <td valign="top"><br />Tags:</td>
                <td><br />
                    <input type="text" name="wiki_create_tags" id="wiki_create_tags" size="30" value="<?php echo (isset($form_tags) ? $form_tags : ''); ?>" />
                    <br /><small>(Optional, a comma-separated list)</small>
                </td>
            </tr>
            <tr>
                <td valign="top"><br />Parent:</td>
                <td><br />
                    <select name="wiki_page_parent" id="wiki_page_parent">
                        <option value="">None</option>
                        <?php foreach($select_parents as $row): ?>
                            <option value="<?php echo $row->Wiki_page_id; ?>"><?php echo $row->Title; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br /><small>(Optional, select page if the new page should have a parent)</small> 
                </td>
            </tr>
            <tr>
                <td></td>
                <td><br /><input type="button" onclick="wiki_submit_changes();" value="Save" /> </td>
            </tr>
        </table>
    </form>

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

      $('#wiki_create_text').wysiwyg({
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
    
    function wiki_set_image_tab(num) {
        if (num==undefined || num=="") return;
        num = parseInt(num);
        $('#wiki_image_tab1').hide();
        $('#wiki_image_tab2').hide();
        $('#wiki_image_tab3').hide();
        $('#wiki_image_tab'+num).show();
        
        return false;
    }
    
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
                        
                        $('#wiki_create_text').wysiwyg('insertImage', upload_path+selected_image, attributes);
                        
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
    
    function wiki_submit_changes() {
        document.getElementById('wiki_create_text').value = $('#wiki_create_text').wysiwyg('getContent');   
        wikiWidget.post('wiki_create_form','/pages/create');
    }
</script>