(function (app) {
    app.controller('MainController', ['$rootScope', '$scope', '$state', 'MainService', 'AlertService', 'TransactionService',
        'TransactionPlanService', 'TransactionTypeService',
        function ($rootScope, $scope, $state, MainService, AlertService, TransactionService, TransactionPlanService, TransactionTypeService) {

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

                    $scope.transactionInfo = $scope.history.transactions.reduce(function (total, w) {
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

            $scope.getAllTransactions = function () {
                TransactionService.getUserTransactions(function (response) {
                    $scope.transactions = response.data;
                    TransactionTypeService.getAll(function (resp) { //fail
                        $scope.transactionTypes = resp.data;

                        TransactionPlanService.getAll(function (response) { //fail
                            $scope.transactionPlans = response.data;
                            $scope.depositTransactionType = $scope.transactionTypes.filter(function (plan) {
                                return /deposit/gi.test(plan.name);
                            });
                            $scope.withdrawalTransactionType = $scope.transactionTypes.filter(function (plan) {
                                return /withdraw/gi.test(plan.name);
                            });
                            if ($scope.depositTransactionType.length) {
                                $scope.depositTransactionType = $scope.depositTransactionType[0];
                            }
                            if ($scope.withdrawalTransactionType.length) {
                                $scope.withdrawalTransactionType = $scope.withdrawalTransactionType[0];
                            }
                            $scope.mappedTransactions = $scope.transactions.map(function (t) {
                                var payload = t.payload = JSON.parse(t.payload);
                                var plan;
                                $scope.transactionPlans = $scope.transactionPlans.filter(function (p) {
                                    return p.id == payload.details && payload.details.transaction_plan_id || payload.transaction_plan_id;
                                });
                                plan = $scope.transactionPlans.length && $scope.transactionPlans[0];
                                return Object.assign({
                                    "plan": plan,
                                    "transactionTypeId": payload.details && payload.details.transaction_type_id || payload.transaction_type_id
                                }, t);
                            });
                            $scope.depositDetails = $scope.mappedTransactions.filter(function (t) {
                                return t.transactionTypeId == $scope.depositTransactionType.id;
                            }).reduce(function (result, deposit, index) {
                                result.total += (deposit.amount || deposit.amount_to_pay);
                                result.lastDeposit = (deposit.amount || deposit.amount_to_pay);
                                result.noOfDeposits = index + 1;
                                return result;
                            }, {
                                total: 0,
                                lastDeposit: 0
                            });
                            $scope.withdrawalDetails = $scope.mappedTransactions.filter(function (t) {
                                return t.transactionTypeId == $scope.withdrawalTransactionType.id;
                            }).reduce(function (result, deposit, index) {
                                result.total += (deposit.amount || deposit.amount_to_pay);
                                result.lastWithdrawal = (deposit.amount || deposit.amount_to_pay);
                                result.noOfWithdrawals = index + 1;
                                return result;
                            }, {
                                total: 0,
                                lastWithdrawal: 0
                            });
                        });
                    });
                }, function (response) {
                    console.log(response);
                    AlertService.alertify('an error occurred while trying to generate url for transfer. please try again', 'danger', 'Error');
                });
            };

            $rootScope.convertDate = function (date) {
                return new Date(date).toDateString();
            };
        }
    ]);

    app.service('MainService', ['APIService', 'userURL', function (APIService, userURL) {

        this.getUserTransactions = function (successHandler, errorHandler) {
            APIService.get(userURL + '/transactions', successHandler, errorHandler);
        };
    }]);
})(cryptocoin);