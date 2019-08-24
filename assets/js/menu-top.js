$(document).ready(function(){
if($(window).scrollTop() > 0) {
    $('.navbar-light').addClass('bgMenu');
}
    $(window).scroll(function () {
        if($(window).scrollTop() > 0){
            $('.navbar-light').addClass('bgMenu');
        }else{
            $('.navbar-light').removeClass('bgMenu');
        }
    });
})