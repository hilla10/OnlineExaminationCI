var table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#hasil').DataTable({
    initComplete: function () {
      var api = this.api();
      $('#hasil_filter input')
        .off('.DT')
        .on('keyup.DT', function (e) {
          api.search(this.value).draw();
        });
    },
    dom:
      "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
      {
        extend: 'copy',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
      },
      {
        extend: 'print',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
      },
      {
        extend: 'excel',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
      },
      {
        extend: 'pdf',
        exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
      },
    ],
    oLanguage: {
      sProcessing: 'loading...',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + 'examResult/data',
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
      { data: 'start_time' },
      {
        orderable: false,
        searchable: false,
      },
    ],
    columnDefs: [
      {
        targets: 7,
        data: 'exam_id',
        render: function (data, type, row, meta) {
          return `
                    <div class="text-center">
                        <a class="btn btn-xs bg-green" href="${base_url}examResult/detail/${data}" >
                            <i class="fa fa-search"></i> View Results
                        </a>
                    </div>
                    `;
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

table.buttons().container().appendTo('#hasil_wrapper .col-md-6:eq(0)');
