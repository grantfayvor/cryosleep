/**
 * Created by Harrison on 6/15/2018.
 */

(function (app) {
    app.controller('UserController', function ($scope, UserService, AlertService, TransactionService) {

        $scope.users = [];
        $scope.user = {};
        $scope.roles = [];

        $scope.getAllUsers = function () {
            UserService.getAllUsers(function (response) {
                $scope.users = response.data;
                var htmlString = "";
                setTimeout(function () {
                    $scope.users.forEach(function (user, index) {
                        if (user.auto_withdraw === true || user.auto_withdraw === 1) {
                            htmlString = "<input type='checkbox' checked name='auto_withdraw' id='auto_withdraw" + index + "' class='cbx hidden' data-ng-change='enableAutoWithdraw(user, " + index + ")' class='form-control' data-ng-model='autoWithdraw'>" +
                                "<label for='auto_withdraw" + index + "' class='lbl'></label>";
                        } else {
                            htmlString = "<input type='checkbox' name='auto_withdraw' id='auto_withdraw" + index + "' class='cbx hidden' data-ng-change='enableAutoWithdraw(user, " + index + ")' class='form-control' data-ng-model='autoWithdraw'>" +
                                "<label for='auto_withdraw" + index + "' class='lbl'></label>";
                        }
                        $('#autw' + index).html(htmlString);
                    });
                });
            }, function (response) {
                AlertService.alertify('an error occurred while trying to fetch the users. please reload this page', 'danger', 'Error');
            });
        };

        $scope.deleteUser = function (userId) {
            UserService.deleteUser(userId, function (response) {
                $scope.users = $scope.users.filter(function (u) {
                    return u.id != userId;
                });
                AlertService.alertify('the user was successfully deleted', 'success', 'Success');
            }, function (response) {
                AlertService.alertify('an error occurred while trying to delete the user. please try again', 'danger', 'Error');
            });
        };

        $scope.editUser = function (user) {
            $scope.user = user;

            UserService.getRoles(function (response) {
                $scope.roles = response.data;
                UserService.getUserRoles($scope.user.id, function (resp) {
                    $scope.user.roleName = resp.data;
                    $scope.user.role = $scope.roles.filter(function (role) {
                        return role.name == $scope.user.roleName;
                    })[0];
                    $('#roleModal').modal('show');
                    $scope.user.previousRole = $scope.roles.filter(function (role) {
                        return role.name == $scope.user.roleName;
                    })[0];
                }, function (resp) {
                    AlertService.alertify('an error occurred while trying to fetch the user role', 'danger', 'Error');
                });
            }, function (response) {
                AlertService.alertify('an error occurred while trying to fetch the roles', 'danger', 'Error');
            });
        };

        $scope.updateUser = function () {
            UserService.updateUser({
                fullName: $scope.user.full_name,
                email: $scope.user.email,
                role: $scope.user.role,
                previousRole: $scope.user.previousRole,
                userId: $scope.user.id
            }, function (response) {
                $('#roleModal').modal('hide');
                AlertService.alertify('the user was successfully updated', 'success', 'Success');
            }, function (response) {
                $('#roleModal').modal('hide');
                AlertService.alertify('an error occurred while trying to update the user', 'danger', 'Error');
            });
        };

        $scope.enableAutoWithdraw = function (user, index) {
            UserService.enableAutoWithdraw({
                userId: user.id,
                autoWithdraw: document.getElementById('auto_withdraw' + index).classList.contains('ng-empty') ? true : false
            }, function (response) {
                AlertService.alertify('the user\'s auto-withdraw status was successfully updated', 'success', 'Success');
            }, function (response) {
                AlertService.alertify('an error occurred while trying to update the auto-withdraw status. please try again', 'danger', 'Error');
            });
        };

        $scope.showPaymentModal = function (user) {
            $scope.payment = {};
            UserService.getAddressForUser(user.id, function (response) {
                if (!response.data.address) {
                    AlertService.alertify("the user's address could not be located", "danger", "Error");
                    return;
                }
                $scope.payment.userAddress = response.data.address;
                $scope.payment.currency = "BTC";
                $('#payModal').modal('show');
            }, function (response) {
                AlertService.alertify("an error occurred while fetching the user address", "danger", "Error");
            });
        };

        $scope.generateWithdrawalURL = function () {
            if (!$scope.payment.userAddress || !$scope.payment.amount) {
                AlertService.alertify("please input correct details", "danger", "Error");
                return;
            }
            TransactionService.generateWithdrawalURL({
                'amount_usd': $scope.payment.amount,
                'address': $scope.payment.userAddress,
                'currency': $scope.payment.currency
            }, function (response) {
                window.location.href = response.data.url;
            }, function (response) {
                AlertService.alertify('an error occurred while trying to generate the transfer url. please try again later', 'danger', 'Error');
            });
        };
    });

    app.service('UserService', function (APIService, userURL) {

        this.getAllUsers = function (successHandler, errorHandler) {
            APIService.get(userURL, successHandler, errorHandler);
        };

        this.deleteUser = function (userId, successHandler, errorHandler) {
            APIService.delete(userURL + '/' + userId, successHandler, errorHandler);
        };

        this.getRoles = function (successHandler, errorHandler) {
            APIService.get('/api/role-with-claims', successHandler, errorHandler);
        };

        this.getUserRoles = function (userId, successHandler, errorHandler) {
            APIService.get(userURL + '/roles/' + userId, successHandler, errorHandler);
        };

        this.updateUser = function (details, successHandler, errorHandler) {
            APIService.put(userURL + '/update', details, successHandler, errorHandler);
        };

        this.enableAutoWithdraw = function (details, successHandler, errorHandler) {
            APIService.put(userURL + '/withdraw/auto', details, successHandler, errorHandler);
        };

        this.getAddressForUser = function (userId, successHandler, errorHandler) {
            APIService.get('/api/crypto/address/get_selected?userId=' + userId, successHandler, errorHandler);
        };
    });
})(cryptocoin);