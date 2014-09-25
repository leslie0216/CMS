
{*---------------------------------------------------------------------------*}
{if isset($people)}
<div class="people-default display-default" data-type='people' data-id={$people.people_id}>
	{if isset($user) and $user.is_admin}
		<a class="edit-node button" href='{gl url="admin/people/view"}/{$people.people_id}'>{t s='Edit' m=0}</a>
	{/if}

	<h2>{$people.people_firstname} {$people.people_middlename} {$people.people_lastname}
		<span class="headeraffiliation">{$people.people_affiliation}</span>
	</h2>

	<div class="profileleft">
	{if isset($people.image) and $people.image.count > 0}
		<img src="{$weburl}files/people/image/{$people.image.rows[0].image_filename}" class="imagewrap" align="left" />
	{/if}
	</div>

	<div class="profileright">
		<p>{$people.people_bio}</p>
		<img src="{txt2img text={$people.people_email}}" />

		{if isset($people.image) and $people.image.count > 1}
			{for $i = 1; $i < $people.image.count; $i++}
				<img src="{$weburl}files/people/image/{$people.image.rows[$i].image_filename}" class="imagewrap" align="left" />
			{/for}
		{/if}

		{if isset($people.publication) and $people.publication.count > 0}
			{include "templates/snippets/section_title.tpl" title={t s=Publications m=0}}
			{include "templates/publication/teaser_list.tpl" publication=$people.publication}
			<a class="view-all button" href="{$weburl}people/{$people.people_id}/publication">{t s='View All' m=0}</a>
		{/if}

		{if isset($people.research) and $people.research.count > 0}
			{include "templates/snippets/section_title.tpl" title={t s=Research m=0}}
			{include "templates/research/teaser_list.tpl" research=$people.research}
			<a class="view-all button" href="{$weburl}people/{$people.people_id}/research">{t s='View All' m=0}</a>
		{/if}
	</div>
	<br clear="all" />
</div>
{/if}

{*---------------------------------------------------------------------------*}
