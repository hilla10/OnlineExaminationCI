function ajaxcsrf() {
  let csrfname = '<?= $this->security->get_csrf_token_name() ?>';
  let csrfhash = '<?= $this->security->get_csrf_hash() ?>';
  let csrf = {};
  csrf[csrfname] = csrfhash;
  $.ajaxSetup({
    data: csrf,
  });
}

$(document).ready(function () {
  ajaxcsrf();

  $('#btncek').on('click', function () {
    let token = $('#token').val();
    let idExam = $(this).data('id');
    if (token === '') {
      Swal('Failed', 'Token must be filled', 'error');
    } else {
      let key = $('#exam_id').data('key');
      $.ajax({
        url: base_url + 'exam/cektoken/',
        type: 'POST',
        data: {
          exam_id: idExam,
          token: token,
        },
        cache: false,
        success: function (result) {
          Swal({
            type: result.status ? 'success' : 'error',
            title: result.status ? 'Successful' : 'Failed',
            text: result.status ? 'True Token' : 'Incorrect Token',
          }).then((data) => {
            if (result.status) {
              location.href = base_url + 'exam/?key=' + key;
            }
          });
        },
      });
    }
  });

  let time = $('.countdown');
  if (time.length) {
    countdown(time.data('time'));
  }
});
