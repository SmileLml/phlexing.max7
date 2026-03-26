$("#submitBtn").click(function(event)
{
  event.preventDefault();

  var form = $("#unimportedCoursesForm");

  $(this).prop("disabled", true);

  form.submit();
});
