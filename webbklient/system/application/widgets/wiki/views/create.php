
     <h1>
        Create a new wiki-page
    </h1>

    <div style="clear:both;height:1px;">&nbsp;</div>

    <form name="wiki_create_form" id="wiki_create_form">
    
        <table cellpadding="3" cellspacing="3">
            <tr>
                <td valign="top">Title:</td>
                <td><input type="text" name="wiki_create_title" id="wiki_create_title" value="" /></td>
            </tr>
            <tr>
                <td valign="top">Text:</td>
                <td><textarea rows="10" cols="60" id="wiki_create_text" name="wiki_create_text"></textarea></td>
            </tr>
            <tr>
                <td valign="top">Tags:</td>
                <td>
                    <input type="text" name="wiki_create_tags" id="wiki_create_tags" value="" />
                    <br /><small>(A comma-separated list)</small>
                </td>
            </tr>
            <tr>
                <td valign="top">Parent:</td>
                <td>
                    <select name="" id="">
                        <option value="">None</option>
                        <?php foreach($select_parents as $row): ?>
                            <option value="<?php echo $row->Wiki_page_id; ?>"><?php echo $row->Title; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <br /><small>(select page if the new page should have a parent)</small> 
                </td>
            </tr>
            <tr>
                <td></td>
                <td><br /><input type="button" onclick="wiki_save_new_page();" value="Save" /> </td>
            </tr>
        </table>
    </form>
    
    
<script type="text/javascript">

    $('#wiki_create_text').css({'width':'425px', 'height':'200px'});
    $('#wiki_create_text').resizable();

    function wiki_save_new_page() {
        
        
    }
</script>