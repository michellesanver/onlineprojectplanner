
    <?php if (isset($status) && isset($status_message)): ?>
        <div class="<?php echo $status; ?>" id="wiki-status-message"><b><?php echo $status_message; ?></b><span>[ <a href="javascript:void(0);" onclick="$('#wiki-status-message').remove();return false;">close</a> ]</span><br /></div>
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
    
   
<script type="text/javascript">

      $('#wiki_create_text').wysiwyg({
          controls: {
            justifyFull: { visible : false },
            h1: { visible : false },
            insertImage: { visible : false },
            customImageDialog: {
                visible: true,
                exec: function() { wiki_do_image_dialog(); return false; },
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
                    $( this ).dialog( "destroy" );
                    
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
                        
                        return false;
                    }
                },
                Cancel: function() {
                    $( this ).dialog( "destroy" );
                    
                    return false;
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
    
    function wiki_delete_image(filename, viewId, token) {
        
        // setup parameters for call to widget function
        var delete_parameters = {
            // parameters for post-request on confirmed
            'filename': filename,
            'token': token,
            
            // callback on sucess
            'callback_sucess': 'wiki_delete_image_finished',
            'callback_parameters': { 'remove_id': viewId, 'filename': filename  }
        };
        
        // call widget function
        Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'postDeleteImage', delete_parameters);

        return false;
    }
    
    function wiki_delete_image_finished(parameters) {
        // cleanup on delete success
        $('#'+parameters.remove_id).remove();
        var mySelect = document.getElementById("wiki_wysiwyg_images");
        for(var n=0; n<mySelect.options.length; n++) {
            if (mySelect.options[n].value == parameters.filename) {
                mySelect.options[n] = null;
                break;
            }
        }
    }
    
    function wiki_submit_changes() {
        document.getElementById('wiki_create_text').value = $('#wiki_create_text').wysiwyg('getContent');
        Desktop.callWidgetFunction(<?php echo $instance_id; ?>, 'post', {'form_class':'wiki_create_form', 'url':'/pages/create'});
    }
</script>