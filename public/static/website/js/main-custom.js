$('.navslide-position .btn-slide-tram').on('click', function(e){
    $('.navslide-position .btn-slide-tram').removeClass('active');
    $(this).addClass('active');
});


$('a.nav-circulo .circulo').on('click', function(e){
    $('a.nav-circulo .circulo').removeClass('active');
    $(this).addClass('active');
});


$(function() {
    $('.lazy').lazy();
});

$('.menufaqs .faqs-menu-item').on('click', function(e){
    e.preventDefault();
    $('.faqs-menu-item').removeClass('active');
    $(this).addClass('active');
});

// $('.main-menu ul li a').first().addClass('active');

$('.main-menu ul li a').on('click', function(e){
    $('.main-menu ul li a').removeClass('active');
    $(this).addClass('active');
});

/*$('.btn-colapse').on('click', function(evt){
    $btn = $(this);
    $card_destinos = $btn.closest(".overlay");

    if(!$card_destinos.hasClass("scroll-auto-destino"))
    {
      $card_destinos.addClass('scroll-auto-destino');
    }
    else
    {
      $card_destinos.removeClass('scroll-auto-destino');
    }
});*/

if(document.querySelectorAll(".owl_custom").length)
{
  var $elem = $(".owl_custom");

  $elem.each(function(index, element){
    var $current = $(element);

    var config = {
      loop: $current.attr("data-loop") === "true",
      autoplay: $current.attr("data-autoplay") === "true",
      autoplayTimeout: $current.attr("data-autoplay-timeout") ? parseInt($current.attr("data-autoplay-timeout"), 10) : 5999,
      autoplaySpeed: $current.attr("data-autoplay-speed") ? parseInt($current.attr("data-autoplay-speed"), 10) : false,
      autoplayHoverPause: $current.attr("data-autoplay-hover-pause") === "true",
      margin: $current.attr("data-margin") ? parseInt($current.attr("data-margin"), 10) : 0,
      stagePadding: $current.attr("data-stage-padding") ? parseInt($current.attr("data-stage-padding"), 10) : 0,
      animateIn: $current.attr("data-animate-in") || "",
      animateOut: $current.attr("data-animate-out") || "",
      nav: $current.attr("data-nav") === "true",
      dots: $current.attr("data-dots") === "true",
      mobileFirst: true,
      mouseDrag: true,
      touchDrag:true,

      URLhashListener : $current.attr("data-url-hash-listener") === "true",
      startPosition : $current.attr("data-start-position") || "",
    };

    var oaux = new Object();

    var iaux = $current.attr("data-items") ? parseInt($current.attr("data-items"), 10) : 1;

    if(iaux > 0)
    {
      oaux["0"] = {
        "items":iaux,
      };
    }

    iaux = $current.attr("data-items-sm") ? parseInt($current.attr("data-items-sm"), 10) : iaux;

    if(iaux > 0)
    {
      oaux["575"] = {
        "items":iaux,
      };
    }

    iaux = $current.attr("data-items-md") ? parseInt($current.attr("data-items-md"), 10) : iaux;

    if(iaux > 0)
    {
      oaux["767"] = {
        "items":iaux,
      };
    }

    iaux = $current.attr("data-items-lg") ? parseInt($current.attr("data-items-lg"), 10) : iaux;

    if(iaux > 0)
    {
      oaux["991"] = {
        "items":iaux,
      };
    }

    config.responsive = oaux;

    var cPrevHtml = $current.attr("data-prev-html") || "";
    var cNextHtml = $current.attr("data-next-html") || "";

    if(config.nav && cPrevHtml.length && cNextHtml.length)
    {
      config.navText = [cPrevHtml, cNextHtml];
    }

    var owl = $current.owlCarousel(config);

    $(".lazy").lazy();

    owl.on("changed.owl.carousel", function(e){
      $(".lazy").lazy();
    });

    owl.on("dragged.owl.carousel", function(e){
      owl.trigger("stop.owl.autoplay");
    });

    owl.on("click", ".owl-prev, .owl-next, .owl-dot", function(e){
      owl.trigger("stop.owl.autoplay");
    });
  });
}

$(document).ready(function(){  
    
})