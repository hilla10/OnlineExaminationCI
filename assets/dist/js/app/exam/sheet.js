$(document).ready(function () {
  let t = $('.remainingTime');
  if (t.length) {
    remainingTime(t.data('time'));
  }

  open(1); // Start with the first widget
  saveTemporarily();

  widget = $('.step');
  btnnext = $('.next');
  btnback = $('.back');
  btnsubmit = $('.submit');

  $('.step, .back, .completed').hide();
  $('#widget_1').show();
  $('#question_number').html(1);
  $('.next').attr('rel', 2);
  $('.back').attr('rel', 0); // Start with 0 to hide the back button
});

function getFormData($form) {
  let unindexed_array = $form.serializeArray();
  let indexed_array = {};
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

  // Check if the widget exists before showing
  if ($('#widget_' + id_widget).length) {
    $('.step').hide();
    $('#widget_' + id_widget).show();

    // Show or hide back button
    if (id_widget > 1) {
      $('.back').show();
    } else {
      $('.back').hide();
    }
  }

  Save();
}

function next() {
  let Next = parseInt($('.next').attr('rel'));

  // Ensure Next does not exceed the total number of widgets
  Next = Next > total_widget ? total_widget : Next;

  $('#question_number').html(Next);

  // Update the next and back button attributes
  $('.next').attr('rel', Next + 1 <= total_widget ? Next + 1 : total_widget);
  $('.back').attr('rel', Next - 1 >= 1 ? Next - 1 : 1);
  $('.ragu_ragu').attr('rel', Next);
  cek_status_ragu(Next);
  last_check(Next);

  // Check if the widget exists before showing
  if ($('#widget_' + Next).length) {
    $('.step').hide();
    $('#widget_' + Next).show();

    // Show or hide back button
    if (Next > 1) {
      $('.back').show();
    } else {
      $('.back').hide();
    }
  }

  Save();
}

function back() {
  let Back = parseInt($('.back').attr('rel'));

  // Ensure Back does not go below 1
  Back = Back < 1 ? 1 : Back;

  $('#question_number').html(Back);

  // Update the next and back button attributes
  $('.back').attr('rel', Back - 1 >= 1 ? Back - 1 : 1);
  $('.next').attr('rel', Back + 1);
  $('.ragu_ragu').attr('rel', Back);
  cek_status_ragu(Back);
  last_check(Back);

  // Check if the widget exists before showing
  if ($('#widget_' + Back).length) {
    $('.step').hide();
    $('#widget_' + Back).show();

    // Show or hide back button
    if (Back > 1) {
      $('.back').show();
    } else {
      $('.back').hide();
    }
  }

  Save();
}

function no_answer() {
  let id_step = $('.ragu_ragu').attr('rel');
  let status_ragu = $('#rg_' + id_step).val();

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
  let status_ragu = $('#rg_' + question_id).val();

  if (status_ragu == 'N') {
    $('.ragu_ragu').html('Doubt');
  } else {
    $('.ragu_ragu').html('No doubt');
  }
}

function last_check(question_id) {
  let total_questions = $('#total_questions').val();
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
  let f_asal = $('#exam');
  let form = getFormData(f_asal);
  let total_questions = form.total_questions;
  total_questions = parseInt(total_questions);

  let answer_result = '';

  for (let i = 1; i < total_questions; i++) {
    // Changed loop to include last question
    let idx = 'option_' + i;
    let idx2 = 'rg_' + i;
    let answer = form[idx];
    let ragu = form[idx2];

    if (answer != undefined) {
      if (ragu == 'Y') {
        if (answer == '-') {
          answer_result +=
            '<a id="btn_question_' +
            i +
            '" class="btn btn-default btn_question btn-sm" data-id="' +
            i +
            '">' +
            i +
            '. ' +
            answer +
            '</a>';
        } else {
          answer_result +=
            '<a id="btn_question_' +
            i +
            '" class="btn btn-warning btn_question btn-sm" data-id="' +
            i +
            '">' +
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
            '" class="btn btn-default btn_question btn-sm" data-id="' +
            i +
            '">' +
            i +
            '. ' +
            answer +
            '</a>';
        } else {
          answer_result +=
            '<a id="btn_question_' +
            i +
            '" class="btn btn-success btn_question btn-sm" data-id="' +
            i +
            '">' +
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
        '" class="btn btn-default btn_question btn-sm" data-id="' +
        i +
        '">' +
        i +
        '. -</a>';
    }
  }
  $('#display_answer').html('<div id="yes"></div>' + answer_result);

  // Bind click event to dynamically created buttons using event delegation
  $('#display_answer').on('click', '.btn_question', function () {
    let id = $(this).data('id');
    open(id);
  });
}

function Save() {
  saveTemporarily();
  let form = $('#exam');

  $.ajax({
    type: 'POST',
    url: base_url + 'exam/exam',
    data: form.serialize(),
    dataType: 'json',
    success: function (data) {
      console.log(data); // Debugging
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
    },
    success: function (r) {
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
