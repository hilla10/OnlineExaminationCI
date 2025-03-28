rate = Number(rate);
$(document).ready(function () {
  if (rate < 1 || rate > 50) {
    alert('Maximum input 50');
    window.location.href = base_url + 'department';
  } else {
    generate(rate);
  }

  $('#inputs input:first').select();
  $('form#department input').on('change', function () {
    $(this).parent('.form-group').removeClass('has-error');
    $(this).next('.help-block').text('');
  });

  $('form#department').on('submit', function (e) {
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
              window.location.href = base_url + 'department';
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

function generate(n) {
  for (let i = 1; i <= n; i++) {
    inputs = `
            <tr>
                <td>${i}</td>
                <td>
                    <div class="form-group">
                        <input autocomplete="off" type="text" name="department_name[${i}]" class="input-sm form-control">
                        <small class="help-block text-right"></small>
                    </div>
                </td>
            </tr>
            `;
    $('#inputs').append(inputs);
  }
}
