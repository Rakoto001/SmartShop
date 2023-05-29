$(document).ready(function(){

    $(".add-cart-action").click(function () {

        var ajaxcontrollerpath = $(this).attr('ajax-path');
        let id = $(this).attr('article-id');
        let quantity = $('#cart-quantity span').html();
       

        $.ajax({
            type: 'POST',
            url: ajaxcontrollerpath,
            dataType: 'json',
            data: {"product-quanity": quantity},
        });

        // réinitialiser la valeur de la quantity
        $('#cart-quantity span').html('1');

        
    });

}
)