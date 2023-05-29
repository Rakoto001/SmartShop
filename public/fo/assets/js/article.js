$(document).ready(function(){

    $(".add-cart-action").click(function () {

        var ajaxcontrollerpath = $(this).attr('ajax-path');
        let id = $(this).attr('article-id');
        let quantity = $('#cart-quantity span').html();
        console.log(typeof(quantity));
        console.log(quantity);

        $.ajax({
            type: 'POST',
            url: ajaxcontrollerpath,
            dataType: 'json',
            data: {"product-quanity": quantity},
        });

        
    });

}
)