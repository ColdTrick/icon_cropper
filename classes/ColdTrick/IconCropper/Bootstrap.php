<?php

namespace ColdTrick\IconCropper;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\DefaultPluginBootstrap::init()
	 */
	public function init() {
		
		elgg_register_css('cropperjs', elgg_get_simplecache_url('cropperjs/cropper.css'));
		elgg_define_js('cropperjs', [
			'src' => elgg_get_simplecache_url('cropperjs/cropper.min.js'),
		]);
		elgg_define_js('jquery-cropper/jquery-cropper', [
			'src' => elgg_get_simplecache_url('jquery-cropper/jquery-cropper.min.js'),
		]);
		
		$this->extendViews();
	}
	
	/**
	 * Extend views
	 *
	 * @return void
	 */
	protected function extendViews() {
		
		elgg_extend_view('entity/edit/icon/file', 'icon_cropper/init');
	}
}
