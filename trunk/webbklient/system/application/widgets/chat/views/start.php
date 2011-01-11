    
<div id="chat_chatwrapper">

    <div id="chat_leftcolumn">

        <div id="chat_windowwrapper">

            <div id="chat_window">

            <!-- TEST FEED START -->

            <?php if(empty($cashe) == false ) { ?>

                <?php foreach ($cashe->items->item as $item) { ?>

                    <div class="chat_itemwrapper">

                    <p>
                        <span class="user"><?php echo $item->user; ?></span>
                        <span class="datetime"><?php echo $item->datetime; ?></span>
                        <span class="message"><?php echo $item->message; ?></span>
                    </p>

                    </div>

                <?php } ?>

            <?php } ?>

            <!-- TEST FEED END -->

            </div>

        </div>

    </div>

    <div id="chat_rightcolumn">

        <div id="chat_previousdiscussionswrapper">

            <h3>Previous Discussions</h3>

            <form action="" method="post">

                <select id="previouschatdiscussions" name="previouschatdiscussions">

                    <option value="md5">Previous Discussion 1</option>
                    <option value="md5">Previous Discussion 2</option>
                    <option value="md5">Previous Discussion 3</option>

                </select>

            </form>

        </div>

        <div id="chat_projectmemberswrapper">

            <h3>Project Members</h3>

            <ul>
                <li><span>Martin</span></li>
                <li><span>Tobias</span></li>
            </ul>

        </div>

    </div>

    <div id="chat_footer">

        <div id="chat_postitemwrapper">

            <form action="" method="post">

                <input type="text" id="postchatitem" name="postchatitem" value="" />

                <input type="submit" value="Send" id="chat_postitembutton" />

                <div id="chat_clearpostitemwrapper">&nbsp;</div>

            </form>

        </div>

    </div>

</div>