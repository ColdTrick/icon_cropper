<?php

elgg_load_css('cropperjs');

$cropper_data = json_encode([
	'viewMode' => 3,
]);

echo elgg_format_element('div', ['class' => 'icon-cropper-wrapper'], elgg_format_element('img', ['data-cropper' => $cropper_data]));

?>
<script>
	require(['icon_cropper/init'], function(Cropper) {
		var cropper = new Cropper();
		
		cropper.init('input[type="file"][name="<?php echo elgg_extract('name', $vars, 'icon'); ?>"]');
	});
</script>
