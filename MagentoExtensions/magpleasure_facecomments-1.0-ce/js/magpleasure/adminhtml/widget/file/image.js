/**
 * Magpleasure Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE-CE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magpleasure.com/LICENSE-CE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * Magpleasure does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Magpleasure does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Magpleasure
 * @package    Magpleasure_Common
 * @version    0.6.11
 * @copyright  Copyright (c) 2012-2013 Magpleasure Ltd. (http://www.magpleasure.com)
 * @license    http://www.magpleasure.com/LICENSE-CE.txt
 */


function FileImageCtrl($scope, savedData, controlId) {

    $scope.data = savedData;
    $scope.getRequired = function(){
        return ($scope.data.is_required && !$scope.data.has_thumbnail);
    };

    // Init controller
    $scope.loading = false;
    $scope.loading_percent = 0;
    $scope.error_message = false;
    $scope.delete = [];

    $scope.checkImageExistence = function(){
        $(controlId).disabled = $scope.data.has_thumbnail;
    };

    jQuery(function() {
        jQuery('#' + $scope.data.html_id).fileupload({
            dataType: 'json',
            url: $scope.data.upload_url,
            send: function(e, data){
                $scope.$apply(function() {
                    $scope.startLoader();

                    if ($scope.value){
                        $scope.delete.push($scope.value);
                    }
                });
            },
            done: function(e, data) {
                $scope.$apply(function() {
                    $scope.disableLoader();
                    if (data.result){
                        var result = data.result;

                        if (result[$scope.data.response_key]){

                            var file = result[$scope.data.response_key][0];
                            var error = file['error'];
                            if (!error){
                                $scope.data.thumbnail_url = file['thumbnail_url'];
                                $scope.data.image_url = file['url'];
                                $scope.data.has_thumbnail = true;

                                $scope.checkImageExistence();

                                var r = new RegExp('\\' + $scope.data.dir_separator, 'g');
                                $scope.value = file.upload_path.replace(r, '/');



                            } else {

                                $scope.error_message = error;
                            }
                        }
                    }
                });
            },
            fail: function(e, data) {
                $scope.$apply(function() {
                    $scope.disableLoader();
                });
            },
            progressall: function(e, data) {
                $scope.$apply(function() {
                    $scope.loading = true;
                    $scope.loading_percent = parseInt(data.loaded / data.total * 100, 10);
                });
            }
        });
    });

    $scope.disableLoader = function(){
        $scope.loading = false;
        $scope.loading_percent = 0;
    };

    $scope.startLoader = function(){
        $scope.error_message = false;
        $scope.loading_percent = 0;
        $scope.loading = false;
    };

    $scope.clearData = function(){
        $scope.delete.push($scope.value);
        $scope.value = '';
        $scope.data.has_thumbnail = false;
        $scope.checkImageExistence();
    };

    if (savedData.has_image){
        $scope.data.thumbnail_url = savedData.thumbnail_url;
        $scope.data.image_url = savedData.image_url;
        $scope.data.has_thumbnail = true;
        $scope.value = savedData.value;
    } else {
        $scope.value = '';
    }

}

