
<?php
	$rating_remain = 0;
	$rating_to_loop = round($rating_to_loop, 0);
	if($rating_to_loop <= 5){
		$rating_remain = 5 - $rating_to_loop;
	}


	if(!isset($icon)){
		$icon = 'ion-ios-star';
	}

	if(!isset($color)){
		$color = '#FFCC5F';
	}
?>

@for ($i = 0; $i < $rating_to_loop; $i++)
   	<i class="ion {{$icon}} " style="font-size: {{$size}}px; color: {{$color}};"></i>
@endfor

@for ($i = 0; $i < $rating_remain; $i++)
   	<i class="ion {{$icon}}" style="font-size: {{$size}}px; color: #CBC3C6;"></i>
@endfor
