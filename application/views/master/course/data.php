<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Master <?= $subtitle ?></h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body">
		<div class="mt-2 mb-3">
			<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-flat bg-blue"><i class="fa fa-plus"></i> Add Data</button>
			<a href="<?= base_url('course/import') ?>" class="btn btn-sm btn-flat btn-success"><i class="fa fa-upload"></i> Import</a>
			<button type="button" onclick="reload_ajax()" class="btn btn-sm bg-maroon btn-flat btn-default"><i class="fa fa-refresh"></i> Reload</button>
			<div class="pull-right">
				<button onclick="bulk_edit()" class="btn btn-sm btn-flat btn-warning" type="button"><i class="fa fa-edit"></i> Edit</button>
				<button onclick="bulk_delete()" class="btn btn-sm btn-flat btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button>
			</div>
		</div>
		<?= form_open('', array('id' => 'bulk')) ?>
		<table id="course" class="w-100 table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Course</th>
					<th class="text-center">
						<input type="checkbox" class="select_all">
					</th>
				</tr>
			</thead>
			<!-- <tfoot>
				<tr>
					<th>#</th>
					<th>Course</th>
					<th class="text-center">
						<input type="checkbox" class="select_all">
					</th>
				</tr>
			</tfoot> -->
		</table>
		<?= form_close() ?>
	</div>
</div>

<div class="modal fade" id="myModal">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Add Data</h4>
			</div>
			<?= form_open('course/add', array('id', 'tambah')); ?>
			<div class="modal-body">
				<div class="form-group">
					<label for="rate">Number of Data</label>
					<input value="1" minlength="1" maxlength="50" min="1" max="50" id="rateinput" type="number" autocomplete="off" required="required" name="rate" class="form-control">
					<small class="help-block">Max. 50</small>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" name="input">Generate</button>
			</div>
			<?= form_close(); ?>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/course/data.js"></script>