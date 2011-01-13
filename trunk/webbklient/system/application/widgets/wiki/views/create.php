
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
                <td><textarea rows="10" cols="60" id="wiki_create_text" name="wiki_create_text"><?php echo (isset($form_text) ? $form_text : ''); ?></textarea></td>
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
                <td><br /><input type="button" onclick="wikiWidget.post('wiki_create_form','/pages/create');" value="Save" /> </td>
            </tr>
        </table>
    </form>
    
    
<script type="text/javascript">

    $('#wiki_create_text').css({'width':'325px', 'height':'150px'});
    $('#wiki_create_text').resizable();

</script>