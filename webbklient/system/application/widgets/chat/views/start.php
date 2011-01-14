    
<div id="chat_chatwrapper" style="margin-left: -650px;">

    <div class="chat_chatpage">

        <div id="chat_newdiscussionswrapper">

                <form action="" method="post">

                    <input type="submit" value="Create New Discussion" id="chat_createnewdiscussionsbutton" />

                </form>

        </div>

        <span class="chat_turnpage chat_turnright">Back to chat</span>

    </div>

    <div class="chat_chatpage">

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

                <?php if(empty($rooms) == false ) { ?>

                    <form action="" method="post">

                        <select id="previouschatdiscussions" name="previouschatdiscussions">

                        <?php foreach ($rooms as $room) { ?>

                            <option value="<?php echo $room["Key"]; ?>"><?php echo $room["Title"]; ?></option>

                        <?php } ?>

                        </select>

                    </form>

                <?php } ?>

            </div>

            <span class="chat_turnpage chat_turnleft">Create new discussion</span>
            <span class="chat_turnpage chat_turnright">View project members</span>

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

    <div class="chat_chatpage">

        <div id="chat_projectmemberswrapper">

            <h3>Project Members</h3>

            <?php if($members != NULL) { ?>

                <ul>

                <?php foreach($members as $member) { ?>

                    <?php if($member['IsLoggedInUser'] != false) { ?>
                    <li><span><?php echo($member['Username'])." (".$member['Role'].")" ?></span> (that's you)</li>
                    <?php } else { ?>
                    <li><span><?php echo($member['Username'])." (".$member['Role'].")" ?></span></li>
                    <?php } ?>

                <?php } ?>

                </ul>

            <?php } ?>

        </div>

        <span class="chat_turnpage chat_turnleft">Back to chat</span>

    </div>

    <div id="chat_clearchatwrapper">&nbsp;</div>

    <script type="text/javascript">

        chatRemote.init();
        chatFunctions.init();

    </script>

</div>