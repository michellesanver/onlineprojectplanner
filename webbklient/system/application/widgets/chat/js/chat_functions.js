chatFunctions = {

    init: function() {

        $('#chat_previousdiscussionswrapper form select').change(function() {

            alert('Här läser vi av cashe-nyckeln och läser in vald cashe i #chat_window samt kopplar mot en kanal...');

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

            alert('Här pushar vi ut ett nytt meddelande i #chat_window samt cashar meddelandet i xml-filen kopplad till diskussionens nyckel...');

            return false;

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

                $(xml).find('room').each(function() {

                    form.find('select').append('<option value="'+$(this).find('key').text()+'">'+$(this).find('title').text()+'</option>');

                });

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

    }

    // ...

}