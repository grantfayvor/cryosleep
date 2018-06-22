/**
 * Created by Harrison on 6/15/2018.
 */

(function (app) {
    app.controller('TransactionController', function ($scope, $state, TransactionService, TransactionPlanService, TransactionTypeService,
                                                      AlertService, WithdrawalService, CryptoService) {

        $scope.transaction = {};
        $scope.withdrawal = {};
        $scope.transactions = [];
        $scope.withdrawals = [];

        $scope.initialize = function () {
            $scope.getTransactionPlans();
            $scope.getTransactionTypes();
        };

        $scope.newTransaction = function () {
            TransactionService.create($scope.transaction, function (response) {
                console.log(response);
            }, function (response) {
                console.log(response);
            });
        };

        $scope.getTransactions = function () {
            TransactionService.getAll(function (response) {
                $scope.transactions = response.data;
            }, function (response) {
                console.log(response);
            });
        };

        $scope.getUserTransactions = function () {
            TransactionService.getUserTransactions(function (response) {
                $scope.transactions = response.data;
                $scope.getTransactionTypes(function (resp) {
                    $scope.transactionTypes = resp.data;
                    if ($state.is('view_withdrawals')) {
                        $scope.getTransactionPlans(function (response) {
                            $scope.transactionPlans = response.data;
                            $scope.transactionType = $scope.transactionTypes.filter(function (plan) {
                                return /withdraw/gi.test(plan.name);
                            });
                            if ($scope.transactionType.length) {
                                $scope.transactionType = $scope.transactionType[0];
                            }
                            $scope.transactions = $scope.transactions.map(function (t) {
                                var payload = t.payload = JSON.parse(t.payload);
                                var plan;
                                $scope.transactionPlans.filter(function (p) {
                                    return p.id == payload.details.transaction_plan_id;
                                });
                                plan = $scope.transactionPlans.length && $scope.transactionPlans[0];
                                return Object.assign({
                                    "plan": plan,
                                    "transactionTypeId": payload.details.transaction_type_id
                                }, t);
                            }).filter(function (t) {
                                return t.transactionTypeId == $scope.transactionType.id;
                            });
                            console.log($scope.transactions);
                        });
                    }
                    if ($state.is('view_deposits')) {
                        $scope.getTransactionPlans(function (response) {
                            $scope.transactionPlans = response.data;
                            $scope.transactionType = $scope.transactionTypes.filter(function (plan) {
                                return /deposit/gi.test(plan.name);
                            });
                            if ($scope.transactionType.length) {
                                $scope.transactionType = $scope.transactionType[0];
                            }
                            $scope.transactions = $scope.transactions.map(function (t) {
                                var payload = t.payload = JSON.parse(t.payload);
                                var plan;
                                $scope.transactionPlans.filter(function (p) {
                                    return p.id == payload.details.transaction_plan_id;
                                });
                                plan = $scope.transactionPlans.length && $scope.transactionPlans[0];
                                return Object.assign({
                                    "plan": plan,
                                    "transactionTypeId": payload.details.transaction_type_id
                                }, t);
                            }).filter(function (t) {
                                return t.transactionTypeId == $scope.transactionType.id;
                            });
                        });
                    }
                });
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error occurred while trying to generate url for transfer. please try again', 'danger', 'Error');
            });
        };

        $scope.generatePaymentURL = function () {
            TransactionService.generatePaymentURL($scope.transaction, function (response) {
                $scope.paymentURL = response.data.url;
                AlertService.alertify('payment url successfully generated', 'success', 'Success');
            }, function (response) {
                AlertService.alertify('an error occurred while trying to generate url for transfer. please try again', 'danger', 'Error');
            });
        };

        $scope.createCallbackAddressForTransfer = function () {
            if($scope.transaction.callbackDetails) {
                return;
            }
            TransactionService.createCallbackAddressForTransfer($scope.transaction, function (response) {
                if(response.data.error == "ok") {
                    $scope.transaction.callbackDetails = response.data.result;
                } else {
                    AlertService.alertify('an error occurred while trying to generate address to pay to. Please try again.', 'danger', 'Error');
                }
            }, function (response) {
                AlertService.alertify('an error occurred while generating the callback address', 'danger', 'Error');
            });
        };

        $scope.generateWithdrawalURL = function (request) {
            TransactionService.generateWithdrawalURL({
                'amount_usd': request.amount,
                'address': request.address,
                'currency': request.currency,
                'withdrawal_info_id': request.id
            }, function (response) {
                window.location.href = response.data.url;
            }, function (response) {
                AlertService.alertify('an error occurred while trying to generate the transfer url. please try again later');
            });
        };

        $scope.deleteTransaction = function (transactionId) {
            TransactionService.delete(transactionId, function (response) {
                $scope.transactions = $scope.transactions.filter(function (t) {
                    return t.id != transactionId;
                });
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error occurred while trying to delete the transaction', 'danger', 'Error');
            });
        };

        $scope.getTransactionPlans = function (extendCallback) {
            TransactionPlanService.getAll(extendCallback || function (response) {
                $scope.transactionPlans = response.data;
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error has occurred please try reloading the page.', 'danger', 'Error');
            });
        };

        $scope.getTransactionTypes = function (extendCallback) {
            TransactionTypeService.getAll(extendCallback || function (response) {
                $scope.transactionTypes = response.data;
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error has occurred please try reloading the page.', 'danger', 'Error');
            });
        };

        $scope.getConfirmedTransactions = function () {
            TransactionService.getConfirmedTransactions(function (response) {
                $scope.balance = response.data.reduce(function (total, t) {
                    return total.amount + t.amount;
                });
                if (typeof $scope.balance == "object") {
                    $scope.balance = $scope.balance.amount;
                }
                CryptoService.getUserAddress(function (resp) {
                    $scope.withdrawal.address = resp.data.address;
                }, function (resp) {
                    AlertService.alertify('an error occurred while trying to fetch your registered address', 'danger', 'Error');
                });
            }, function (response) {
                AlertService.alertify('an error occurred while trying to fetch your confirmed transactions', 'danger', 'Error');
            });
        };

        $scope.getBalances = function () {
            TransactionService.getBalances($scope.withdrawal, function (response) {
                if (!/ok/gi.test(response.data.error)) {
                    AlertService.alertify('an error occurred while trying to fetch your current balance', 'danger', 'Error');
                    return;
                }
                $scope.balance = response.data.result;
                $scope.coins = Object.keys($scope.balance);

            }, function (response) {
                AlertService.alertify('an error occurred while trying to fetch your current balance', 'danger', 'Error');
            });
        };

        $scope.setMaxMinAmounts = function () {
            var plan = $scope.transactionPlans.find(function (plan) {
                return plan.id == $scope.transaction.transaction_plan_id;
            });
            $scope.maxAmount = plan.max_amount;
            $scope.minAmount = plan.min_amount;
        };

        $scope.makeWithdrawalRequest = function () {
            WithdrawalService.makeWithdrawalRequest($scope.withdrawal, function (response) {
                AlertService.alertify('Your request for withdrawal was successful. You would be made aware when it has been approved', 'success', 'Success');
            }, function (response) {
                AlertService.alertify('an error occurred while trying to process your request for withdrawal. please try again', 'danger', 'Error');
            });
        };

        $scope.getUnapprovedWithdrawals = function () {
            WithdrawalService.getUnapprovedWithdrawals(function (response) {
                $scope.withdrawals = response.data;
            }, function (response) {
                AlertService.alertify('an error occurred while trying to fetch the unapproved withdrawal requests. please try again', 'danger', 'Error');
            });
        };

        $scope.transferFundsModal = function (plan) {
            $scope.transaction.transaction_plan_id = plan.id;
            $scope.setMaxMinAmounts();
            $('#transferFundsModal').modal('show');
        };

        $scope.showPreviewTransferModal = function () {
            $scope.transaction.plan = $scope.transactionPlans.find(function (plan) {
                return plan.id == $scope.transaction.transaction_plan_id;
            });
            $('#previewTransferModal').modal('show');
        };

        $scope.setBTCAmount = function (amountUsd) {
            $scope.getRates(amountUsd, function (response) {
                $scope.rates = response.data;
                var btc = $scope.rates.coins_accept.find(function (coin) {
                    return /bitcoin/gi.test(coin.name) || /BTC/gi.test(coin.iso);
                });
                $scope.transaction.amount_btc = parseFloat(btc.rate);
                $scope.transaction.callbackDetails = null;
            });
        };

        $scope.getRates = function (amount, optionalCallback) {
            TransactionService.getRates(amount, optionalCallback || function (response) {
                $scope.rates = response.data;
            }, function (response) {
                AlertService.alertify('an error occurred while trying to fetch the rates');
            });
        };

        $scope.deactivatePayNow = function () {
            $scope.paymentURL = null;
        };
    });

    app.service('TransactionService', function (APIService, transactionURL) {

        this.create = function (details, successHandler, errorHandler) {
            APIService.post(transactionURL, details, successHandler, errorHandler);
        };

        this.getById = function (id, successHandler, errorHandler) {
            APIService.get(transactionURL + '/' + id, successHandler, errorHandler);
        };

        this.getAll = function (successHandler, errorHandler) {
            APIService.get(transactionURL, successHandler, errorHandler);
        };

        this.getUserTransactions = function (successHandler, errorHandler) {
            APIService.get(transactionURL + '/find/user', successHandler, errorHandler);
        };

        this.delete = function (id, successHandler, errorHandler) {
            APIService.delete(transactionURL + '/' + id, successHandler, errorHandler);
        };

        this.generatePaymentURL = function (details, successHandler, errorHandler) {
            APIService.post(transactionURL + '/payment/generate_url', details, successHandler, errorHandler);
        };

        this.generateWithdrawalURL = function (details, successHandler, errorHandler) {
            APIService.post(transactionURL + '/withdrawal/generate_url', details, successHandler, errorHandler);
        };

        this.getBalances = function (details, successHandler, errorHandler) {
            APIService.post('/coinpayment/ajax/balances', details, successHandler, errorHandler);
        };

        this.getConfirmedTransactions = function (successHandler, errorHandler) {
            APIService.get(transactionURL + '/user/confirmed', successHandler, errorHandler);
        };

        this.getRates = function (amount, successHandler, errorHandler) {
            APIService.get('/coinpayment/ajax/rates/' + amount, successHandler, errorHandler);
        };

        this.createCallbackAddressForTransfer = function (details, successHandler, errorHandler) {
            APIService.post('/coinpayment/ajax/callback_address', details, successHandler, errorHandler);
        };

    });

    app.service('WithdrawalService', function (APIService, withdrawalURL) {

        this.makeWithdrawalRequest = function (details, successHandler, errorHandler) {
            APIService.post(withdrawalURL, details, successHandler, errorHandler);
        };

        this.getUnapprovedWithdrawals = function (successHandler, errorHandler) {
            APIService.get(withdrawalURL + '/unapproved', successHandler, errorHandler);
        };

        this.approveWtihdrawalRequest = function (requestId, successHandler, errorHandler) {
            APIService.put(withdrawalURL + '/approve/' + requestId, successHandler, errorHandler);
        };
    });
})(cryptocoin);