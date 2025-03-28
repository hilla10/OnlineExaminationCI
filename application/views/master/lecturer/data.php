<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Master <?= $subtitle ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="mt-2 mb-4">
            <a href="<?= base_url('lecturer/add') ?>" class="btn btn-sm bg-blue btn-flat"><i class="fa fa-plus"></i> Add Data</a>
            <a href="<?= base_url('lecturer/import') ?>" class="btn btn-sm btn-flat btn-success"><i class="fa fa-upload"></i> Import</a>
            <button type="button" onclick="reload_ajax()" class="btn btn-sm bg-maroon btn-default btn-flat"><i class="fa fa-refresh"></i> Reload</button>
            <div class="pull-right">
                <button onclick="bulk_delete()" class="btn btn-sm btn-danger btn-flat" type="button"><i class="fa fa-trash"></i> Delete</button>
            </div>
        </div>
        <?= form_open('lecturer/delete', array('id' => 'bulk')) ?>
        <table id="lecturer" class="w-100 table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Teacher ID</th>
                    <th>Lecturer Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th class="text-center">Action</th>
                    <th class="text-center">
                        <input type="checkbox" class="select_all">
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
            <!-- <tfoot>
                <tr>
                    <th>#</th>
                    <th>Teacher ID</th>
                    <th>Lecturer Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th class="text-center">Action</th>
                    <th class="text-center">
                        <input type="checkbox" class="select_all">
                    </th>
                </tr>
            </tfoot> -->
        </table>
        <?= form_close() ?>
    </div>
</div>

<script src="<?= base_url() ?>assets/dist/js/app/master/lecturer/data.js"></script>