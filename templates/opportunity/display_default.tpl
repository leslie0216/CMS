
{*---------------------------------------------------------------------------*}

{if isset($opportunity)}
<div class="opportunity-default display-default" data-type='opportunity' data-id={$opportunity.opportunity_id}>
	{if isset($user) and $user.is_admin}
		<a class="edit-node button" href='{gl url="admin/opportunity/view"}/{$opportunity.opportunity_id}'>Edit</a>
	{/if}

	<h2><span style="color: gray">{t s=Opportunity m=0}:</span>
		{$opportunity.opportunity_title}
	</h2>

	<p>{$opportunity.opportunity_description}</p>

	<p><span style="color: gray">{t s=Status m=0}:</span>
		{$opportunity.opportunity_status}
	</p>

</div>
{/if}

{*---------------------------------------------------------------------------*}
