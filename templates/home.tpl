
{*---------------------------------------------------------------------------*}


{include "templates/snippets/section_title.tpl" title={t s='Researches' m=0}}

<div class="research-largeicon-list largeicon-list">
{for $i=0; $i < $research.count; $i++}
	{include "templates/research/display_largeicon.tpl" research=$research.rows[$i]}
{/for}
</div>

<a class="view-all" href="{gl url='researches'}">{t s='View All' m=0}<span class="arrows">&nbsp;&gt;&gt;</span></a>

{*---------------------------------------------------------------------------*}

{include "templates/snippets/section_title.tpl" title={t s='Publications' m=0}}

<div class="publication-largeicon-list largeicon-list">
{for $i=0; $i < $publication.count; $i++}
	{include "templates/publication/display_largeicon.tpl" publication=$publication.rows[$i]}
{/for}
</div>

<a class="view-all" href="{gl url='publications'}">{t s='View All' m=0}<span class="arrows">&nbsp;&gt;&gt;</span></a>

{*---------------------------------------------------------------------------*}


{*---------------------------------------------------------------------------*}
