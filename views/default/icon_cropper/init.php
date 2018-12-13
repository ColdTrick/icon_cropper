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
$icon_type = elgg_extract('icon_type', $vars, 'icon');
$input_name = elgg_extract('name', $vars, 'icon');

// build cropper configuration
$default_config = [
	'viewMode' => 2,
	'background' => false,
	'autoCropArea' => 1,
	'rotatable' => false,
];

$cropper_data = array_merge($default_config, (array) elgg_extract('cropper_config', $vars, []));
$cropper_data = json_encode($cropper_data);

$img_url = null;
if ($entity instanceof ElggEntity && $entity->hasIcon('master', $icon_type)) {
	$img_url = $entity->getIconURL([
		'size' => 'master',
		'type' => $icon_type,
	]);
}

$img = elgg_format_element('img', [
	'data-icon-cropper' => $cropper_data,
	'src' => $img_url,
]);


echo elgg_format_element('div', ['class' => ['icon-cropper-wrapper', 'hidden']], $img);

$input ='';
foreach (['x1', 'y1', 'x2', 'y2'] as $coord) {
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => $coord,
		'value' => ($entity instanceof ElggEntity) ? $entity->$coord : null,
	]);
}

if (!empty($img_url)) {
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => 'icon_cropper_guid',
		'value' => $entity->guid,
	]);
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => 'icon_cropper_type',
		'value' => $icon_type,
	]);
	$input .= elgg_view_field([
		'#type' => 'hidden',
		'name' => 'icon_cropper_input',
		'value' => $input_name,
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
