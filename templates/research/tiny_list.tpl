
{*---------------------------------------------------------------------------*}

<div class="research-tiny-list">
{for $i=0; $i < $research.count; $i++}
	{include "templates/research/display_tiny.tpl" research=$research.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
