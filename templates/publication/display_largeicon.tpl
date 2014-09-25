
{*---------------------------------------------------------------------------*}

<a href='{gl url="publication/{$publication.publication_id}"}' class="publication-largeicon largeicon" data-type='publication' data-id={$publication.publication_id}>
	<h4 class="title">{$publication.publication_title}</h4>
	<div>
	{if isset($publication.image_filename)}
		<img class="thumbnail" src='{gl url="files/publication/image/thumb/{$publication.image_filename}"}'>
	{else}
		<img class="thumbnail" src='{gl url="static/images/noimage.png"}'/>
	{/if}
	</div>
</a>

{*---------------------------------------------------------------------------*}
