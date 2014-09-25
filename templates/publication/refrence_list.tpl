
{*---------------------------------------------------------------------------*}

<div class="publication-refrence-list">
{for $i=0; $i < $publication.count; $i++}
	{include "templates/publication/display_teaser.tpl" publication=$publication.rows[$i]}
{/for}
</div>

{*---------------------------------------------------------------------------*}
