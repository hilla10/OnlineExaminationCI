$(document).ready(function () {
  $('form#login input').on('change', function () {
    $(this).parent().removeClass('has-error');
    $(this).next().next().text('');
  });

  $('#togglePassword').on('click', function () {
    // Toggle the type attribute
    const passwordField = $('#password');
    const type =
      passwordField.attr('type') === 'password' ? 'text' : 'password';
    passwordField.attr('type', type);

    // Toggle the icon class
    $(this).toggleClass('bi-eye bi-eye-slash-fill');

    // Toggle the text content
    const toggleText = $('#toggleText');
    toggleText.text(
      toggleText.text() === 'Show Password' ? 'Hide Password' : 'Show Password'
    );
  });

  $('form#login').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    let infobox = $('#infoMessage');
    infobox.addClass('callout callout-info').text('Checking...');

    let btnsubmit = $('#submit');
    btnsubmit.attr('disabled', 'disabled').val('Wait...');

    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: $(this).serialize(),
      success: function (data) {
        infobox.removeAttr('class').text('');
        btnsubmit.removeAttr('disabled').val('Login');
        if (data.status) {
          infobox
            .addClass('callout callout-success text-center')
            .text('Login Success');
          let go = base_url + data.url;
          window.location.href = go;
        } else {
          if (data.invalid) {
            $.each(data.invalid, function (key, val) {
              $('[name="' + key + '"')
                .parent()
                .addClass('has-error');
              $('[name="' + key + '"')
                .next()
                .next()
                .text(val);
              if (val == '') {
                $('[name="' + key + '"')
                  .parent()
                  .removeClass('has-error');
                $('[name="' + key + '"')
                  .next()
                  .next()
                  .text('');
              }
            });
          }
          if (data.failed) {
            infobox
              .addClass('callout callout-danger text-center')
              .text(data.failed);
          }
        }
      },
    });
  });
});
