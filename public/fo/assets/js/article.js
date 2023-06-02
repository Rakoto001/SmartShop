$(document).ready(function(){

    


    /** pour l'ajout de l'article */
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





    /** pour la modification */

    $('.myInput').on('input', function() {

        
        // var inputValue = $(".myInput").val();
        // console.log(inputValue);

        $('input[id^="txtAnswer"]').each(function(input){
            var value = $(this).val();
            var id = $(this).attr('id');
            alert('id: ' + id + ' value:' + value);
        });

        
      });





      
  /** pour la suppression */

  $('.delete-one-larticle').click(function(){

    ajaxdeletepath = $(this).attr('url-ajax-delete');
    id = $(this).attr('id-article');

    $('#results-list-' + id).hide()


    $.ajax({
        type: 'POST',
        url: ajaxdeletepath,
        dataType: 'json',
        data: {'id': id},
    })
  });

}
)