require('./bootstrap');
require('jquery');

setTimeout(function() {
    $('.message').fadeOut('fast');
}, 100);

$('.show-transaction-form').on('click', function() {
    id = $(this).data('id')
    formId = '#transaction-form-' + id
    $(formId).toggleClass('show')
})

$('.show-loan-form').on('click', function() {
    id = $(this).data('id')
    formId = '#loan-form-' + id
    $(formId).toggleClass('show')
    $('input[name="loan_id"]').val($(this).data('loanId'))
})

$('.history-btn').on('click', function() {
    id = $(this).data('id')
    historyBody = '#history-body-' + id
    accountBody = '#account-body-' + id
    $(accountBody).toggleClass('hide')
    $(historyBody).toggleClass('show')

    $('input[name="account_id"]').val($(this).data('id'))
})
