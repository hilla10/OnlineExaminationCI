rate = Number(rate);
$(document).ready(function () {
  if (rate < 1 || rate > 50) {
    alert('Maximum input 50');
    window.location = base_url + 'class';
  }

  $('form#class input, form#class select').on('change', function () {
    $(this).closest('.form-group').removeClass('has-error');
    $(this).next().next().text('');
  });

  $('form#class').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    var btn = $('#submit');
    btn.attr('disabled', 'disabled').text('Wait...');

    $.ajax({
      url: $(this).attr('action'),
      data: $(this).serialize(),
      method: 'POST',
      success: function (data) {
        btn.removeAttr('disabled').text('Save');
        //console.log(data);
        if (data.status) {
          Swal({
            title: 'Success',
            text: 'Data Saved Successfully',
            type: 'success',
          }).then((result) => {
            if (result.value) {
              window.location.href = base_url + 'class';
            }
          });
        } else {
          var j;
          for (let i = 0; i <= data.errors.length; i++) {
            $.each(data.errors[i], function (key, val) {
              j = $('[name="' + key + '"]');
              j.closest('.form-group').addClass('has-error');
              j.next().next().text(val);
              if (val == '') {
                j.closest('.form-group').removeClass('has-error');
                j.next().next().text('');
              }
            });
          }
        }
      },
    });
  });
});
