{assign var="a_info" value=$actions_info.$action }
<input type="submit" 
	name="{$a_info.button}" 
	value="{$a_info.button_label|escape}"
{if $disabled}
	disabled="yes"
{/if}
>
