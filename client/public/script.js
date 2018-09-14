$(document).ready(function() {
  $("#carsForm").submit(function(event) {
    var form = $(this);
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "http://localhost:8080/api/cars",
      data: form.serialize(), // serializes the form's elements.
      success: function(data) {
        window.location.replace("http://localhost:8080/client");
      }
    });
  });
  $("#carsEditForm").submit(function(event) {
    alert( "TODO: build submit handler.  See peopleForm submit handler for inspiration " );

  });
  $("#carsEditForm").submit(function(event) {
    var form = $(this);
    var carsID = $(this).attr("data-id");
    event.preventDefault();
    $.ajax({
      type: "PUT",
      url: "http://localhost:8080/api/cars/" + carsID,
      data: form.serialize(),
      success: function(data) {
        window.location.replace("http://localhost:8080/client");
      }
    });
  });
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
