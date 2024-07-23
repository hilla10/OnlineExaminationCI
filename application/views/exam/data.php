<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$subtitle?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <button type="button" onclick="bulk_delete()" class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash"></i> Bulk Delete</button>
        <div class="pull-right">
            <a href="<?=base_url('exam/add')?>" class="btn bg-blue btn-sm btn-flat"><i class="fa fa-plus"></i> Conduct New Exam</a>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm btn-flat bg-maroon"><i class="fa fa-refresh"></i> Reload</button>
        </div>
    </div>
	<?=form_open('exam/delete', array('id'=>'bulk'))?>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        <table id="exam" class="w-100 table table-striped table-bordered table-hover">
        <thead>
            <tr>
				<th class="text-center">
					<input type="checkbox" class="select_all">
				</th>
                <th>#</th>
                <th>Exam Name</th>
                <th>Course</th>
                <th>Total Ques.</th>
                <th>Time</th>
                <th>Pattern</th>
				<th	class="text-center">Token</th>
				<th class="text-center">Action</th>
            </tr>        
        </thead>
        <!-- <tfoot>
            <tr>
				<th class="text-center">
					<input type="checkbox" class="select_all">
				</th>
                <th>#</th>
                <th>Exam Name</th>
                <th>Course</th>
                <th>Number of Ques.</th>
                <th>Time</th>
                <th>Pattern</th>                
				<th	class="text-center">Token</th>
				<th class="text-center">Action</th>
            </tr>
        </tfoot> -->
        </table>
    </div>
	<?=form_close();?>
</div>

<script type="text/javascript">
	var lecturer_id = '<?=$lecturer->lecturer_id?>';
</script>

<script src="<?=base_url()?>assets/dist/js/app/exam/data.js"></script>