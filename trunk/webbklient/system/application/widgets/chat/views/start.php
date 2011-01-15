    
<div id="chat_chatwrapper" style="margin-left: -650px;">

    <div class="chat_chatpage">

        <div id="chat_newdiscussionswrapper">

                <form action="">

                    <div class="chat_messagebox"></div>

                    <label for="chat_createnewdiscussionstitle">Title</label>
                    <input type="text" id="chat_createnewdiscussionstitle" name="chat_createnewdiscussionstitle" value="" maxlength="200" />

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

                

                </div>

            </div>

        </div>

        <div id="chat_rightcolumn">

            <div id="chat_previousdiscussionswrapper">

                <h3>Previous Discussions</h3>

                <form action="">

                    <div class="chat_messagebox"></div>

                    <select id="chat_previouschatdiscussions" name="chat_previouschatdiscussions">

                    <option value="">-</option>

                    <?php if(empty($rooms) == false) { ?>

                        <?php foreach ($rooms as $room) { ?>

                            <option value="<?php echo $room["Key"]; ?>"><?php echo $room["Title"]; ?></option>

                        <?php } ?>

                    <?php } ?>

                    </select>

                </form>

            </div>

            <span class="chat_turnpage chat_turnleft">Create new discussion</span>
            <span class="chat_turnpage chat_turnright">View project members</span>

        </div>

        <div id="chat_footer">

            <div id="chat_postitemwrapper">

                <form action="">

                    <div class="chat_messagebox"></div>

                    <input type="text" id="chat_postchatitemmessage" name="chat_postchatitem" value="" maxlength="300" />

                    <input type="submit" value="Send" id="chat_postitembutton" disabled="disabled" />

                    <div id="chat_clearpostitemwrapper">&nbsp;</div>

                </form>

            </div>

        </div>

    </div>

    <div class="chat_chatpage">

        <div id="chat_projectmemberswrapper">

            <h3>Project Members</h3>

            <?php if(empty($members) == false) { ?>

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