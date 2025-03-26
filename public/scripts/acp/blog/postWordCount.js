/**
 * @author brianha289
 * created Date: 26/10/2021
 * project: basic_cms
 */
// start app
$(document).ready(function () {
  // Meta Desc

  var trimmed = $("#word_count").val().substring(0, 300);
  $("#word_count").val(trimmed);
  var words = $("#word_count").val().length;
  $("#word_left").text(300 - words);

  $("#word_count").on("keyup", function () {
    var words = this.value.length;

    if (words > 300) {
      var trimmed = $(this).val().substring(0, 300);
      $(this).val(trimmed);
      $("#word_left").text(0);
    } else {
      $("#word_left").text(300 - words);
    }
  });

  //-----------------------------------------------
  // Meta Title
  //-----------------------------------------------
  var trimmed = $("#title_word_count").val().substring(0, 70);
  $("#title_word_count").val(trimmed);
  var words = $("#title_word_count").val().length;
  $("#title_word_left").text(70 - words);

  $("#title_word_count").on("keyup", function () {
    var words = this.value.length;

    if (words > 70) {
      var trimmed = $(this).val().substring(0, 70);
      $(this).val(trimmed);
      $("#title_word_left").text(0);
    } else {
      $("#title_word_left").text(70 - words);
    }
  });
});
