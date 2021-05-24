<?php

namespace is\Masters\Modules\Isengine\Socialnews;

use is\Helpers\System;
use is\Helpers\Objects;
use is\Helpers\Strings;
use is\Helpers\Local;

use is\Masters\Modules\Master;
use is\Masters\View;

$data = [];
$sets = &$object -> settings;

if (!System::typeIterable($object -> result['data'])) {
	return;
}

foreach ($object -> result['data'] as $key => $item) {
	
	if (isset($sets['disable']) && in_array($item['id'], $sets['disable'])) {
		
		$sets['count']++;
		
	} elseif ($key < $sets['count']) {
		
		$item['type'] = mb_strtolower($item['media_type']);
		
		$data[$key] = [
			'date' => strtotime($item['timestamp']) + 10800,
			'text' => $item['caption'],
			'link' => $item['permalink'],
			'images' => []
		];
		
		if ($item['type'] === 'image') {
			$data[$key]['images'][] = $item['media_url'];
		} elseif ($item['type'] === 'video') {
			$data[$key]['images'] = [$item['thumbnail_url']];
			$data[$key]['video'] = $item['media_url'];
		} elseif ($item['type'] === 'carousel_album') {
			if (System::typeIterable($item['children']['data'])) {
				foreach ($item['children']['data'] as $images) {
					$item['type'] = mb_strtolower($images['media_type']);
					if ($item['type'] === 'image') {
						$data[$key]['images'][] = $images['media_url'];
					} elseif ($item['type'] === 'video') {
						$data[$key]['images'] = [$images['thumbnail_url']];
						$data[$key]['video'] = $images['media_url'];
						break;
					}
				}
			}
		}
		
	}
	
}

if (System::typeIterable($data)) {
	$object -> setData($data);
}

?>