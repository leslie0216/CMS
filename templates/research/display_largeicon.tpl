
{*---------------------------------------------------------------------------*}

<a href='{gl url="research/{$research.research_id}"}' class="research-largeicon largeicon" data-type='research' data-id={$research.research_id}>
	<h4 class="title">{$research.research_title}</h4>
	<div>
	{if isset($research.image_filename)}
		<img class="thumbnail" src='{gl url="files/research/image/thumb/{$research.image_filename}"}'>
	{else}
		<img class="thumbnail" src='{gl url="static/images/noimage.png"}'/>
	{/if}
	</div>
</a>

{*---------------------------------------------------------------------------*}
