app.factory("Data", ['$http', '$location',
    function ($http, $q, $location) {

        var serviceBase = 'api/v1/';
        var _serviceBase = 'api/v2/public/'

        var obj = {};

        obj.getToken = function(q){
            return $http.get(_serviceBase + q).then(function (results) {
                return results.data;
            });
        };

        obj.get = function (q) {
            return $http.get(_serviceBase + q).then(function (results) {
                return results.data;
            });
        };
        obj.post = function (q, object) {
            return $http.post(_serviceBase + q, object).then(function (results) {
                return results.data;
            });
        };
        obj.put = function (q, object) {
            return $http.put(_serviceBase + q, object).then(function (results) {
                console.log(results);
                return results.data;
            });
        };
        obj.delete = function (q) {
            return $http.delete(_serviceBase + q).then(function (results) {
                return results.data;
            });
        };
        return obj;
}]);
