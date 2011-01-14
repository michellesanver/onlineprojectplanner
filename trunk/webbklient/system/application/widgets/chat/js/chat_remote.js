var chatRemote = {

    currentPosition: 1,
    pageWidth: 650,

    init: function() {

        $('.chat_turnpage').bind('click', function() {

            chatRemote.currentPosition = ($(this).hasClass('chat_turnright')) ? chatRemote.currentPosition + 1 : chatRemote.currentPosition - 1;

            $('#chat_chatwrapper').animate(
                { 'marginLeft' : chatRemote.pageWidth * (-chatRemote.currentPosition) }
            );

        });

    }

    // ...

}