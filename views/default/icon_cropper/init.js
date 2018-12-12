define(function(require) {
	
	var $ = require('jquery');
	require('jquery-cropper/jquery-cropper');
	
	function Cropper() {
		var $field;
		var $img;
		var that = this;
		
		this.init = function(selector) {
			console.log('init');
			$field = $(selector);
			$img = $field.closest('.elgg-field').siblings('.icon-cropper-wrapper').find('> img').eq(0);
			
			$field.on('change', this.replaceImg);
		};
	
		this.replaceImg = function() {
			console.log('replace');
			var oFReader = new FileReader();
			
		    oFReader.readAsDataURL(this.files[0]);
		    
		    oFReader.onload = function (oFREvent) {
		    	console.log('reading done');
		    	$img.cropper('destroy');
		    	
		    	$img.attr('src', this.result);
		    	
		    	that.reload();
		    };
		};
		
		this.reload = function() {
			console.log('reload');
			
			var data = $img.data();
			$img.cropper();
		};
	};
	
	return Cropper;
});
