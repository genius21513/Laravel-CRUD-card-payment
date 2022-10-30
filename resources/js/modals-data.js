$('.send-account-id-button').on('click', function() {
    $('input[name="account_id"]').val($(this).data('id'))
})
$('.send-card-id-button').on('click', function() {
    $('input[name="card_id"]').val($(this).data('id'))
})


