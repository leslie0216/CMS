
{*---------------------------------------------------------------------------*}

<h2>{t s='Opportunities' m=0}</h2>
{if isset($opportunities)}
	<div class="opportunity-teaser-list">
	{for $i=0; $i < $opportunities.count; $i++}
		{include "templates/opportunity/display_teaser.tpl" opportunity=$opportunities.rows[$i]}
	{/for}
	</div>
{/if}

{*---------------------------------------------------------------------------*}
