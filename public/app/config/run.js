(function (app) {
    app.run(function ($http) {

        $http.get('/api/crypto/address/confirm')
            .then(function (response) {
                window.sessionStorage.setItem("confirmed_address", response.data === true);
            }, function (response) {
                console.log(response);
            });
    });
})(cryptocoin);