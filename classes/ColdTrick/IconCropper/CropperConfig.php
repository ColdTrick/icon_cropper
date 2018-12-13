<?php

namespace ColdTrick\IconCropper;

class CropperConfig {
	
	/**
	 * Prepare entity_type and entity_subtype based on the given entity
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'icon_cropper/init'
	 *
	 * @return void|array
	 */
	public static function prepareEntityTypeSubtype(\Elgg\Hook $hook) {
		
		$vars = $hook->getValue();
		
		if (isset($vars['entity_type']) && isset($vars['entity_subtype'])) {
			// already set
			return;
		}
		
		$entity = elgg_extract('entity', $vars);
		if (!$entity instanceof \ElggEntity) {
			// no entity provided, can't autodetect
			return;
		}
		
		$vars['entity_type'] = $entity->getType();
		$vars['entity_subtype'] = $entity->getSubtype();
		
		return $vars;
	}
	
	/**
	 * Prepare aspect ratio for the cropper
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'icon_cropper/init'
	 *
	 * @return void|array
	 */
	public static function prepareAspectRatio(\Elgg\Hook $hook) {
		
		$vars = $hook->getValue();
		
		$detect_ratio_size = elgg_extract('cropper_aspect_ratio_size', $vars, 'master');
		if ($detect_ratio_size === false) {
			return;
		}
		
		$cropper_config = (array) elgg_extract('cropper_config', $vars, []);
		if (isset($cropper_config['aspectRatio'])) {
			// already set
			return;
		}
		
		$icon_type = elgg_extract('icon_type', $vars, 'icon');
		$entity_type = elgg_extract('entity_type', $vars);
		$entity_subtype = elgg_extract('entity_subtype', $vars);
		
		$sizes = elgg_get_icon_sizes($entity_type, $entity_subtype, $icon_type);
		if (empty($sizes) || !isset($sizes[$detect_ratio_size])) {
			// no way to read the config
			return;
		}
		
		$width = (int) elgg_extract('w', $sizes[$detect_ratio_size]);
		$height = (int) elgg_extract('h', $sizes[$detect_ratio_size]);
		
		if (empty($width) || empty($height)) {
			return;
		}
		
		$cropper_config['aspectRatio'] = $width / $height;
		
		$vars['cropper_config'] = $cropper_config;
		
		return $vars;
	}
}