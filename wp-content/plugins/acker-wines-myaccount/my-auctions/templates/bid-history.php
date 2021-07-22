<?php
function aw_account_myauctions_bid_history_get_template(){
?>
<h1 class="aw-page-title">My Auctions > Bid History</h1>

<div id="aw-account-bidhistory-appraisal-builder" class="aw-appraisal-builder" style="display:none;">
	<h1>Appraisal Builder</h1>
	<span class="aw-selected"></span>
	<button class="aw-review-button"> Review </button>
</div>

<div id="aw-account-bidhistory-appraisal" style="display:none;">
	<div class="aw-appraisal-table">
		<h5>MANUAL WINE ENTRY</h5>
		<table id="aw-addto-appraisal-table">
			<tbody>
			<tr class="" >
				<td class=" aw-order-qty"><input name="quantity" placeholder="QTY" /></td>
				<td class=" aw-order-fmt"><input name="format" placeholder="FMT" /></td>
				<td class=" aw-order-vin"><input name="vintage" placeholder="VIN" /></td>
				<td class=" aw-order-name"><input name="wine-name" placeholder="WINE" /></td>
				<td class=" aw-order-des"><input name="des"  placeholder="DES" /></td>
				<td class=" aw-order-prod"><input name="prod" placeholder="PROD" /></td>
				<td class=" aw-order-actions"><button class="aw-addto-appraisal-table-submit aw-button aw-red-button">Add Wine</button></td>
			</tr>
		</tbody>
		</table>
		<h5>APPRAISAL</h5>
		<table id="aw-bidhistory-appraisal-table">
			<thead>
					<tr class="" style="width: 100%;" >
						<th class=" aw-order-lot"><span class="nobr">LOT</span></th>
						<th class=" aw-order-qty"><span class="nobr">QTY</span></th>
						<th class=" aw-order-fmt"><span class="nobr">FMT</span></th>
						<th class=" aw-order-vin"><span class="nobr">VIN</span></th>
						<th class=" aw-order-name"><span class="nobr">WINE</span></th>
						<th class=" aw-order-des"><span class="nobr">des</span></th>
						<th class=" aw-order-prod"><span class="nobr">Prod</span></th>
						<th class=" aw-order-bidamt"><span class="nobr">Bid AMT</span></th>
						<th class=" aw-order-actions"><span class="nobr">Remove</span></th>
					</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<div class="aw-button-container"><button id="aw-bidhistory-appraisal-submit-button" class="aw-button aw-red-button">Submit Appraisal</button></div>
	</div>
</div>

<div class="aw-bidhistory-filters" style="display:none;">
	<div class="aw-result">
		<label>Result</label>
		<input type="hidden" id="aw-bidhistory-filter-selection-result" value=""  />
		<select id="aw-myauctions-bidhistory-result-select">
			<option aw-bid-result="all" class="aw-bidhistory-result-option">All Results</option>
			<option aw-bid-result="win" class="aw-bidhistory-result-option">Win</option>
			<option aw-bid-result="lost" class="aw-bidhistory-result-option">Lost</option>
			<option aw-bid-result="tie" class="aw-bidhistory-result-option">Tie/Lost</option>
		</select>
	</div>
	<div class="aw-dates">
		<form id="bidhistory-date-form" method="post" action="bid-history.php">
			<label>From</label>
				<input type="text" id="bidhistory-filter-dates-from" value="<?php if(isset($_POST["startDate"])){ echo $_POST["startDate"]; }?>" />
				<input type="hidden" id="bidhistory-fromDate-value" name="startDate" value="<?php if(isset($_POST["startDate"])){ echo $_POST["startDate"]; } ?>" />
			<label>To</label>
				<input type="text" id="bidhistory-filter-dates-to" value="<?php if(isset($_POST["endDate"])){ echo $_POST["endDate"]; } ?>"  />
				<input type="hidden" id="bidhistory-toDate-value" name="endDate"  value="<?php if(isset($_POST["endDate"])){ echo $_POST["endDate"]; } ?>" />
			<button id="aw-date-search" class="aw-button aw-red-button" name="submit" value="Submit">Filter Dates</button>
		</form>
	</div>
	<div class="aw-search">
		<label>Search</label>
		<input type="text" id="bidhistory-filter-search" value="" /> <BR />
		<span id="aw-search-filter-info"></span> <BR />
	</div>
</div>
<?php include_once(plugin_dir_path( __FILE__ ) . '../../page-loading.php'); ?>
<div id="aw-account-myauctions-bid-history-vue-root" style="display:none;">
	<div class="aw-bidhistory-auction" v-for="(year, value) in years" >
		<button data-id="value" class="aw-year-buttons">
			<h1 class="aw-year">{{ value }}<i class="fa fa-chevron-down"></i></h1>
		</button>
		<div v-for="(auction, value) in year.auctions" class="aw-auction">
			<h2>{{ auction, value | longDate }}</h2>
			<!-- <p style="display: none;" class="aw-auction-table-id">{{ auction, value | auctionTableID }}</p> -->
			<table v-bind:id="auction | auctionTableID"
			class="aw-account-auctions-table hover stripe"  >
			<thead>
					<tr class="" >
						<th class=" aw-order-lot"><span class="nobr">LOT</span></th>
						<th class=" aw-order-qty"><span class="nobr">QTY</span></th>
						<th class=" aw-order-fmt"><span class="nobr">FMT</span></th>
						<th class=" aw-order-vin"><span class="nobr">VIN</span></th>
						<th class=" aw-order-name"><span class="nobr">WINE</span></th>
						<th class=" aw-order-des"><span class="nobr">des</span></th>
						<th class=" aw-order-prod"><span class="nobr">Prod</span></th>
						<th class=" aw-order-bidamt"><span class="nobr">Bid AMT</span></th>
						<th class=" aw-order-result"><span class="nobr">Result</span></th>
						<th class=" aw-order-buyagain"><span class="nobr">Buy Again</span></th>
						<th class=" aw-order-appraisal"><span class="nobr">Appraise</span></th>
					</tr>
			</thead>
			<tbody>
				<tr v-for="lot in auction.lots" >
					<td class="aw-lot-id aw-center">{{ lot.lot }}</td>
					<td class="aw-lot-qty aw-center">{{ lot.quantity }}</td>
					<td class="aw-lot-fmt">{{ lot.format }}</td>
					<td class="aw-lot-vin aw-center">{{ lot.vintage }}</td>
					<td class="aw-wine-name">{{ lot.wineName }}</a></td>
					<td class="aw-lot-des">{{ lot.designation }}</td>
					<td class="aw-lot-prod">{{ lot.producer }}</td>
					<!-- {{ lot.currencySymbolLocal }} -->
					<td class="aw-lot-total aw-right">$ {{ lot.bidAmount }}</span></td>
					<td class="aw-lot-result aw-center"><b>{{ lot.result }}</b></td>
					<td class="aw-buy-again">
						<!-- <a class="aw-button aw-red-button" :href="'/?s=' + lot.wineName">Search</a> -->
					</td>
					<td class="aw-wine-appraisal aw-center" v-if="lot.result == 'Win'" >
							<input class="aw-appraisal"
								type="checkbox"
								:row-id="lot.row_id"
								:auction="lot.auctionNoSuffix"
								:lot-id="lot.lot"
								:lot-qty="lot.quantity"
								:wine-fmt="lot.format"
								:wine-name="lot.wineName"
								:wine-vin="lot.vintage"
								:wine-des="lot.designation"
								:bid-amt="lot.bidAmount"
								:result="lot.result"
							/>
					</td>
					<td v-else></td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
		<BR />
	</div>



</div>

<?php
}?>
