var table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#ujian').DataTable({
    initComplete: function () {
      var api = this.api();
      $('#ujian_filter input')
        .off('.DT')
        .on('keyup.DT', function (e) {
          api.search(this.value).draw();
        });
    },
    oLanguage: {
      sProcessing: 'loading...',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + 'ujian/list_json',
      type: 'POST',
    },
    columns: [
      {
        data: 'exam_id',
        orderable: false,
        searchable: false,
      },
      { data: 'exam_name' },
      { data: 'course_name' },
      { data: 'lecturer_name' },
      { data: 'number_of_questions' },
      { data: 'duration' },
      {
        searchable: false,
        orderable: false,
      },
    ],
    columnDefs: [
      {
        targets: 6,
        data: {
          exam_id: 'exam_id',
          ada: 'ada',
        },
        render: function (data, type, row, meta) {
          var btn;
          if (data.ada > 0) {
            btn = `
								<a class="btn btn-xs btn-success" href="${base_url}hasilujian/cetak/${data.exam_id}" target="_blank">
									<i class="fa fa-print"></i> Print Results
								</a>`;
          } else {
            btn = `<a class="btn btn-xs btn-primary" href="${base_url}ujian/token/${data.exam_id}">
								<i class="fa fa-pencil"></i> Take Exam
							</a>`;
          }
          return `<div class="text-center">
									${btn}
								</div>`;
        },
      },
    ],
    order: [[1, 'asc']],
    rowId: function (a) {
      return a;
    },
    rowCallback: function (row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $('td:eq(0)', row).html(index);
    },
  });
});
