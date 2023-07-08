<?php

namespace QPFR;

class Actions {

	const OPTION_NAME = 'qpfr_purge';

	public function init(){
		$this->attach_hooks();
	}
	public function attach_hooks(){


		// hook into the update_option action for the following actions so we can set a timestamp to use for images
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

		// hook into the following filters to append the timestamp to the image url
		add_filter('wp_calculate_image_srcset', array($this, 'append_image_src_srcset'), 10, 1);
		add_filter('wp_get_attachment_image_src', array($this, 'append_image_src'), 10, 4);
		add_filter('wp_get_attachment_image_attributes', array($this, 'append_image_attributes'), 999 , 3);

	}

	// update the option with the current timestamp

	public function update_option(){
		update_option(self::OPTION_NAME, time());
	}

	//
	public function append_image_src_srcset($sources) {
		$new_sources = [];
		foreach ($sources as $key => $source) {
			$source['url'] = self::append_query_string($source['url']);
			$new_sources[$key] = $source;
		}
		return $new_sources;
	}

	// append the query string to the image url
	public function append_image_src($image, $attachment_id, $size, $icon) {
		$image[0] = self::append_query_string($image[0]);
		return $image;
	}

	// append the query string to the image attributes
	function append_image_attributes($attr, $attachment, $size) {

		$attr['src'] = self::append_query_string($attr['src']);

		return $attr;

	}

	// append the query string to the url
	private static function append_query_string($url) {
		// if the query parameter already exists, let's leave
		if (strpos($url, self::OPTION_NAME) !== false) {
			return $url;
		}

		return add_query_arg(self::OPTION_NAME, get_option(self::OPTION_NAME), $url);
	}


}
