$(document).ready(function() {
  $("#peopleForm").submit(function(event) {
    var form = $(this);
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "http://localhost:8080/api/cars",
      data: form.serialize(), // serializes the form's elements.
      success: function(data) {
        window.location.replace("http://localhost:8080/api");
      }
    });
  });
  $("#carsEditForm").submit(function(event) {
    alert( "TODO: build submit handler.  See peopleForm submit handler for inspiration " );

  });
  //$( ".deletebtn" ).click(function() {
  //alert( "TODO: build delete handler with confirmation dialog See here for confirmation details:  https://developer.mozilla.org/en-US/docs/Web/API/Window/confirm" );
//});
$(".deletebtn").click(function() {
  var delButton = $(this).attr("data-id");
  if (window.confirm("Are you sure?")){
    $.ajax({
      type:"DELETE",
      url: "http://localhost:8080/api/cars/" + delButton,
      success: function (data) {
        window.location.reload();
    }
  });
}
});
});
