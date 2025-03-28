$(document).ready(function () {
  $('form#classLecturer select').on('change', function () {
    $(this).closest('.form-group').removeClass('has-error');
    $(this).nextAll('.help-block').eq(0).text('');
  });

  $('form#classLecturer').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let btn = $('#submit');
    btn.attr('disabled', 'disabled').text('Wait...');

    $.ajax({
      url: $(this).attr('action'),
      data: $(this).serialize(),
      method: 'POST',
      success: function (data) {
        btn.removeAttr('disabled').text('Save');

        if (data.status) {
          Swal({
            title: 'Success',
            text: 'Data Saved Successfully',
            type: 'success',
          }).then((result) => {
            if (result.value) {
              window.location.href = base_url + 'classLecturer';
            }
          });
        } else {
          if (data.errors) {
            let j;
            $.each(data.errors, function (key, val) {
              j =
                key.substring(0, 6) == 'course'
                  ? $('[name="' + key + '[]"]')
                  : $('[name="' + key + '"]');
              j.closest('.form-group').addClass('has-error');
              j.nextAll('.help-block').eq(0).text(val);
              if (val == '') {
                j.parent().addClass('has-error');
                j.nextAll('.help-block').eq(0).text('');
              }
            });
          }
        }
      },
    });
  });
});
