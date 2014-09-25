
{*---------------------------------------------------------------------------*}

<img id='remove-refrence-button' src='{gl url="static/images/recyclebin.png"}'/>

{*---------------------------------------------------------------------------*}

<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="admin/opportunity/create"}'>
	<div id="opportunity-container" class="field f_100">
		<label for="opportunity">
			Find opportunity
		</label>
		<input class="find" autocomplete=off type="text" name="opportunity" placeholder="search"/>
	</div>
</form>

{*---------------------------------------------------------------------------*}

{if isset($opportunity)}
{include "templates/snippets/section_title.tpl" title={t s='Edit opportunity' m=0}}

<div class="TTWForm-container"
			data-type='opportunity'
			data-id='{$opportunity.opportunity_id}'
>

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
			action='{gl url="admin/opportunity/edit"}/{$opportunity.opportunity_id}'
	>

		<a class="view" href='{gl url="opportunity"}/{$opportunity.opportunity_id}'>view</a>
		<a class="remove" href='{gl url="admin/opportunity/remove"}/{$opportunity.opportunity_id}'>remove</a>

		<div id="opportunity_title-container" class="field f_100">
			<label for="opportunity_title">
				Title
			</label>
			<input type="text" name="opportunity_title" id="opportunity_title" required
				value="{$opportunity.opportunity_title}">
		</div>

		<div id="opportunity_status-container" class="field f_100">
			<label for="opportunity_status">
				Status
			</label>

			<select name="opportunity_status" id="opportunity_status" required>
				<option id="opportunity_status-1" value="active"
				{if "active" == $opportunity.opportunity_status}
					selected
				{/if}
				>
					{t s='active' m=0}
				</option>
				<option id="opportunity_status-2" value="future"
				{if "future" == $opportunity.opportunity_status}
					selected
				{/if}
				>
					{t s='future' m=0}
				</option>
				<option id="opportunity_status-3" value="onhold"
				{if "onhold" == $opportunity.opportunity_status}
					selected
				{/if}
				>
					{t s='onhold' m=0}
				</option>
				<option id="opportunity_status-4" value="past"
				{if "past" == $opportunity.opportunity_status}
					selected
				{/if}
				>
					{t s='past' m=0}
				</option>
				<option id="opportunity_status-5" value="unknown"
				{if "unknown" == $opportunity.opportunity_status}
					selected
				{/if}
				>
					{t s='unknown' m=0}
				</option>
			</select>
		</div>

		<div id="opportunity_priority-container" class="field f_100">
			<label for="opportunity_priority">
				Priority
			</label>
			<input type="number" name="opportunity_priority" id="opportunity_priority"
				value="{$opportunity.opportunity_priority}" min=0>
		</div>

		<div id="opportunity_summary-container" class="field f_100">
			<label for="opportunity_summary">
				Summary
			</label>
			<textarea name="opportunity_summary" id="opportunity_summary">{$opportunity.opportunity_summary}</textarea>
		</div>

		<div id="opportunity_description-container" class="field f_100">
			<label for="opportunity_description">
				Description
			</label>
			<textarea name="opportunity_description" id="opportunity_description">{$opportunity.opportunity_description}</textarea>
		</div>

		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Save">
		</div>

	</form>
</div>

{/if}

<br clear="all" />

{*---------------------------------------------------------------------------*}
