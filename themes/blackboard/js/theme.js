$(function () {
  setNavigation();

  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });
    
});

function setNavigation() {
    var path = window.location.pathname;
    //path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $(".nav a").each(function () {
        var href = $(this).attr('href');
        if (path == href) {
            $(this).closest('li').addClass('active');
        }
    });
}