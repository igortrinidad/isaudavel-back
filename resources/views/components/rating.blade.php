
<?php
	$rating_remain = 0;
	$rating_to_loop = round($rating_to_loop, 0);
	if($rating_to_loop <= 5){
		$rating_remain = 5 - $rating_to_loop;
	}
?>

@for ($i = 0; $i < $rating_to_loop; $i++)
   	<i class="ion ion-ios-star c-gold" style="font-size: {{$size}}px; color: #FFCC5F;"></i>
@endfor

@for ($i = 0; $i < $rating_remain; $i++)
   	<i class="ion ion-ios-star-outline c-gold" style="font-size: {{$size}}px; color: #FFCC5F;"></i>
@endfor
