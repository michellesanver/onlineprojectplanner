
    <h1>Search</h1>
    
    <p>Here you can search in the Wiki by a word or by a tag. Choose your path:</p>
    
    <table cellpadding="3" cellspacing="3">
        <tr>
            <td valign="top">Word:</td>
            <td><input type="text" size="25" id="wiki_search_word" name="wiki_search_word" /></td>
        </tr>
        <tr>
            <td valign="top">Tag:</td>
            <td><input type="text" size="25" id="wiki_search_tag" name="wiki_search_tag" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="button" value="Go!" onclick="do_search();" /></td>
        </tr>
    </table>
    
    <script type="text/javascript">
        function do_search()
        {
            var word = $('#wiki_search_word').val();
            var tag = $('#wiki_search_tag').val();
            
            if (word != "" && tag == "")
            {
                $('#wiki_search_word').val(' ');
                wikiWidget.search(word, '', 'wiki_search_results');    
            }
            else if (word == "" && tag != "")
            {
                $('#wiki_search_tag').val(' ');
                wikiWidget.search('', tag, 'wiki_search_results');                    
            }
        }
    </script>