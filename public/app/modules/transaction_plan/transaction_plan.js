/**
 * Created by Harrison on 6/15/2018.
 */

(function (app) {
    app.controller('TransactionPlanController', function ($scope, TransactionPlanService, AlertService) {

        $scope.transactionPlan = {};
        $scope.transactionPlans = [];

        $scope.newTransactionPlan = function () {
            TransactionPlanService.create($scope.transactionPlan, function (response) {
                console.log(response);
                AlertService.alertify('Transaction Plan was successfully created', 'success', 'Success');
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error occurred while trying to create the transaction plan. please try again.', 'danger', 'Error');
            });
        };

        $scope.getTransactionPlans = function () {
            TransactionPlanService.getAll(function (response) {
                $scope.transactionPlans = response.data;
            }, function (response) {
                console.log(response);
            });
        };

        $scope.deleteTransactionPlan = function (planId) {
            TransactionPlanService.delete(planId, function (response) {
                $scope.transactionPlans = $scope.transactionPlans.filter(function (plan) {
                    return plan.id != planId;
                });
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error occurred while trying to delete the transaction plan', 'danger', 'Error');
            });
        };
    });

    app.service('TransactionPlanService', function (APIService, transactionPlanURL) {

        this.create = function (details, successHandler, errorHandler) {
            APIService.post(transactionPlanURL, details, successHandler, errorHandler);
        };

        this.getById = function (id, successHandler, errorHandler) {
            APIService.get(transactionPlanURL + '/' + id, successHandler, errorHandler);
        };

        this.getAll = function (successHandler, errorHandler) {
            APIService.get(transactionPlanURL, successHandler, errorHandler);
        };

        this.delete = function (id, successHandler, errorHandler) {
            APIService.delete(transactionPlanURL + '/' + id, successHandler, errorHandler);
        };
    });
})(cryptocoin);