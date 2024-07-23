// Function to load departments
function loadDepartment() {
  $('#department').find('option').not(':first').remove();

  $.getJSON(base_url + 'department/loadDepartment', function (data) {
    var options = [];
    for (let i = 0; i < data.length; i++) {
      options.push({
        id: data[i].department_id,
        text: data[i].department_name,
      });
    }
    $('#department').select2({
      data: options,
    });
  }).fail(function (jqXHR, textStatus, errorThrown) {
    // console.log('Error loading departments: ', textStatus, errorThrown); // Log any errors
  });
}

// Function to load classes based on department ID
function load_class(id) {
  $('#class').find('option').not(':first').remove();

  $.getJSON(base_url + 'class/classByDepartment/' + id, function (data) {
    var options = [];
    for (let i = 0; i < data.length; i++) {
      options.push({
        id: data[i].class_id,
        text: data[i].class_name,
      });
    }
    $('#class').select2({
      data: options,
    });
  }).fail(function (jqXHR, textStatus, errorThrown) {
    // console.log('Error loading classes: ', textStatus, errorThrown); // Log any errors
  });
}

$(document).ready(function () {
  ajaxcsrf();

  // Load Department
  loadDepartment();

  // Event listener for department change
  $('#department').on('change', function () {
    var departmentId = $(this).val();

    if (departmentId) {
      $.ajax({
        url: base_url + 'student/getClassByDepartment',
        type: 'POST',
        data: { department_id: departmentId },
        dataType: 'json',
        success: function (response) {
          var classDropdown = $('#class');
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

  // Reset form validation states on change
  $('form#student input, form#student select').on('change', function () {
    $(this).closest('.form-group').removeClass('has-error has-success');
    $(this).nextAll('.help-block').eq(0).text('');
  });

  $('[name="gender"]').on('change', function () {
    $(this).parent().nextAll('.help-block').eq(0).text('');
  });

  // Form submission handler
  $('form#student').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission
    e.stopImmediatePropagation();

    var btn = $('#submit');
    btn.attr('disabled', 'disabled').text('Wait...');

    $.ajax({
      url: $(this).attr('action'), // URL for form submission
      data: $(this).serialize(), // Serialize form data
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
              window.location.href = base_url + 'student'; // Redirect on success
            }
          });
        } else {
          // console.log(data.errors);
          $.each(data.errors, function (key, value) {
            var input = $('[name="' + key + '"]');
            input.nextAll('.help-block').eq(0).text(value);
            input.closest('.form-group').addClass('has-error');
            if (value == '') {
              input.nextAll('.help-block').eq(0).text('');
              input
                .closest('.form-group')
                .removeClass('has-error')
                .addClass('has-success');
            }
          });
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        btn.removeAttr('disabled').text('Save');
        console.error(
          'Error during form submission: ',
          textStatus,
          errorThrown
        );
      },
    });
  });
});
