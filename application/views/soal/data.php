<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$subjudul?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
		<div class="row">
        	<div class="col-sm-4">
				<button type="button" onclick="bulk_delete()" class="btn btn-flat btn-sm bg-red"><i class="fa fa-trash"></i> Bulk Delete</button>
			</div>
			<div class="form-group col-sm-4 text-center">
				<?php if ( $this->ion_auth->is_admin() ) : ?>
					<select id="matkul_filter" class="form-control select2" style="width:100% !important">
						<option value="all">All Course</option>
						<?php foreach ($course as $m) :?>
							<option value="<?=$m->course_id?>"><?=$m->course_name?></option>
						<?php endforeach; ?>
					</select>
				<?php endif; ?>
				<?php if ( $this->ion_auth->in_group('Lecturer') ) : ?>				
					<input id="course_id" value="<?=$course->course_name;?>" type="text" readonly="readonly" class="form-control">
				<?php endif; ?>
			</div>
			<div class="col-sm-4">
				<div class="pull-right">
					<a href="<?=base_url('soal/add')?>" class="btn bg-blue btn-flat btn-sm"><i class="fa fa-plus"></i> Create Questions</a>
					<button type="button" onclick="reload_ajax()" class="btn btn-flat btn-sm bg-maroon"><i class="fa fa-refresh"></i> Reload</button>
				</div>
			</div>
		</div>
    </div>
	<?=form_open('soal/delete', array('id'=>'bulk'))?>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        <table id="soal" class="w-100 table table-striped table-bordered table-hover">
        <thead>
            <tr>
				<th class="text-center">
					<input type="checkbox" class="select_all">
				</th>
                <th width="25">#</th>
				<th>Lecturer</th>
                <th>Course</th>
				<th>Question</th>
				<th>Created Date</th>
				<th class="text-center">Action</th>
            </tr>        
        </thead>
        <!-- <tfoot>
            <tr>
				<th class="text-center">
					<input type="checkbox" class="select_all">
				</th>
                <th width="25">#</th>
				<th>Lecturer</th>
                <th>Course</th>
				<th>Question</th>
				<th>Created Date</th>
				<th class="text-center">Action</th>
            </tr>
        </tfoot> -->
        </table>
    </div>
	<?=form_close();?>
</div>

<script src="<?=base_url()?>assets/dist/js/app/soal/data.js"></script>

<?php if ( $this->ion_auth->is_admin() ) : ?>
<script type="text/javascript">
$(document).ready(function(){
	$('#matkul_filter').on('change', function(){
		let course_id = $(this).val();
		let src = '<?=base_url()?>soal/data';
		let url;

		if(course_id !== 'all'){
			let src2 = src + '/' + course_id;
			url = $(this).prop('checked') === true ? src : src2;
		}else{
			url = src;
		}
		table.ajax.url(url).load();
	});
});
</script>
<?php endif; ?>
<?php if ( $this->ion_auth->in_group('Lecturer') ) : ?>
<script type="text/javascript">
$(document).ready(function(){
	let course_id = '<?=$course->course_id?>';
	let lecturer_id = '<?=$course->lecturer_id?>';
	let src = '<?=base_url()?>soal/data';
	let url = src + '/' + course_id + '/' + lecturer_id;

	table.ajax.url(url).load();
});
</script>
<?php endif; ?>