<?php

namespace QPFR;

class Actions {

	const OPTION_NAME = 'qpfr_purge';

	public function init(){
		$this->attach_hooks();
	}
	public function attach_hooks(){

		$update_option_actions = [
			'redis_object_cache_flush',
			'spinupwp_site_purged',
			'spinupwp_post_purged',
			'spinupwp_url_purged',
			'spinupwp_cache_cleared',
		];

		foreach($update_option_actions as $action){
			add_action($action, array($this, 'update_option'));
		}


		add_filter('wp_get_attachment_image_src', array($this, 'append_image_src'), 10, 4);
		add_filter('wp_get_attachment_image_attributes', array($this, 'append_image_attributes'), 999 , 3);

	}

	public function update_option(){
		update_option(self::OPTION_NAME, time());
	}

	public function append_image_src($image, $attachment_id, $size, $icon) {
		$image[0] = self::append_query_string($image[0]);
		return $image;
	}

	function append_image_attributes($attr, $attachment, $size) {

		$attr['src'] = self::append_query_string($attr['src']);

		return $attr;

	}

	private static function append_query_string($url) {
		$query_string = self::OPTION_NAME . '=' . get_option(self::OPTION_NAME);
		if (strpos($url, '?') !== false) {
			return sprintf('%s&%s', $url, $query_string);
		} else {
			return sprintf('%s?%s', $url, $query_string);
		}
	}


}
