<?php

namespace ColdTrick\IconCropper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ActionValidation {
	
	/**
	 * Check input variables if an icon is resized/cropped without a new icon
	 *
	 * @param \Elgg\Hook $hook 'action:validate', 'all'
	 *
	 * @return void
	 */
	public static function checkIconCropping(\Elgg\Hook $hook) {
		
		$entity_guid = (int) get_input('icon_cropper_guid');
		$input_name = get_input('icon_cropper_input');
		$icon_type = get_input('icon_cropper_type');
		
		if (empty($entity_guid) || elgg_is_empty($input_name) || elgg_is_empty($icon_type)) {
			// not enough information
			return;
		}
		
		if (get_input("{$input_name}_remove") || elgg_get_uploaded_file($input_name)) {
			// user wanted to remove icon, or provided own icon
			return;
		}
		
		$entity = get_entity($entity_guid);
		if (!$entity instanceof \ElggEntity || !$entity->hasIcon('master', $icon_type)) {
			// entity doesn't have an icon
			return;
		}
		
		if ($icon_type === 'icon') {
			$current = [
				'x1' => (int) $entity->x1,
				'y1' => (int) $entity->y1,
				'x2' => (int) $entity->x2,
				'y2' => (int) $entity->y2,
			];
		} else {
			$current = unserialize($entity->{"{$icon_type}_coords"});
		}
		
		$input_cropping_coords = [
			'x1' => (int) get_input('x1'),
			'y1' => (int) get_input('y1'),
			'x2' => (int) get_input('x2'),
			'y2' => (int) get_input('y2'),
		];
		
		$diff = array_diff_assoc($input_cropping_coords, $current);
		if (empty($diff)) {
			// no new cropping data
			return;
		}
		
		// get master image to fake an image upload with
		$master = $entity->getIcon('master', $icon_type);
		
		// copy master to temp location
		$tmp_file = new \ElggTempFile();
		$tmp_file->open('write');
		$tmp_file->write($master->grabFile());
		$tmp_file->close();
		
		$file = [
			'name' => basename($master->getFilenameOnFilestore()),
			'type' => $master->getMimeType(),
			'size'=> $master->getSize(),
			'tmp_name'=> $tmp_file->getFilenameOnFilestore(),
			'error'=> UPLOAD_ERR_OK,
		];
		
		// store the 'new' file in PHP global
		$_FILES[$input_name] = $file;
		
		// store the 'new' file in Elgg request filebag
		$uploaded_file = self::arrayToUploadedFile($file);
		
		$filebag = _elgg_services()->request->files;
		$filebag->set($input_name, $uploaded_file);
	}
	
	/**
	 * convert $_FILES array to UploadedFile
	 *
	 * @param array $file_data file information array
	 *
	 * @return false|UploadedFile
	 */
	protected static function arrayToUploadedFile($file_data) {
		
		if (!is_array($file_data)) {
			return false;
		}
		
		$req_fields = ['error', 'name', 'size', 'tmp_name', 'type'];
		$keys = array_keys($file_data);
		sort($keys);
		
		if ($keys !== $req_fields) {
			return false;
		}
		
		return new UploadedFile(
			$file_data['tmp_name'],
			$file_data['name'],
			$file_data['type'],
			$file_data['size'],
			$file_data['error'],
			true
		);
	}
}
