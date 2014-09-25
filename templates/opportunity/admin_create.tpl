
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

{include "templates/snippets/section_title.tpl" title={t s='Add opportunity' m=0}}

<div class="TTWForm-container">

	<form class="TTWForm" method="post" enctype="multipart/form-data" novalidate
		action='{gl url="admin/opportunity/create"}'
	>

		<div id="opportunity_title-container" class="field f_100">
			<label for="opportunity_title">
				Title
			</label>
			<input type="text" name="opportunity_title" id="opportunity_title" required>
		</div>

		<div id="opportunity_status-container" class="field f_100">
			<label for="opportunity_status">
				Status
			</label>

			<select name="opportunity_status" id="opportunity_status" required>
				<option id="opportunity_status-1" value="active">
					{t s='active' m=0}
				</option>
				<option id="opportunity_status-2" value="future">
					{t s='future' m=0}
				</option>
				<option id="opportunity_status-3" value="onhold">
					{t s='onhold' m=0}
				</option>
				<option id="opportunity_status-4" value="past">
					{t s='past' m=0}
				</option>
				<option id="opportunity_status-5" value="unknown">
					{t s='unknown' m=0}
				</option>
			</select>
		</div>

		<div id="opportunity_priority-container" class="field f_100">
			<label for="opportunity_priority">
				Priority
			</label>
			<input type="number" name="opportunity_priority" id="opportunity_priority" min=0>
		</div>

		<div id="opportunity_summary-container" class="field f_100">
			<label for="opportunity_summary">
				Summary
			</label>
			<textarea name="opportunity_summary" id="opportunity_summary"></textarea>
		</div>

		<div id="opportunity_description-container" class="field f_100">
			<label for="opportunity_description">
				Description
			</label>
			<textarea name="opportunity_description" id="opportunity_description"></textarea>
		</div>

		<div id="form-submit" class="field f_100 clearfix submit">
			<input type="submit" value="Save">
		</div>

	</form>
</div>

<br clear="all" />

{*---------------------------------------------------------------------------*}
