<?php
function aw_my_wines_get_template(){
 ?>
 <h1 class="aw-page-title">My Portfolio > My Wines</h1>
<div id="aw-account-mywines-appraisal-builder" class="aw-appraisal-builder" style="display: none;"> >
 <h1>Appraisal Builder</h1>
 <span class="aw-selected"></span>
 <button class="aw-review-button"> <a href="#appraisal_link">Review </a></button>
</div>
<section id="my-wines-by-section" style="display: none;">
	<div id="my-wines-charts">
		<div id="my-wines-chart-region">
		</div>
		<div id="my-wines-chart-vintage">
		</div>
		<div id="my-wines-chart-producer">
		</div>
		<div id="my-wines-chart-format">
		</div>
	</div>
</section>
<?php include_once(plugin_dir_path( __FILE__ ) . '../page-loading.php'); ?>
 <section id="my-wines-vue-root" style="display:none;">
	 <div id="aw-mywines-filters-section" >
		<div class="aw-type">
			<label>Type</label>
			<input type="hidden" id="aw-mywines-filter-selection-type" value="" />
			<select class="type-filter">
				<option>All</option>
				<option>Retail</option>
				<option>Auction</option>
			</select>
	 </div>
	 <button id="clear-filters" class="aw-button aw-red-button">Clear Filters</button>
	</div>
    <table id="aw-my-wines-table" class="hover stripe" cellspacing="0"  width="100%" >
        <thead>
            <tr class="" >
                <th class="aw-order-type"><span class="nobr">Type</span></th>
                <th class="aw-order-date"><span class="nobr">Date</span></th>
                <th class="aw-order-link"><span class="nobr">PDL/INV#</span></th>
                <th class="aw-wine-qty"><span class="nobr">Qty</span></th>
                <th class="aw-wine-format"><span class="nobr">Fmt</span></th>
                <th class="aw-wine-vintage"><span class="nobr">Vint</span></th>
                <th class="aw-wine-link"><span class="nobr">Wine Name</span></th>
								<th class="aw-wine-region"><span class="nobr">Region</span></th>
								<th class="aw-wine-designation"><span class="nobr">Designation</span></th>
								<th class="aw-wine-producer"><span class="nobr">Producer</span></th>
								<th class="aw-wine-button"><span class="nobr">Appraise</span></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="wine in wines" v-if="wine.wine_region !== 'Accessory'">
							<td class="aw-order-type">{{ wine.order_type }}</td>
							<td class="aw-order-date">{{ wine.order_date }}</td>
							<td class="aw-order-link">{{ wine.order_link }}</td>
							<td class="aw-wine-qty">{{ wine.wine_qty }}</td>
              <td class="aw-wine-format">{{ wine.wine_format }}</td>
              <td class="aw-wine-vintage">{{ wine.wine_vintage }}</td>
              <td class="aw-wine-link">{{ wine.wine_link }}</td>
							<td class="aw-wine-region">{{ wine.wine_region }}</td>
							<td class="aw-wine-designation">{{ wine.wine_designation }}</td>
							<td class="aw-wine-producer">{{ wine.wine_producer }}</td>
							<td class="aw-wine-button">
									<input class="aw-appraisal"
										type="checkbox"
										:order-type="wine.order_type"
										:order-date="wine.order_date"
										:order-link="wine.order_link"
										:wine-qty="wine.wine_qty"
										:wine-fmt="wine.format"
										:wine-name="wine.wineName"
										:wine-vin="wine.vintage"
										:wine-des="wine.designation"
									/>
							</td>
            </tr>
        </tbody>
    </table>
</section>

<section id="my-wines-appraisal-table" style="display:none;">
	<h5 id="appraisal_link">Add to Appraisal</h5>
		 <table id="aw-appraisal-table" class="" cellspacing="0"  width="100%">
				 <thead>
						<tr>
							<th class="aw-order-type"><span class="nobr">Type</span></th>
							<th class="aw-order-date"><span class="nobr">Date</span></th>
							<th class="aw-order-link"><span class="nobr">PDL/INV#</span></th>
							<th class="aw-wine-qty"><span class="nobr">Qty</span></th>
							<th class="aw-wine-format"><span class="nobr">Fmt</span></th>
							<th class="aw-wine-vintage"><span class="nobr">Vint</span></th>
							<th class="aw-wine-link"><span class="nobr">Wine Name</span></th>
							<th class="aw-wine-region"><span class="nobr">Region</span></th>
							<th class="aw-wine-designation"><span class="nobr">Designation</span></th>
							<th class="aw-wine-producer"><span class="nobr">Producer</span></th>
							<th class="aw-wine-remove"><span class="nobr">Remove</span></th>
						</tr>
				 </thead>
				 <tbody></tbody>
			</table>
		<button class="aw-button aw-white-button" id="show-appraisal-lightbox-button"><i class="icon-plus"></i> Appraisal Manual Entry </button>
		<button class="aw-button aw-red-button" id="submit-appraisal-form" action="javascript;" onsubmit="return appraisalFormSubmit(this) ">
			Submit Appraisal
		</button>
</section>
<div id="add-to-appraisal-lightbox" style="display:none;">
	<div class="appraisal-lightbox-content">
		<div class="appraisal-lightbox-header">
			<h1>Appraisal Builder</h1>
			<div class="lightbox-close">
				<button id="appraisal-lightbox-close" ><i class="icon-close"></i></button>
			</div>
		</div>
		<div class="appraisal-lightbox-body">
			<table id="add-to-appraisal-table">
				<thead>
					<tr>
						<th>QTY</th>
						<th>FMT</th>
						<th>VINTAGE</th>
						<th>WINE</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><input type="text" name="appraisal-form-qty" /></td>
						<td><input type="text" name="appraisal-form-fmt" /></td>
						<td><input type="text" name="appraisal-form-vintage" /></td>
						<td><input type="text" name="appraisal-form-wine" /></td>
					</tr>

				</tbody>
			</table>
			<button class="aw-button aw-red-button" id="submit-addto-form" >Add Entry</button>
		</div>
	</div>
</div>


<?php
}
