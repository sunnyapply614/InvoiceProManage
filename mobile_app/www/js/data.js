app.factory("Data", ['$http', '$location',
    function ($http, $q, $location) {

        var _serviceBase = 'http://therealcarlos.com/extra/product-manager/api/v2/public/'

        var _upc_token = "973F620C-0DCC-445B-B0F0-27A80D2550C0"

        var _upc_base = "http://www.searchupc.com/handlers/upcsearch.ashx?request_type=3&access_token="+_upc_token+"&upc="

        var obj = {};


        obj.getUPCdata = function(q){
            console.log("URL", _upc_base+q);
            return $http.get(_upc_base + q).then(function (results) {
                console.log("R", results);
                return results; 
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
