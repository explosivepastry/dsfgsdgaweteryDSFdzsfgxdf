{extends 'user/layout.tpl'}

{block 'userContent'}
  <div class="ui segment">
    <h3 class="ui header">
        {$FOLLOWING_TOPICS}
        {if count($TOPICS_LIST)}
          <div class="res right floated">
            <a class="ui mini negative button" href="#" data-toggle="modal"
               data-target="#modal-delete">{$UNFOLLOW_ALL}</a>
          </div>
        {/if}
    </h3>
      {if isset($SUCCESS_MESSAGE)}
        <div class="ui success icon message">
          <i class="check icon"></i>
          <div class="content">
            <div class="header">{$SUCCESS}</div>
              {$SUCCESS_MESSAGE}
          </div>
        </div>
      {/if}
    <div class="ui middle aligned relaxed selection list">
        {nocache}
            {if count($TOPICS_LIST)}
              <table class="ui striped selectable table">
                <tbody>
                {foreach from=$TOPICS_LIST item=topic}
                  <tr>
                    <td>
                      <div class="ui stackable middle aligned grid">
                        <div class="row" onclick="window.location.href = '{$topic.last_post_link}'"
                             style="cursor: pointer">
                          <div class="ten wide column">
                              {if $topic.unread}
                                <i class="bell icon"></i>
                                <strong>{$topic.topic_title}</strong>
                              {else}
                                  {$topic.topic_title}
                              {/if}
                          </div>
                          <div class="four wide column">
                            <h5 class="ui image header">
                              <img class="ui mini circular image" src="{$topic.reply_author_avatar}">
                              <div class="content">
                                <a href="{$topic.reply_author_link}" data-toggle="popup"
                                   data-poload="{$USER_INFO_URL}{$topic.reply_author_id}"
                                   style="{$topic.reply_author_style}">{$topic.reply_author_nickname}</a>
                                <div class="sub header" data-toggle="tooltip"
                                     data-content="{$topic.reply_date_full}">{$topic.reply_date}</div>
                              </div>
                            </h5>
                          </div>
                          <div class="two wide column right aligned">
                            <a href="{$topic.unfollow_link}" class="ui mini red button">{$UNFOLLOW_TOPIC}</a>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                {/foreach}
                </tbody>
              </table>
                {$PAGINATION}
            {else}
              <div class="ui info message">
                <div class="content">
                    {$NO_TOPICS}
                </div>
              </div>
            {/if}
        {/nocache}
    </div>
  </div>
{/block}

{block 'modals' append}
  <div class="ui small modal" id="modal-delete">
    <div class="header">
        {$UNFOLLOW_ALL}
    </div>
    <div class="content">
        {$CONFIRM_UNFOLLOW}
    </div>
    <div class="actions">
      <a class="ui negative button">{$NO}</a>
      <form class="ui form" action="" method="post" style="display: inline">
        <input type="hidden" name="token" value="{$TOKEN}">
        <input type="hidden" name="action" value="purge">
        <input type="submit" class="ui green button" value="{$YES}">
      </form>
    </div>
  </div>
{/block}