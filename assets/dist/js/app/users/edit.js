function submitajax(url, data, msg, btn, originalText) {
  $.ajax({
    url: url,
    data: data,
    type: 'POST',
    success: function (response) {
      if (response.status) {
        Swal({
          title: 'Successful',
          text: msg,
          type: 'success',
        });
        $('form#change_password').trigger('reset');
      } else {
        if (response.errors) {
          $.each(response.errors, function (key, val) {
            $('[name="' + key + '"]')
              .closest('.form-group')
              .addClass('has-error');
            $('[name="' + key + '"]')
              .nextAll('.help-block')
              .eq(0)
              .text(val);
            if (val === '') {
              $('[name="' + key + '"]')
                .closest('.form-group')
                .removeClass('has-error');
              $('[name="' + key + '"]')
                .nextAll('.help-block')
                .eq(0)
                .text('');
            }
          });
        }
        if (response.msg) {
          Swal({
            title: 'Failed',
            text: 'Old password is not correct',
            type: 'error',
          });
        }
      }
      btn.removeAttr('disabled').text(originalText);
    },
  });
}

$(document).ready(function () {
  $('form input, form select').on('change', function () {
    $(this).closest('.form-group').removeClass('has-error');
    $(this).nextAll('.help-block').eq(0).text('');
  });

  $('form#user_info').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let btn = $('#btn-info');
    let originalText = btn.text();
    btn.attr('disabled', 'disabled').text('Process...');

    let url = $(this).attr('action');
    let data = $(this).serialize();
    let msg = 'User information has been successfully updated';
    submitajax(url, data, msg, btn, originalText);
  });

  $('form#user_level').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let btn = $('#btn-level');
    let originalText = btn.text();
    btn.attr('disabled', 'disabled').text('Process...');

    let url = $(this).attr('action');
    let data = $(this).serialize();
    let msg = 'User level has been successfully updated';
    submitajax(url, data, msg, btn, originalText);
  });

  $('form#user_status').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let btn = $('#btn-status');
    let originalText = btn.text();
    btn.attr('disabled', 'disabled').text('Process...');

    let url = $(this).attr('action');
    let data = $(this).serialize();
    let msg = 'User status has been successfully updated';
    submitajax(url, data, msg, btn, originalText);
  });

  $('form#change_password').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let btn = $('#btn-pass');
    let originalText = btn.text();
    btn.attr('disabled', 'disabled').text('Process...');

    let url = $(this).attr('action');
    let data = $(this).serialize();
    let msg = "Your password has been changed successfully!";
    submitajax(url, data, msg, btn, originalText);
  });

  // Toggle password visibility
    function togglePasswordVisibility(passwordFieldSelector, toggleButtonSelector, toggleTextSelector) {
        const passwordField = $(passwordFieldSelector);
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);

        const toggleButton = $(toggleButtonSelector);
        toggleButton.toggleClass('bi-eye bi-eye-slash-fill');

        const toggleText = $(toggleTextSelector);
        toggleText.text(type === 'password' ? 'Show Password' : 'Hide Password');
    }

    // Bind click events for password toggling
    $('#togglePasswordOld').on('click', function () {
        togglePasswordVisibility('#old', '#togglePasswordOld', '#toggleTextOld');
    });

    $('#togglePasswordNew').on('click', function () {
        togglePasswordVisibility('#new', '#togglePasswordNew', '#toggleTextNew');
    });

    $('#togglePasswordConfirm').on('click', function () {
        togglePasswordVisibility('#new_confirm', '#togglePasswordConfirm', '#toggleTextConfirm');
    });
});