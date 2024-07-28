$(document).ready(function () {
  var t = $('.remainingTime');
  if (t.length) {
    remainingTime(t.data('time'));
  }

  open(1);
  saveTemporarily();

  widget = $('.step');
  btnnext = $('.next');
  btnback = $('.back');
  btnsubmit = $('.submit');

  $('.step, .back, .completed').hide();
  $('#widget_1').show();
});

function getFormData($form) {
  var unindexed_array = $form.serializeArray();
  var indexed_array = {};
  $.map(unindexed_array, function (n, i) {
    indexed_array[n['name']] = n['value'];
  });
  return indexed_array;
}

function open(id_widget) {
  $('.next').attr('rel', id_widget + 1);
  $('.back').attr('rel', id_widget - 1);
  $('.ragu_ragu').attr('rel', id_widget);
  cek_status_ragu(id_widget);
  last_check(id_widget);

  $('#question_number').html(id_widget);

  $('.step').hide();
  $('#widget_' + id_widget).show();

  Save();
}

function next() {
  var Next = $('.next').attr('rel');
  Next = parseInt(Next);
  Next = Next > total_widget ? total_widget : Next;

  $('#question_number').html(Next);

  $('.next').attr('rel', Next + 1);
  $('.back').attr('rel', Next - 1);
  $('.ragu_ragu').attr('rel', Next);
  cek_status_ragu(Next);
  last_check(Next);

  var finished = Next == total_widget ? 1 : 0;

  $('.step').hide();
  $('#widget_' + Next).show();

  if (finished == 1) {
    $('.back').show();
    $('.next').hide();
  } else if (finished == 0) {
    $('.next').show();
    $('.back').show();
  }

  Save();
}

function back() {
  var back = $('.back').attr('rel');
  back = parseInt(back);
  back = back < 1 ? 1 : back;

  $('#question_number').html(back);

  $('.back').attr('rel', back - 1);
  $('.next').attr('rel', back + 1);
  $('.ragu_ragu').attr('rel', back);
  cek_status_ragu(back);
  last_check(back);

  $('.step').hide();
  $('#widget_' + back).show();

  var already_start = back == 1 ? 1 : 0;

  $('.step').hide();
  $('#widget_' + back).show();

  if (already_start == 1) {
    $('.back').hide();
    $('.next').show();
  } else if (already_start == 0) {
    $('.next').show();
    $('.back').show();
  }

  Save();
}

function no_answer() {
  var id_step = $('.ragu_ragu').attr('rel');
  var status_ragu = $('#rg_' + id_step).val();

  if (status_ragu == 'N') {
    $('#rg_' + id_step).val('Y');
    $('#btn_question_' + id_step).removeClass('btn-success');
    $('#btn_question_' + id_step).addClass('btn-warning');
  } else {
    $('#rg_' + id_step).val('N');
    $('#btn_question_' + id_step).removeClass('btn-warning');
    $('#btn_question_' + id_step).addClass('btn-success');
  }

  cek_status_ragu(id_step);

  Save();
}

function cek_status_ragu(question_id) {
  var status_ragu = $('#rg_' + question_id).val();

  if (status_ragu == 'N') {
    $('.ragu_ragu').html('Doubt');
  } else {
    $('.ragu_ragu').html('No doubt');
  }
}

function last_check(question_id) {
  var total_questions = $('#total_questions').val();
  total_questions = parseInt(total_questions) - 1;

  if (total_questions === question_id) {
    $('.next').hide();
    $('.completed, .back').show();
  } else {
    $('.next').show();
    $('.completed, .back').hide();
  }
}

function saveTemporarily() {
  var f_asal = $('#exam');
  var form = getFormData(f_asal);
  //form = JSON.stringify(form);
  var total_questions = form.total_questions;
  total_questions = parseInt(total_questions);

  var answer_result = '';

  for (var i = 1; i < total_questions; i++) {
    var idx = 'option_' + i;
    var idx2 = 'rg_' + i;
    var answer = form[idx];
    var ragu = form[idx2];

    if (answer != undefined) {
      if (ragu == 'Y') {
        if (answer == '-') {
          answer_result +=
            '<a id="btn_question_' +
            i +
            '" class="btn btn-default btn_question btn-sm" onclick="return open(' +
            i +
            ');">' +
            i +
            '. ' +
            answer +
            '</a>';
        } else {
          answer_result +=
            '<a id="btn_question_' +
            i +
            '" class="btn btn-warning btn_question btn-sm" onclick="return open(' +
            i +
            ');">' +
            i +
            '. ' +
            answer +
            '</a>';
        }
      } else {
        if (answer == '-') {
          answer_result +=
            '<a id="btn_question_' +
            i +
            '" class="btn btn-default btn_question btn-sm" onclick="return open(' +
            i +
            ');">' +
            i +
            '. ' +
            answer +
            '</a>';
        } else {
          answer_result +=
            '<a id="btn_question_' +
            i +
            '" class="btn btn-success btn_question btn-sm" onclick="return open(' +
            i +
            ');">' +
            i +
            '. ' +
            answer +
            '</a>';
        }
      }
    } else {
      answer_result +=
        '<a id="btn_question_' +
        i +
        '" class="btn btn-default btn_question btn-sm" onclick="return open(' +
        i +
        ');">' +
        i +
        '. -</a>';
    }
  }
  $('#display_answer').html('<div id="yes"></div>' + answer_result);
}

function Save() {
  saveTemporarily();
  var form = $('#exam');

  $.ajax({
    type: 'POST',
    url: base_url + 'exam/exam',
    data: form.serialize(),
    dataType: 'json',
    success: function (data) {
      // $('.ajax-loading').show();
      console.log(data);
    },
  });
}

function completed() {
  Save();
  ajaxcsrf();
  $.ajax({
    type: 'POST',
    url: base_url + 'exam/save_final',
    data: { id: id_tes },
    beforeSend: function () {
      Save();
      // $('.ajax-loading').show();
    },
    success: function (r) {
      console.log(r);
      if (r.status) {
        window.location.href = base_url + 'exam/list';
      }
    },
  });
}

function timesUP() {
  completed();
  alert('Exam time is up!');
}

function save_final() {
  Save();
  if (confirm('Are you sure you want to end the test?')) {
    completed();
  }
}
