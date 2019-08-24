$(document).ready(function () {
   $('.js-like').click(function (e) {
       e.preventDefault();

       const url = this.href;
       const icon = $(this).children('i');

       var request = $.ajax({
           url:url,
           method:"GET",
           dataType:'json',
       })

       request.done(function( msg ) {
           $('.js-likes').html(msg.likes);
            if(icon.hasClass('fas')){
                icon.removeClass('fas');
                icon.addClass('far');
            }else{
                icon.removeClass('far');
                icon.addClass('fas');
            }
       });
   });
});