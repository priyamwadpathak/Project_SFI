//collapse topics
$(document).ready(function () {
  $('.collapse.in').prev('.panel-heading').addClass('active');
  $('#accordion, #bs-collapse')
    .on('hide.bs.collapse', function (a) {
      $(a.target).prev('.panel-heading').addClass('active');
    })
    .on('show.bs.collapse', function (a) {
      $(a.target).prev('.panel-heading').removeClass('active');
    });
});

//notification panel
function shownotificationFunction() {
    document.getElementById("mydropnotificationdown").classList.toggle("shownotification");
}

// Close the dropnotificationdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropnotification')) {

    var dropnotificationdowns = document.getElementsByClassName("dropnotificationdown-content");
    var i;
    for (i = 0; i < dropnotificationdowns.length; i++) {
      var opendropnotificationdown = dropnotificationdowns[i];
      if (opendropnotificationdown.classList.contains('shownotification')) {
        opendropnotificationdown.classList.remove('shownotification');
      }
    }
  }
};

//dropdown button
$(document).ready(function() {
    $('.dropdown').click(function() {

        var img = $(this).attr("src");
        if(img=="assets/down.png")
            img = "assets/up.svg";
        else
            img = "assets/down.png";
        $(this).attr("src", img);

    });
});
