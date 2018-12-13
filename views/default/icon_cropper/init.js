define(function(require) {
	
	var $ = require('jquery');
	require('jquery-cropper/jquery-cropper');
	
	function Cropper() {
		var $field;
		var $img;
		var $inputWrapper;
		var that = this;
		
		this.init = function(selector) {
			$field = $(selector);
			$img = $field.closest('.elgg-field').siblings('.icon-cropper-wrapper').find('> img').eq(0);
			$inputWrapper = $field.closest('.elgg-field').siblings('.icon-cropper-input').eq(0);
			
			$field.on('change', this.replaceImg);
		};
	
		this.replaceImg = function() {
			var oFReader = new FileReader();
			
		    oFReader.readAsDataURL(this.files[0]);
		    
		    oFReader.onload = function (oFREvent) {
		    	$img.off('crop.iconCropper');
		    	$img.cropper('destroy');
		    	
		    	$img.attr('src', this.result);
		    	
		    	that.reload();
		    };
		};
		
		this.reload = function() {
			$img.cropper($img.data().iconCropper);
			$img.on('crop.iconCropper', this.crop);
		};
		
		this.crop = function(event) {
			var cropDetails = $img.cropper('getData', true);
			
			$inputWrapper.find('input[name="x1"]').val(cropDetails.x);
			$inputWrapper.find('input[name="y1"]').val(cropDetails.y);
			$inputWrapper.find('input[name="x2"]').val(cropDetails.x + cropDetails.width);
			$inputWrapper.find('input[name="y2"]').val(cropDetails.y + cropDetails.height);
		}
	};
	
	return Cropper;
});
