<div class="col-30">
    <div class="column-left">
        <h2>Select options</h2>
        <div class="option-div-container margin-top">
            <span class="bold">Sort by views</span>
            <div class="option-div-list">
                <form action="">
                    <input type="radio" id="i1" name="views-count" value="view-max">
                    <label for="i1">Most popular</label>
                    <div class="clearfix"></div>
                    <input type="radio" id="i2" name="views-count" value="view-min">
                    <label for="i2">Not interested</label>
                </form>
            </div>
        </div>
        <div class="option-div-container margin-top">
            <span class="bold">Related product</span>
            <div class="option-div-list">
                <? foreach ($this->products as $product) {
                    $p = '<p data-name="' . $product['href'] . '">' . $product['name'] . '</p>';
                    echo $p;
                }?>
            </div>
        </div>
        <div class="option-div-container margin-top">
            <span class="bold">Sort by date</span>
            <div class="option-div-list">
                <form action="">
                    <input type="radio" id="d1" name="date-added" value="date-max">
                    <label for="d1">Newest</label>
                    <div class="clearfix"></div>
                    <input type="radio" id="d2" name="date-added" value="date-min">
                    <label for="d2">Very old</label>
                </form>
            </div>
        </div>
        <div class="option-div-container margin-top">
            <span class="bold">Show records</span>
            <div class="option-div-list">
                <select id="record-count">
                    <option>1 record</option>
                    <option>3 records</option>
                    <option>5 records</option>
                    <option>10 records</option>
                    <option>20 records</option>
                </select>
            </div>
        </div>
    </div>
</div>