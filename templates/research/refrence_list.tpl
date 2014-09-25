
{*---------------------------------------------------------------------------*}

<div class="research-refrence-list">
{for $i=0; $i < $research.count; $i++}
	{include "templates/research/display_teaser.tpl" research=$research.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
