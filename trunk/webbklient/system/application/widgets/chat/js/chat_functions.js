chatFunctions = {

    key: null,
    interval: null,

    init: function() {

        $('#chat_previousdiscussionswrapper form select').change(function() {

            var key = $(this).find('option:selected').val();

            var keyRegEx = new RegExp(/^([a-zA-Z0-9]{32})$/);

            // If key is key

            if(keyRegEx.test(key) != false)
            {
                chatFunctions.key = key;

                // Load cashe

                chatFunctions.loadCashe();

                // Enable post item form

                $('#chat_postitembutton').removeAttr('disabled');

                // Set interval for update (Reload cashe)

                if(chatFunctions.interval == null)
                {
                    chatFunctions.interval = setInterval('chatFunctions.reloadCashe()', 5000);
                }
            }
            else
            {
                chatFunctions.key = null;

                // Disable post item form

                $('#chat_postitembutton').attr('disabled', 'disabled');

                // Clear interval for update (Reload cashe)

                if(chatFunctions.interval != null)
                {
                    clearInterval(chatFunctions.interval);
                    chatFunctions.interval = null;
                }
            }

            return false;

        });

        $('#chat_newdiscussionswrapper form').submit(function() {

            var form = $(this);

            $.ajax({

            type: 'POST',
            url: SITE_URL+'/widget/' + chatWidget.widgetName + '/chat/registernewchatroom/',
            data: form.serialize(),
            dataType: 'xml',

            beforeSend: function() {

                 form.find('.chat_messagebox').html('');

            },

            timeout: 5000,

            error: function() {

                form.find('.chat_messagebox').append('<p class="error">Something went wrong!</p>');

            },

            success: function(xml) {

                // Find out status

                var status = $(xml).find('status').text();

                if(status == 'ok')
                {
                    // Append ok-messages

                    $(xml).find('message').each(function() {

                        form.find('.chat_messagebox').append('<p class="ok">'+$(this).text()+'</p>');

                    });

                    // Reload previous discussions

                    if(chatFunctions.reloadDiscussions() != false)
                    {
                        chatRemote.turnRight();
                    }
                }
                else
                {
                    // Append error-messages

                    $(xml).find('message').each(function() {

                        form.find('.chat_messagebox').append('<p class="error">'+$(this).text()+'</p>');

                    });
                }

            }

            });

            return false;

        });

        $('#chat_postitemwrapper form').submit(function() {

            var form = $(this);

            $.ajax({

            type: 'POST',
            url: SITE_URL+'/widget/' + chatWidget.widgetName + '/chat/cashenewitem/',
            data: 'chat_postchatitemkey=' + chatFunctions.key + '&chat_postchatitemmessage=' + form.find('#chat_postchatitemmessage').val(),
            dataType: 'xml',

            beforeSend: function() {

                 form.find('.chat_messagebox').html('');

            },

            timeout: 5000,

            error: function() {

                form.find('.chat_messagebox').append('<p class="error">Something went wrong!</p>');

            },

            success: function(xml) {

                // Find out status

                var status = $(xml).find('status').text();

                if(status == 'ok')
                {
                    // Clear input

                    form.find('#chat_postchatitemmessage').val('');
                }
                else
                {
                    // Append error-messages

                    $(xml).find('message').each(function() {

                        form.find('.chat_messagebox').append('<p class="error">'+$(this).text()+'</p>');

                    });
                }

            }

            });

            return false;

        });

        $('#chat_previousdiscussionswrapper .chat_reloadpreviousdiscussions').bind('click', function() {

            // Reload previous discussions

            chatFunctions.reloadDiscussions();

        });

    },

    reloadDiscussions: function() {

        var form = $('#chat_previousdiscussionswrapper form');

        $.ajax({

        url: SITE_URL+'/widget/' + chatWidget.widgetName + '/chat/reloadchatrooms/',
        dataType: 'xml',

        beforeSend: function() {

             form.find('.chat_messagebox').html('');

        },

        timeout: 5000,

        error: function() {

            form.find('.chat_messagebox').append('<p class="error">Something went wrong!</p>');

        },

        success: function(xml) {

            // Find out status

            var status = $(xml).find('status').text();

            if(status == 'ok')
            {
                // Reload

                form.find('select').html('');

                form.find('select').append('<option value="">-</option>');

                $(xml).find('room').each(function() {

                    form.find('select').append('<option value="'+$(this).find('key').text()+'">'+$(this).find('title').text()+'</option>');

                });

                // Reset key

                chatFunctions.key = null;

                return true;
            }
            else
            {
                // Append error-messages

                $(xml).find('message').each(function() {

                    chatFunctions.form.find('.chat_messagebox').append('<p class="error">'+$(this).text()+'</p>');

                });

                return false;
            }

        }

        });

    },

    loadCashe: function() {

        var target = $('#chat_window');

        $.ajax({

        type: 'POST',
        url: SITE_URL+'/widget/' + chatWidget.widgetName + '/chat/loadcashe/',
        data: 'chat_loadcashekey=' + chatFunctions.key,
        dataType: 'xml',

        beforeSend: function() {

             target.html('');

        },

        timeout: 5000,

        error: function() {

            target.append('<p class="error">Something went wrong!</p>');

        },

        success: function(xml) {

            // Find out status

            var status = $(xml).find('status').text();

            if(status == 'ok')
            {
                // Load

                $(xml).find('item').each(function() {

                    target.append('<div class="chat_itemwrapper"><p><span class="user">'+$(this).find('user').text()+'</span><span class="datetime">'+$(this).find('datetime').text()+'</span><span class="message">'+$(this).find('message').text()+'</span></p></div>');

                });
            }
            else
            {
                // Append error-messages

                $(xml).find('message').each(function() {

                    target.append('<p class="error">'+$(this).text()+'</p>');

                });
            }

        },

        complete: function() {

            target.scrollTop = target.scrollHeight;

        }

        });

        return false;

    },

    reloadCashe: function() {

        var target = $('#chat_window');

        var updated = $('.chat_itemwrapper:last .datetime').html();

        $.ajax({

        type: 'POST',
        url: SITE_URL+'/widget/' + chatWidget.widgetName + '/chat/reloadcashe/',
        data: 'chat_reloadcashekey=' + chatFunctions.key + '&chat_reloadcasheupdated=' + updated,
        dataType: 'xml',

        timeout: 5000,

        success: function(xml) {

            // Find out status

            var status = $(xml).find('status').text();

            if(status == 'ok')
            {
                // Append new items

                $(xml).find('item').each(function() {

                    target.append('<div class="chat_itemwrapper"><p><span class="user">'+$(this).find('user').text()+'</span><span class="datetime">'+$(this).find('datetime').text()+'</span><span class="message">'+$(this).find('message').text()+'</span></p></div>');

                });
            }

        },

        complete: function() {

            target.scrollTop = target.scrollHeight;

        }

        });

        return false;

    }

    // ...

}