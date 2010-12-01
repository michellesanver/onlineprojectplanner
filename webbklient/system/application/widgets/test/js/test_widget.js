


function open_test_messages()
{
    if ( confirm('Vilket meddelande vill du se; ok eller error?') )
        show_message('Det här är ett ok-meddelande (behöver lite bättre css)');
    else
        show_errormessage('Det här är ett feeel-meddelande (behöver lite bättre css)');
}