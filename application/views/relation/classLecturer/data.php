<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Relation <?=$subtitle?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="mt-2 mb-3">
            <a href="<?=base_url('classLecturer/add')?>" class="btn btn-sm btn-flat bg-blue"><i class="fa fa-plus"></i> Add Data</a>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm bg-maroon btn-flat btn-default"><i class="fa fa-refresh"></i> Reload</button>
			<div class="pull-right">
				<button onclick="bulk_delete()" class="btn btn-sm btn-flat btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button>
			</div>
        </div>
		<?=form_open('',array('id'=>'bulk'))?>
        <table id="classLecturer" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Teacher ID</th>
                    <th>Lecturer</th>
                    <th>Class</th>
					<th class="text-center">Edit</th>
					<th class="text-center">
						<input type="checkbox" class="select_all">
					</th>
                </tr>
            </thead>
            <!-- <tfoot>
                <tr>
                    <th>#</th>
                    <th>Teacher ID</th>
                    <th>Lecturer</th>
                    <th>Class</th>
					<th class="text-center">Edit</th>
					<th class="text-center">
						<input type="checkbox" class="select_all">
					</th>
                </tr>
            </tfoot> -->
        </table>
		<?=form_close()?>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/relation/classLecturer/data.js"></script>