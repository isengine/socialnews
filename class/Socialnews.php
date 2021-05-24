<?php

namespace is\Masters\Modules\Isengine;

use is\Helpers\System;
use is\Helpers\Objects;
use is\Helpers\Strings;
use is\Helpers\Local;

use is\Masters\Modules\Master;
use is\Masters\View;

class Socialnews extends Master {
	
	public $result;
	
	public function launch() {
		
		$sets = &$this -> settings;
		$folder = $this -> cache . $sets['api'] . '.' . $sets['id'] . DS;
		$file = $folder . date('YmdH') . '.ini';
		
		Local::createFolder($folder);
		
		if (file_exists($file)) {
			$result = Local::readFile($file);
		} else {
			$method = $sets['api'];
			$result = $this -> $method();
			
			if ($result) {
				if ($sets['reverse']) {
					$result = array_reverse($result);
				}
				Local::eraseFolder($folder);
				Local::writeFile($file, $result);
			}
		}
		unset($file, $folder);
		
		if (!$result) {
			return;
		}
		
		$this -> result = json_decode($result, true);
		$this -> blocks($sets['api']);
		
	}
	
	public function vk() {
		return file_get_contents('https://api.vk.com/method/wall.get', false, stream_context_create(array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => http_build_query(
					array(
						'owner_id' => $this -> settings['id'],
						'count' => !empty($this -> settings['limit']) ? (int) $this -> settings['limit'] : null,
						'access_token' => $this -> settings['key'],
						'v' => '5.85'
					)
				)
			)
		)));
	}
	
	public function instagram() {
		
		if (!$this -> settings['id']) {
			$this -> settings['id'] = substr($this -> settings['key'], 0, strpos($this -> settings['key'], '.'));
		}
		
		//$url = 'https://api.instagram.com/v1/users/' . $this -> settings['id'] . '/media/recent?access_token=' . $this -> settings['key'];
		
		$url = 'https://graph.facebook.com/' . $this -> settings['id'] . '/media?fields=media_url,thumbnail_url,caption,id,media_type,timestamp,username,comments_count,like_count,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}&limit=' . $this -> settings['count'] . '&access_token=' . $this -> insta_key_clean( $this -> settings['key'] );
		
		return Local::RequestUrl($url, null, 'curl');
		
	}
	
	public function insta_key_clean($key) {
		
		if (substr_count($key, '.') < 3) {
			return str_replace('634hgdf83hjdj2', '', $key);
		}
		
		$parts = explode('.', trim($key));
		$last_part = $parts[2] . $parts[3];
		$cleaned = $parts[0] . '.' . base64_decode( $parts[1] ) . '.' . base64_decode( $last_part );
		
		return $cleaned;
		
	}
	
}

?>