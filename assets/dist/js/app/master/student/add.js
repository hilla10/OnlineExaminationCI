function loadDepartment() {
  $('#department').find('option').not(':first').remove();

  $.getJSON(base_url + 'department/loadDepartment', function (data) {
    let option = [];
    for (let i = 0; i < data.length; i++) {
      option.push({
        id: data[i].department_id,
        text: data[i].department_name,
      });
    }
    $('#department').select2({
      data: option,
    });
  }).fail(function (jqXHR, textStatus, errorThrown) {
    // console.log('Error loading departments: ', textStatus, errorThrown); // Log any errors
  });
}

function load_class(id) {
  $('#class').find('option').not(':first').remove();

  $.getJSON(base_url + 'class/classByDepartment/' + id, function (data) {
    let option = [];
    for (let i = 0; i < data.length; i++) {
      option.push({
        id: data[i].class_id,
        text: data[i].class_name,
      });
    }
    $('#class').select2({
      data: option,
    });
  }).fail(function (jqXHR, textStatus, errorThrown) {
    // console.log('Error loading classes: ', textStatus, errorThrown); // Log any errors
  });
}

$(document).ready(function () {
  ajaxcsrf();

  // Load Department
  loadDepartment();

  $('#department').on('change', function () {
    let departmentId = $(this).val();

    if (departmentId) {
      $.ajax({
        url: base_url + 'student/getClassByDepartment',
        type: 'POST',
        data: { department_id: departmentId },
        dataType: 'json',
        success: function (response) {
          let classDropdown = $('#class');
          classDropdown.empty(); // Clear existing options

          if (response.length > 0) {
            classDropdown.append(
              '<option value="" disabled selected>-- Choose --</option>'
            );
            $.each(response, function (index, classData) {
              classDropdown.append(
                '<option value="' +
                  classData.class_id +
                  '">' +
                  classData.class_name +
                  '</option>'
              );
            });
          } else {
            classDropdown.append(
              '<option value="" disabled>No classes available</option>'
            );
          }
        },
        error: function () {
          console.error('Error loading classes.');
        },
      });
    } else {
      $('#class')
        .empty()
        .append('<option value="" disabled selected>-- Choose --</option>');
    }
  });

  $('form#student input, form#student select').on('change', function () {
    $(this).closest('.form-group').removeClass('has-error has-success');
    $(this).nextAll('.help-block').eq(0).text('');
  });

  $('[name="gender"]').on('change', function () {
    $(this).parent().nextAll('.help-block').eq(0).text('');
  });

  $('form#student').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let btn = $('#submit');
    btn.attr('disabled', 'disabled').text('Wait...');

    $.ajax({
      url: $(this).attr('action'),
      data: $(this).serialize(),
      type: 'POST',
      success: function (data) {
        btn.removeAttr('disabled').text('Save');
        if (data.status) {
          Swal({
            title: 'Success',
            text: 'Data Saved Successfully',
            type: 'success',
          }).then((result) => {
            if (result.value) {
              window.location.href = base_url + 'student';
            }
          });
        } else {
          // console.log(data.errors);
          $.each(data.errors, function (key, value) {
            $('[name="' + key + '"]')
              .nextAll('.help-block')
              .eq(0)
              .text(value);
            $('[name="' + key + '"]')
              .closest('.form-group')
              .addClass('has-error');
            if (value == '') {
              $('[name="' + key + '"]')
                .nextAll('.help-block')
                .eq(0)
                .text('');
              $('[name="' + key + '"]')
                .closest('.form-group')
                .removeClass('has-error')
                .addClass('has-success');
            }
          });
        }
      },
    });
  });
});
