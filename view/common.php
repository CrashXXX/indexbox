<div class="col-60">
<h1><?=$this->data['h1']?></h1>
<? foreach ($this->data as $review) {
    if (!is_array($review)) continue;
	$div = '<div class="review" onclick="document.location.href=\'/' . $review['href'] . '\'">';
	$div .= '<p class="bold">' . $review['title'] . '</p>';
	$div .= '<p>' . $review['description'] . '</p>';
	$div .= '<p class="text-right break-word">Date: <span class="bold margin-right">' . $review['time_create'] . '</span> Views: <span class="bold">' . $review['views'] . '</span></p>';
	$div .= '</div>';
	echo $div;
}
?>
</div>
