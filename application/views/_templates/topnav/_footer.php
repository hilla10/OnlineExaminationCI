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
		let time = new Date(t);
		let n = new Date();
		let x = setInterval(function() {
			let now = new Date().getTime();
			let dis = time.getTime() - now;
			let h = Math.floor((dis % (1000 * 60 * 60 * 60)) / (1000 * 60 * 60));
			let m = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60));
			let s = Math.floor((dis % (1000 * 60)) / (1000));
			h = ("0" + h).slice(-2);
			m = ("0" + m).slice(-2);
			s = ("0" + s).slice(-2);
			let cd = h + ":" + m + ":" + s;
			$('.remainingTime').html(cd);
		}, 100);
		setTimeout(function() {
			timesUP();
		}, (time.getTime() - n.getTime()));
	}

	function countdown(t) {
		let time = new Date(t);
		let n = new Date();
		let x = setInterval(function() {
			let now = new Date().getTime();
			let dis = time.getTime() - now;
			let d = Math.floor(dis / (1000 * 60 * 60 * 24));
			let h = Math.floor((dis % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			let m = Math.floor((dis % (1000 * 60 * 60)) / (1000 * 60));
			let s = Math.floor((dis % (1000 * 60)) / (1000));
			d = ("0" + d).slice(-2);
			h = ("0" + h).slice(-2);
			m = ("0" + m).slice(-2);
			s = ("0" + s).slice(-2);
			let cd = d + " Day, " + h + " Hours, " + m + " Minute, " + s + " Second ";
			$('.countdown').html(cd);

			setTimeout(function() {
				location.reload()
			}, dis);
		}, 1000);
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
