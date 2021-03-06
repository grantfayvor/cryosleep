var cryptocoin = angular.module('app.crypto', ['ui.router']);

(function (app) {
    app.config(['$httpProvider', '$stateProvider', '$urlRouterProvider', '$logProvider', '$interpolateProvider',
        function ($httpProvider, $stateProvider, $urlRouterProvider, $logProvider, $interpolateProvider) {

            $interpolateProvider.startSymbol('<%').endSymbol('%>');
            $logProvider.debugEnabled(true);

            $httpProvider.defaults.headers.common.Accept = "application/json";
            $httpProvider.defaults.headers.common['Content-Type'] = "application/json";
            $httpProvider.interceptors.push('httpInterceptor');

            var confirmedAddress = window.sessionStorage.getItem("confirmed_address");
            if (!confirmedAddress ||  confirmedAddress == 'false') {
                $urlRouterProvider.otherwise('/new_crypto_account');
                $urlRouterProvider.when('#!/', 'new_crypto_account');
            } else {
                $urlRouterProvider.otherwise('/');
                $urlRouterProvider.when('#!/', 'dashboard');
            }

            $stateProvider
                .state('dashboard', {
                    url: '/',
                    templateUrl: '/app/home.html',
                    controller: 'MainController'
                })
                .state('new_crypto_account', {
                    url: '/new_crypto_account',
                    templateUrl: '/app/modules/crypto/new_crypto_account.html',
                    controller: 'CryptoController'
                })
                .state('view_crypto_accounts', {
                    url: '/view_crypto_accounts',
                    templateUrl: '/app/modules/crypto/view_crypto_accounts.html',
                    controller: 'CryptoController'
                })
                .state('new_deposit', {
                    url: '/new_deposit',
                    templateUrl: '/app/modules/transaction/new_transaction.html',
                    controller: 'TransactionController'
                })
                .state('view_deposits', {
                    url: '/view_deposits',
                    templateUrl: '/app/modules/transaction/view_transactions.html',
                    controller: 'TransactionController'
                })
                .state('withdrawal_request', {
                    url: '/withdrawal_request',
                    templateUrl: '/app/modules/transaction/withdrawal_request.html',
                    controller: 'TransactionController'
                })
                .state('approve_withdrawal', {
                    url: '/approve_withdrawal',
                    templateUrl: '/app/modules/transaction/approve_withdrawal.html',
                    controller: 'TransactionController'
                })
                .state('view_withdrawals', {
                    url: '/view_withdrawals',
                    templateUrl: '/app/modules/transaction/view_withdrawals.html',
                    controller: 'TransactionController'
                })
                .state('new_transaction_plan', {
                    url: '/new_transaction_plan',
                    templateUrl: '/app/modules/transaction_plan/new_transaction_plan.html',
                    controller: 'TransactionPlanController'
                })
                .state('view_transaction_plans', {
                    url: '/view_transaction_plans',
                    templateUrl: '/app/modules/transaction_plan/view_transaction_plans.html',
                    controller: 'TransactionPlanController'
                })
                .state('new_transaction_type', {
                    url: '/new_transaction_type',
                    templateUrl: '/app/modules/transaction_type/new_transaction_type.html',
                    controller: 'TransactionTypeController'
                })
                .state('view_transaction_types', {
                    url: '/view_transaction_types',
                    templateUrl: '/app/modules/transaction_type/view_transaction_types.html',
                    controller: 'TransactionTypeController'
                })
                .state('view_users', {
                    url: '/view_users',
                    templateUrl: '/app/modules/user/view_users.html',
                    controller: 'UserController'
                });

        }
    ]);
})(cryptocoin);