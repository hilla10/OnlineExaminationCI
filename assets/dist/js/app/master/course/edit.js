$(document).ready(function () {
  $('form#course input').on('change', function () {
    $(this).parent('.form-group').removeClass('has-error');
    $(this).next('.help-block').text('');
  });

  $('form#course').on('submit', function (e) {
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
        //
        if (data.status) {
          Swal({
            title: 'Success',
            text: 'Data Saved Successfully',
            type: 'success',
          }).then((result) => {
            if (result.value) {
              window.location.href = base_url + 'course';
            }
          });
        } else {
          let j;
          for (let i = 0; i <= data.errors.length; i++) {
            $.each(data.errors[i], function (key, val) {
              j = $('[name="' + key + '"]');
              j.parent().addClass('has-error');
              j.next('.help-block').text(val);
              if (val == '') {
                j.parent('.form-group').removeClass('has-error');
                j.next('.help-block').text('');
              }
            });
          }
        }
      },
    });
  });
});
