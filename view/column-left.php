<div class="col-30">
	<div class="column-left">
		<h2>Select options</h2>
		<div class="option-div-container margin-top">
			<span class="bold">Sort by:</span>
			<div class="option-div-list">
				<form action="">
					<input type="radio" id="date-max" name="sort-by" value="date-max" checked>
					<label for="date-max">Date: newest</label>
					<div class="clearfix"></div>
					<input type="radio" id="date-min" name="sort-by" value="date-min">
					<label for="date-min">Date: very old</label>
					<div class="clearfix"></div>
					<input type="radio" id="view-max" name="sort-by" value="view-max">
					<label for="view-max">Views: most popular</label>
					<div class="clearfix"></div>
					<input type="radio" id="view-min" name="sort-by" value="view-min">
					<label for="view-min">Views: not interested</label>
				</form>
			</div>
		</div>
		<div class="option-div-container margin-top">
			<span class="bold">Show records</span>
			<div class="option-div-list">
				<select id="record-count">
					<option value="1">1 record</option>
					<option value="3">3 records</option>
					<option value="5">5 records</option>
					<option value="10" selected>10 records</option>
					<option value="20">20 records</option>
				</select>
			</div>
		</div>
		<div class="option-div-container margin-top">
			<span class="bold">Related product</span>
			<div class="option-div-list">
				<? foreach ($data['products'] as $product) {
					$p = '<p data-name="' . $product['href'] . '">';
					if ($data['href'] !== $product['href']) {
						$p .= '<a href="' . $product['href'] . '">' . $product['name'] . '</a></p>';
					} else {
						$p .= $product['name'] . '</p>';
					}
					echo $p;
				} ?>
			</div>
		</div>
	</div>
</div>