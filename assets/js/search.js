$(document).ready(function(){

    $('.srch-btn').click(function (e) {
        e.preventDefault();
        $('.overlay').css({
            "transform" : "scale(1)",
            "transition" : "1s all"
        });
        });
    $('.real-close').click(function () {

        $('.overlay').css({
            "transform" : "scale(0)",
            "transition" : "1s all"
        });
    });
$('.search-menu').keyup(function(){
    var keywords = $(this).val();
    if(keywords == "")
    {
        $( "#result" ).html("");
    }
    else {
        var request = $.ajax({
            url:"/recherche",
            method:"GET",
            data:{keywords : keywords},
            dataType:'text',
        })

        request.done(function( msg ) {
            $( "#result" ).html( msg );
        });
    }

    });
})