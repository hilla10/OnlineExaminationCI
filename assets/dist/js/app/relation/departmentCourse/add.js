function loadDepartment(id) {
  $('#department option').remove();
  $.getJSON(
    base_url + 'departmentCourse/getDepartmentId/' + id,
    function (data) {
      console.log(data);
      let option;
      $.each(data, function (key, val) {
        option = `
                    <option value="${val.department_id}">${val.department_name}</option>
                `;
        $('#department').append(option);
      });
    }
  );
}

$(document).ready(function () {
  $('[name="course_id"]').on('change', function () {
    loadDepartment($(this).val());
  });

  $('form#departmentCourse select').on('change', function () {
    $(this).closest('.form-group').removeClass('has-error');
    $(this).nextAll('.help-block').eq(0).text('');
  });

  $('form#departmentCourse').on('submit', function (e) {
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
        console.log(data);
        if (data.status) {
          Swal({
            title: 'Success',
            text: 'Data Data Saved Successfully',
            type: 'success',
          }).then((result) => {
            if (result.value) {
              window.location.href = base_url + 'departmentCourse';
            }
          });
        } else {
          if (data.errors) {
            let j;
            $.each(data.errors, function (key, val) {
              j = $('[name="' + key + '"]');
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
