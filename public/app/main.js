(function (app) {
    app.controller('MainController', ['$rootScope', '$scope', '$state', 'MainService', 'AlertService',
        function ($rootScope, $scope, $state, MainService, AlertService) {

            $scope.history = {};

            $scope.getUserTransactions = function () {
                MainService.getUserTransactions(function (response) {
                    $scope.history = response.data;
                    $scope.withdrawalInfo = $scope.history.withdrawals.reduce(function (total, w) {
                        var approvedTotal, unApprovedTotal, lastWithdrawal;
                        if (w.approved) {
                            lastWithdrawal = w.amount;
                            approvedTotal = (total.approvedTotal || 0) + w.amount;
                        } else {
                            unApprovedTotal = (total.unApprovedTotal || 0) + w.amount;
                        }
                        return {
                            approvedTotal: approvedTotal,
                            unApprovedTotal: unApprovedTotal || 0,
                            lastWithdrawal: lastWithdrawal || 0
                        };
                    }, {
                        "amount": 0,
                        "currency": "BTC",
                        "approved": false
                    });

                    console.log($scope.withdrawalInfo);

                    $scope.depositInfo = $scope.history.transactions.reduce(function (total, w) {
                        var totalAmount = (total.totalAmount || 0) + w.amount;
                        //var payload = JSON.parse(w.payload);
                        var activeDeposit = new Date(w.expired) > new Date() && !w.confirmation_at ? w.amount : total.activeDeposit || null;

                        return {
                            totalAmount: totalAmount,
                            lastDeposit: w.amount,
                            activeDeposit: activeDeposit
                        };
                    }, {
                        "expired": new Date("2018-06-18 05:15:26"),
                        "confirmation_at": null,
                        "amount": 0
                    });
                }, function (response) {
                    AlertService.alertify('an error occurred while trying to fetch your transactions. please reload this page', 'danger', 'Error');
                });
            };
        }]);

    app.service('MainService', ['APIService', 'userURL', function (APIService, userURL) {

        this.getUserTransactions = function (successHandler, errorHandler) {
            APIService.get(userURL + '/transactions', successHandler, errorHandler);
        };
    }]);
})(cryptocoin);