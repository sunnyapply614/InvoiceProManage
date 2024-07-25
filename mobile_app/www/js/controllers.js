angular.module('starter.controllers', [])

.controller('AppCtrl', function($scope, $ionicModal, $timeout) {

  $scope.loginData = {};

  // Create the login modal that we will use later
  $ionicModal.fromTemplateUrl('templates/login.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });



  // Triggered in the login modal to close it
  $scope.closeLogin = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.login = function() {
    $scope.modal.show();
  }; 

  $scope.doLogin = function() {
    console.log('Doing login', $scope.loginData);

    $timeout(function() {
      $scope.closeLogin();
    }, 1000);
  }; 
})

.controller('PlaylistsCtrl', function($scope, $stateParams,   $ionicModal , $state , Data) {
  
  var selectedProduct = $stateParams.pId;

  if(selectedProduct){
    Data.get('product/'+selectedProduct).then(function(product){
      $scope.product = product[0];
      console.log($scope.product);
    }); 
    
  }else{
    console.log("All")
    Data.get('product').then(function(products){
      $scope.products = products;
    }); 
  }

  $scope.doRefresh = function() {
    console.log('Refreshing...')
    Data.get('product').then(function(products){
      $scope.products = products;
    })
     .finally(function() {
       // Stop the ion-refresher from spinning
       $scope.$broadcast('scroll.refreshComplete');
     });
  };

  $scope.selectProduct = function(product){
    $scope.selectedProduct = product;
    console.log($scope.selectedProduct);
  };

  $ionicModal.fromTemplateUrl('templates/edit.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });


  // Triggered in the login modal to close it
  $scope.closeEdit = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.edit = function() {
    $scope.modal.show();
  }; 
  
  $scope.finishEdit = function(product){
      console.log(product);
      Data.put('product/' + product.id , product);
      $scope.product = product;
      $scope.modal.hide();  

  }

  $scope.addProduct = function(product){
    Data.post('product', product);
    $state.go('app.playlists');
    
  };

})

.controller('ProductCtrl', function($scope,$cordovaBarcodeScanner , Data){
  

   

  $scope.scanBarCode = function(){
    console.log("scanBarCode"); 
    $cordovaBarcodeScanner
      .scan()
      .then(function(barcodeData) {
        
        console.log("text", barcodeData.text);
        console.log("format", barcodeData.format);
        console.log("cancelled", barcodeData.cancelled);


        Data.get(barcodeData.text).then(function(bData){
          console.log("Scan Done!");
          //console.log("bData", bData);
        });
        

      console.log("Got here");


      }, function(error) {
        // An error occurred
      });
      console.log("scanBarCode");

  };

  $scope.addProduct = function(){
      
  };


  
})

.controller('PlaylistCtrl', function($scope, $stateParams) {
});
