$(document).ready(function () {
  $('.datetimepicker').datetimepicker({
    format: 'MM/DD/YYYY hh:mm:ss A',
    showTodayButton: true,
    showClear: true,
    showClose: true,
    stepping: 1, // Increment in minutes, you can adjust this as needed
    icons: {
      time: 'fa fa-clock',
      date: 'fa fa-calendar',
      up: 'fa fa-chevron-up',
      down: 'fa fa-chevron-down',
      previous: 'fa fa-chevron-left',
      next: 'fa fa-chevron-right',
      today: 'fa fa-crosshairs',
      clear: 'fa fa-trash',
      close: 'fa fa-times',
    },
  });

  $('#formexam input, #formexam select').on('change', function () {
    $(this).closest('.form-group').eq(0).removeClass('has-error');
    $(this).nextAll('.help-block').eq(0).text('');
  });

  $('#formexam').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let btn = $('#submit');
    btn.attr('disabled', 'disabled').text('Process...');

    $.ajax({
      url: $(this).attr('action'),
      data: $(this).serialize(),
      type: 'POST',
      success: function (data) {
        btn.removeAttr('disabled').html('<i class="fa fa-save"></i> Save');

        if (data.status) {
          Swal({
            title: 'Success',
            type: 'success',
            text: 'Data saved successfully',
          }).then((result) => {
            window.location = 'master';
          });
        } else {
          if (data.errors) {
            $.each(data.errors, function (key, val) {
              $('[name="' + key + '"]')
                .closest('.form-group')
                .eq(0)
                .addClass('has-error');
              $('[name="' + key + '"]')
                .nextAll('.help-block')
                .eq(0)
                .text(val);
              if (val === '') {
                $('[name="' + key + '"]')
                  .closest('.form-group')
                  .eq(0)
                  .removeClass('has-error');
                $('[name="' + key + '"]')
                  .nextAll('.help-block')
                  .eq(0)
                  .text('');
              }
            });
          }
        }
      },
    });
  });
});
