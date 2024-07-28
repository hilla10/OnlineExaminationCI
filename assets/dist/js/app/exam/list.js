let table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#exam').DataTable({
    initComplete: function () {
      let api = this.api();
      $('#exam_filter input')
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
      url: base_url + 'exam/list_json',
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
      { data: 'total_questions' },
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
          let btn;
          if (data.ada > 0) {
            btn = `
								<a class="btn btn-xs btn-success" href="${base_url}examResult/print/${data.exam_id}" target="_blank">
									<i class="fa fa-print"></i> Print Results
								</a>`;
          } else {
            btn = `<a class="btn btn-xs btn-primary" href="${base_url}exam/token/${data.exam_id}">
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
      let info = this.fnPagingInfo();
      let page = info.iPage;
      let length = info.iLength;
      let index = page * length + (iDisplayIndex + 1);
      $('td:eq(0)', row).html(index);
    },
  });
});
