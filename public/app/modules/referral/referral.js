(function (app) {

    app.controller('ReferralController', function ($scope, ReferralService, AlertService) {

        $scope.referrals = [];
        $scope.referralLink = document.getElementById('referralCode').value;

        $scope.getReferrals = function () {
            var referralCode = document.getElementById('hiddenReferral').value;
            ReferralService.getReferrals(referralCode, function (response) {
                $scope.referrals = response.data;
            }, function (response) {
                AlertService.alertify('an error occurred while fetching your referrals. please reload this page', 'danger', 'Error');
            });
        };
    });

    app.service('ReferralService', function (APIService) {

        this.getReferrals = function (referralCode, successHandler, errorHandler) {
            APIService.get('/api/referrals/' + referralCode, successHandler, errorHandler);
        };
    });
})(cryptocoin);