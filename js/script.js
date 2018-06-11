function centerCss(className){
    $(className).css("background", "#f9b43b");
    $(className).css("position", "fixed");
    $(className).css("top", "40%");
    $(className).css("left", "50%");
    $(className).css("margin-left", "-7.5rem");
    $(className).css("border-radius", ".3125rem");
}

$( "#order-form-button" ).on( "click", function() {
    $.ajax({
        type: "POST",
        url: '/src/send.php',
        data: $("#order-form").serialize(),
        dataType: 'json'
    }).done(function(data) {
        if (data.errors){
            var msg = 'Произошла ошибка<br>';
            $.each( data.errors, function( key, value ) {
                msg += value + '<br>';
            });
            $('#error .error-message').html(msg);
            centerCss($('#error'));
            $('#error').show();
        } else {
            $('#success .status-popup__message').html(data.msg);
            centerCss($('#success'));
            $('#success').show();
        }
    });
    return false;
});

$('.status-popup__close').on( "click", function() {
    $(this).parent().parent().hide();
    return false;
});