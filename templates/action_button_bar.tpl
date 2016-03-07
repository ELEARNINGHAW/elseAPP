{foreach item=action key=action_name name=actions_info from=$actions_info }{assign var="visible" value="0"}{if isset($action.button_visible_if) }{assign var="visible" value="1"}
{foreach key=k item=cond from=$action.button_visible_if }{assign var="match" value="0"}
{foreach item=v from=$cond }{if ($k == "state") and ($v == $state) }{assign var="match" value="1"}{/if}
{if ($k == "mode" ) and ($v == $mode ) }{assign var="match" value="1"}{/if}
{if ($k == "item" ) and ($v == $item ) }{assign var="match" value="1"}{/if}
{/foreach}{if $match == 0}{assign var="visible" value="0"}{/if}
{/foreach}
{/if}
{if $visible == 1}
<a class="icon"  href="index.php?collection_id={$collection_id}&amp;item={$item}&amp;action={$action.button}&amp;r={$role}&amp;document_id={$document_id}#{$document_id}"><img  class="icon" title="{$action.button_label}" src="img/svg/{$action.button}.svg" /></a>
{/if}{/foreach} 