
{*---------------------------------------------------------------------------*}

<div class="research-largeicon-list">
{for $i=0; $i < $research.count; $i++}
	{include "templates/research/display_largeicon.tpl" research=$research.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
