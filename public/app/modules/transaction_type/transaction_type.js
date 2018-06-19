/**
 * Created by Harrison on 6/15/2018.
 */

(function (app) {
    app.controller('TransactionTypeController', function ($scope, TransactionTypeService, AlertService) {

        $scope.transactionType = {};
        $scope.transactionTypes = [];

        $scope.newTransactionType = function () {
            TransactionTypeService.create($scope.transactionType, function (response) {
                console.log(response);
                AlertService.alertify('Transaction Type was successfully created', 'success', 'Success');
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error occurred while trying to create the transaction type. please try again.', 'danger', 'Error');
            });
        };

        $scope.getTransactionTypes = function () {
            TransactionTypeService.getAll(function (response) {
                $scope.transactionTypes = response.data;
            }, function (response) {
                console.log(response);
            });
        };

        $scope.deleteTransactionType = function (typeId) {
            TransactionTypeService.delete(typeId, function (response) {
                $scope.transactionTypes = $scope.transactionTypes.filter(function (type) {
                    return type.id != typeId;
                });
            }, function (response) {
                console.log(response);
                AlertService.alertify('an error occured while trying to delete the transaction type', 'danger', 'Error');
            });
        };
    });

    app.service('TransactionTypeService', function (APIService, transactionTypeURL) {

        this.create = function (details, successHandler, errorHandler) {
            APIService.post(transactionTypeURL, details, successHandler, errorHandler);
        };

        this.getById = function (id, successHandler, errorHandler) {
            APIService.get(transactionTypeURL + '/' + id, successHandler, errorHandler);
        };

        this.getAll = function (successHandler, errorHandler) {
            APIService.get(transactionTypeURL, successHandler, errorHandler);
        };

        this.delete = function (id, successHandler, errorHandler) {
            APIService.delete(transactionTypeURL + '/' + id, successHandler, errorHandler);
        };
    });
})(cryptocoin);