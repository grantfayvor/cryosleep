(function (app) {
    app.constant('userURL', '/api/user')
        .constant('transactionURL', '/api/transaction')
        .constant('withdrawalURL', '/api/withdrawal')
        .constant('cryptoURL', '/api/crypto')
        .constant('transactionPlanURL', '/api/transaction_plan')
        .constant('transactionTypeURL', '/api/transaction_type');
})(cryptocoin);