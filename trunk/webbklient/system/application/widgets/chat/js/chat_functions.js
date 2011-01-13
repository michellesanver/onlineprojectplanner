chatFunctions = {

    init: function() {

        $('#previouschatdiscussions').change(function() {

            alert('Här läser vi av cashe-nyckeln och läser in vald cashe i #chat_window samt kopplar mot en kanal...');

            return false;

        });

        $('#chat_newdiscussionswrapper form').submit(function() {

            alert('Här skapar vi och registrerar en ny nyckel i databasen och uppdaterar #previouschatdiscussions samt raderar eventuell diskussion i #chat_window samt kopplar mot en kanal...');

            return false;

        });

        $('#chat_postitemwrapper form').submit(function() {

            alert('Här pushar vi ut ett nytt meddelande i #chat_window samt cashar meddelandet i xml-filen kopplad till diskussionens nyckel...');

            return false;

        });

    }

    // ...

}