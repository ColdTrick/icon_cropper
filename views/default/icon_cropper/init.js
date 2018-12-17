define(function(require) {
	
	var $ = require('jquery');
	require('jquery-cropper/jquery-cropper');
	
	function Cropper() {
		var $field;
		var $img;
		var $imgWrapper;
		var $inputWrapper;
		var that = this;
		
		this.init = function(selector) {
			$field = $(selector);
			$imgWrapper = $field.closest('.elgg-field').siblings('.icon-cropper-wrapper');
			$img = $imgWrapper.find('> img').eq(0);
			$inputWrapper = $field.closest('.elgg-field').siblings('.icon-cropper-input').eq(0);
			
			$field.on('change', this.replaceImg);
			
			if ($img[0].hasAttribute('src')) {
				this.reload();
			}
		};
	
		this.replaceImg = function() {
			var oFReader = new FileReader();
			
		    oFReader.readAsDataURL(this.files[0]);
		    
		    oFReader.onload = function (oFREvent) {
		    	$img.off('crop.iconCropper');
		    	$img.cropper('destroy');
		    	
		    	$img.attr('src', this.result);
		    	
		    	$inputWrapper.find('input[name="icon_cropper_guid"], input[name="icon_cropper_type"], input[name="icon_cropper_name"]').remove();
		    	
		    	that.reload({data:{}});
		    };
		};
		
		this.reload = function(extra_data = {}) {
			$imgWrapper.removeClass('hidden');
			
			var data = $img.data().iconCropper;
			Object.assign(data, extra_data);

			$img.cropper(data);
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
