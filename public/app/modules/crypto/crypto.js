/**
 * Created by Harrison on 6/15/2018.
 */

(function (app) {
    app.controller('CryptoController', function ($scope, $state, CryptoService, AlertService) {

        var confirmedAddress = window.sessionStorage.getItem("confirmed_address");
        console.log('the confirmed address in crypto controller is ' + confirmedAddress);
        // if ($state.is('new_crypto_account') && (confirmedAddress == true || confirmedAddress == 'true')) {
        //     $state.go('dashboard');
        // }
        $scope.crypto = {};
        $scope.cryptoAccounts = [];

        $scope.getUserAddress = function() {
            CryptoService.getUserAddress(function (response) {
                $scope.crypto.address = response.data.address;
                $scope.changeAddress = $scope.crypto.address ? false : true;
            }, function (response) {
                AlertService.alertify(response.data && response.data.message, "danger");
            });
        };

        $scope.registerAddress = function () {
            CryptoService.create($scope.crypto, function (response) {
                $('#passwordModal').modal('hide');
                console.log(response);
                AlertService.alertify(response.data && response.data.message, "info");
                window.sessionStorage.setItem("confirmed_address", true);
                $state.go('dashboard');
            }, function (response) {
                console.log("response");
                AlertService.alertify(response.data && response.data.message, "danger");
            });
        };

        $scope.getAllAccounts = function () {
            CryptoService.getAll(function (response) {
                $scope.cryptoAccounts = response.data;
            }, function (response) {
                console.log(response);
            });
        };

        $scope.openPasswordModal = function () {
            $('#passwordModal').modal('show');
        };

    });

    app.service('CryptoService', function (APIService, cryptoURL) {

        this.create = function (details, successHandler, errorHandler) {
            APIService.post(cryptoURL, details, successHandler, errorHandler);
        };

        this.getById = function (id, successHandler, errorHandler) {
            APIService.get(cryptoURL + '/' + id, successHandler, errorHandler);
        };

        this.getAll = function (successHandler, errorHandler) {
            APIService.get(cryptoURL, successHandler, errorHandler);
        };

        this.delete = function (id, successHandler, errorHandler) {
            APIService.delete(cryptoURL + '/' + id, successHandler, errorHandler);
        };

        this.getUserAddress = function (successHandler, errorHandler) {
            APIService.get(cryptoURL + '/address/user', successHandler, errorHandler);
        };
    });
})(cryptocoin);