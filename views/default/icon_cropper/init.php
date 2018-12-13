<?php
/**
 * Add cropping features to icon uploading
 *
 * @uses $vars['entity']                    the entity being edited
 * @uses $vars['entity_type']               the type of the entity
 * @uses $vars['entity_subtype']            the subtype of the entity
 * @uses $vars['cropper_config']            configuration for CropperJS
 * @uses $vars['cropper_aspect_ratio_size'] the icon size to use to detect cropping aspact ratio (default: master) pass 'false' to disable
 */

elgg_load_css('cropperjs');

$entity = elgg_extract('entity', $vars);

// build cropper configuration
$default_config = [
	'viewMode' => 3,
	'autoCropArea' => 1,
	'rotatable' => false,
];

$cropper_data = array_merge($default_config, (array) elgg_extract('cropper_config', $vars, []));
$cropper_data = json_encode($cropper_data);

echo elgg_format_element('div', ['class' => 'icon-cropper-wrapper'], elgg_format_element('img', ['data-icon-cropper' => $cropper_data]));

$input ='';
foreach (['x1', 'y1', 'x2', 'y2'] as $coord) {
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => $coord,
		'value' => ($entity instanceof ElggEntity) ? $entity->$coord : null,
	]);
}

echo elgg_format_element('div', ['class' => ['icon-cropper-input', 'hidden']], $input);

?>
<script>
	require(['icon_cropper/init'], function(Cropper) {
		var cropper = new Cropper();
		
		cropper.init('input[type="file"][name="<?php echo elgg_extract('name', $vars, 'icon'); ?>"]');
	});
</script>
