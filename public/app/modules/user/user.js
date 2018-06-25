/**
 * Created by Harrison on 6/15/2018.
 */

(function (app) {
    app.controller('UserController', function ($scope, UserService, AlertService) {

        $scope.users = [];
        $scope.user = {};
        $scope.roles = [];

        $scope.getAllUsers = function() {
            UserService.getAllUsers(function(response) {
                $scope.users = response.data;
            }, function (response) {
                AlertService.alertify('an error occurred while trying to fetch the users. please reload this page', 'danger', 'Error');
            });
        };

        $scope.deleteUser = function(userId) {
            UserService.deleteUser(userId, function(response) {
                $scope.users = $scope.users.filter(function(u) {
                    return u.id != userId;
                });
                AlertService.alertify('the user was successfully deleted', 'success', 'Success');
            }, function (response) {
                AlertService.alertify('an error occurred while trying to delete the user. please try again', 'danger', 'Error');
            });
        };

        $scope.editUser = function (user) {
            $scope.user = user;
            UserService.getRoles(function(response) {
                $scope.roles = response.data;
                UserService.getUserRoles($scope.user.id, function (resp) {
                    $scope.user.roleName = resp.data;
                    $scope.user.role = $scope.roles.filter(function(role) {
                        return role.name == $scope.user.roleName;
                    })[0];
                    $('#roleModal').modal('show');
                    $scope.user.previousRole  = $scope.roles.filter(function(role) {
                        return role.name == $scope.user.roleName;
                    })[0];
                }, function(resp) {
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
    });

    app.service('UserService', function (APIService, userURL) {

        this.getAllUsers = function(successHandler, errorHandler) {
            APIService.get(userURL, successHandler, errorHandler);
        };

        this.deleteUser = function (userId, successHandler, errorHandler) {
            APIService.delete(userURL + '/' + userId, successHandler, errorHandler);
        };

        this.getRoles = function(successHandler, errorHandler) {
            APIService.get('/api/role-with-claims', successHandler, errorHandler);
        };

        this.getUserRoles = function(userId, successHandler, errorHandler) {
            APIService.get(userURL + '/roles/' + userId, successHandler, errorHandler);
        };

        this.updateUser = function (details, successHandler, errorHandler) {
            APIService.put(userURL + '/update', details, successHandler, errorHandler);
        };
    });
})(cryptocoin);