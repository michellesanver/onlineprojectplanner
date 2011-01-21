    
<div id="chat_chatwrapper" style="margin-left: -650px;">

    <div class="chat_chatpage">

        <div class="chat_secondmenu"><span class="chat_turnpage chat_turnright">Back to chat &raquo;</span></div>

        <div id="chat_newdiscussionswrapper">

            <h3>Create A New Discussion</h3>

            <form action="">

                <div class="chat_messagebox"></div>

                <label for="chat_createnewdiscussionstitle">Title</label>
                <input type="text" id="chat_createnewdiscussionstitle" name="chat_createnewdiscussionstitle" value="" maxlength="50" />

                <input type="submit" value="Create" id="chat_createnewdiscussionsbutton" />

                <div class="chat_clearboth"><span class="chat_donotdisplay">&nbsp;</span></div>

            </form>

        </div>

    </div>

    <div class="chat_chatpage">

        <div id="chat_mainleftcolumn">

            <div id="chat_windowcontainer">

                <div id="chat_windowwrapper">

                    <div id="chat_window">

                        &nbsp;

                    </div>

                </div>

            </div

        </div>

        <div id="chat_mainrightcolumn">

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

                <span class="chat_reloadpreviousdiscussions">Reload discussions</span>

            </div>

            <div id="chat_mainmenu">

                <span class="chat_turnpage chat_turnleft">Create a new discussion</span>
                <span class="chat_turnpage chat_turnright">View project members</span>
                <div class="chat_clearboth"><span class="chat_donotdisplay">&nbsp;</span></div>

            </div>

        </div>

        <div id="chat_footer">

            <div id="chat_postitemwrapper">

                <form action="">

                    <div class="chat_messagebox"></div>

                    <input type="text" id="chat_postchatitemmessage" name="chat_postchatitem" value="" maxlength="300" />

                    <div id="chat_postchatitemstatus"></div>

                    <input type="submit" value="Send" id="chat_postitembutton" disabled="disabled" />

                    <div class="chat_clearboth"><span class="chat_donotdisplay">&nbsp;</span></div>

                </form>

            </div>

        </div>

    </div>

    <div class="chat_chatpage">

        <div class="chat_secondmenu"><span class="chat_turnpage chat_turnleft">&laquo; Back to chat</span></div>

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

    </div>

    <div class="chat_clearleft"><span class="chat_donotdisplay">&nbsp;</span></div>

    <script type="text/javascript">

        chatRemote.init();
        chatFunctions.init();

    </script>

</div>