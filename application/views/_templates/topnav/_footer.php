</section>
<!-- /.content -->
</div>
<!-- /.container -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
	<div class="container">
		<?= date("l, d F Y") ?>, <span class="live-clock"><?= date('H:i:s') ?></span>
		<div class="pull-right hidden-xs">
			<b>Online Exams</b> v2
		</div>
	</div>
	<!-- /.container -->
</footer>
</div>
<!-- ./wrapper -->

<script src="<?= base_url() ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/dist/js/adminlte.min.js"></script>
<script src="<?= base_url() ?>assets/bower_components/pace/pace.min.js"></script>

<script type="text/javascript">



function remainingTime(t) {
    let targetTime = new Date(t).getTime();
    let now = new Date().getTime();

    // Check if the target time is in the past
    if (targetTime <= now) {
        $('.remainingTime').html("00:00:00");
        timesUP(); // Call the function when time is up
        return;
    }

    let x = setInterval(function() {
        now = new Date().getTime(); // Update the current time
        let dis = targetTime - now;

        if (dis <= 0) {
            clearInterval(x); // Stop the interval when time is up
            $('.remainingTime').html("00:00:00");
            timesUP(); // Call the function when time is up
            return;
        }

        let h = Math.floor((dis % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let m = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60));
        let s = Math.floor((dis % (1000 * 60)) / 1000);

        // Pad single digits with leading zeroes
        h = ("0" + h).slice(-2);
        m = ("0" + m).slice(-2);
        s = ("0" + s).slice(-2);

        let cd = h + ":" + m + ":" + s;
        $('.remainingTime').html(cd); // Update the display
    }, 1000); // Update every second
}

	

	function countdown(t) {
    let time = new Date(t).getTime(); // Convert the time to a timestamp
    let x = setInterval(function() {
        let now = new Date().getTime(); // Get the current timestamp
        let dis = time - now; // Calculate the difference

        // If the countdown is finished
        if (dis <= 0) {
            clearInterval(x); // Stop the countdown
            dis = 0; // Set dis to zero to avoid negative values
           location.reload() // reload the page 
        }

        // Calculate days, hours, minutes, seconds
        let d = Math.floor(dis / (1000 * 60 * 60 * 24));
        let h = Math.floor((dis % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let m = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60));
        let s = Math.floor((dis % (1000 * 60)) / (1000));

        // Format time values with leading zeros
        d = ("0" + d).slice(-2);
        h = ("0" + h).slice(-2);
        m = ("0" + m).slice(-2);
        s = ("0" + s).slice(-2);

        // Display time in the element
        let cd = d + " Day, " + h + " Hours, " + m + " Minute, " + s + " Second ";
        $('.countdown').html(cd);

    }, 1000); // Update every second
}


	function ajaxcsrf() {
		let csrfname = '<?= $this->security->get_csrf_token_name() ?>';
		let csrfhash = '<?= $this->security->get_csrf_hash() ?>';
		let csrf = {};
		csrf[csrfname] = csrfhash;
		$.ajaxSetup({
			"data": csrf
		});
	}



	$(document).ready(function() {
		setInterval(function() {
			let date = new Date();
			let h = date.getHours(),
				m = date.getMinutes(),
				s = date.getSeconds();
			h = ("0" + h).slice(-2);
			m = ("0" + m).slice(-2);
			s = ("0" + s).slice(-2);

			let time = h + ":" + m + ":" + s;
			$('.live-clock').html(time);
		}, 1000);
	});
</script>
</body>

</html>
