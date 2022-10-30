$( ".recipient-card" ).change(function() {
    const cardNumber = $(this).val()
   $.ajax({
    method: "get",
    url: 'transaction/get-bank',
    data: {'cardNumber' : cardNumber }
    })
    .done(function( bank ) {
        $(".bank").text(bank)
    })
    .fail(function (data){
        $(".bank").text('niestety wystąpił błąd serwera :(')
    });
  });
