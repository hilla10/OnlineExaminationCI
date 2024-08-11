	<footer class="login-footer">
				<!-- To the right -->
				<div class="pull-right hidden-xs">
					Online Examination System
				</div>
				<!-- Default to the left -->
				<strong>&copy; <?php echo date('Y')?> - Developed by Entoto Polytechnic College. </strong> All rights reserved
			</footer>

<script src="<?=base_url()?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
        });
    });
</script>
</body>
</html>