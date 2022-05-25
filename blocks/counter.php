<?php
/*
 * Counter Block Template.
 */
$id = get_field('section_id');
$sale_live_date = get_field('event_start_date');
?>
<section class="counter-section" id="<?php echo $id; ?>">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="text-block">
					<?php
					echo '<div id="counter">';
					echo '<span class="block"><span class="text">Days</span></span><span class="block"><span class="text">Hours</span></span><span class="block"><span class="text">Minutes</span></span><span class="block"><span class="text">Seconds</span></span>';
					echo '</div>';
					if (!empty($sale_live_date)) {
						?>
						<script>
							// Set the date we're counting down to
							var countDownDate = new Date("<?php echo $sale_live_date; ?>").getTime();
	
							// Update the count down every 1 second
							var x = setInterval(function () {
	
								// Get today's date and time
								var now = new Date().getTime();
	
								// Find the distance between now and the count down date
								var distance = countDownDate - now;
	
								// Time calculations for days, hours, minutes and seconds
								var days = Math.floor(distance / (1000 * 60 * 60 * 24));
								var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
								var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
								var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	
								// Display the result in the element with id="demo"
								document.getElementById("counter").innerHTML = '<span class="block">' + days + '<span class="text">Days</span></span><span class="block">' + hours + '<span class="text">Hours</span></span><span class="block">' + minutes + '<span class="text">Minutes</span></span><span class="block">' + seconds + '<span class="text">Seconds</span></span>';
								//<span class="block">' + seconds + '<span class="text">seconds</span></span>
								// If the count down is finished, write some text
								if (distance < 0) {
									clearInterval(x);
									document.getElementById("counter").innerHTML = '<h2>Event Started</h2>';
								}
							}, 1000);
						</script>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>