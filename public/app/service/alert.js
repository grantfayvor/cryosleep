(function (app) {
    app.factory('AlertService', function () {

        var alert = function (message, type, header) {
            console.log("about to alertify");
            var id = "alert";
            var div = document.getElementById(id);
            if (!div) {
                div = document.createElement("div");
                div.setAttribute("id", "alert");
            }
            div.innerHTML = "";
            div.style = {
                position: "top-right",
                animationIn: "bounceInDown",
                animationOut: "fadeOut"
            };
            div.classList.add("callout", "callout-" + type);
            if (header) {
                var h4 = document.createElement("h4");
                h4.innerHTML = header;
                div.appendChild(h4);
            }
            var p = document.createElement("p");
            p.innerHTML = message;
            div.appendChild(p);
            var placeholder = document.getElementsByClassName('content-header')[0];
            placeholder.appendChild(div);

            setTimeout(function () {
                div.innerHTML = "";
                div.classList.remove("callout", "callout-" + type);
            }, 10000);
        };

        return {
            alertify: alert
        };
    });
})(cryptocoin);