let table;
$(document).ready(function () {
  ajaxcsrf();

   table = $('#detail_result').DataTable({
     initComplete: function () {
       let api = this.api();
       $('#detail_result_filter input')
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
       url: base_url + 'examResult/ScoreMhs/' + id,
       type: 'POST',
     },
     columns: [
       {
         data: 'id',
         orderable: false,
         searchable: false,
       },
       { data: 'name' },
       { data: 'class_name' },
       { data: 'department_name' },
       { data: 'correct_count' },
       { data: 'score' },
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
