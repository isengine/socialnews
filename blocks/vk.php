<?php

namespace is\Masters\Modules\Isengine\Socialnews;

use is\Helpers\System;
use is\Helpers\Objects;
use is\Helpers\Strings;
use is\Helpers\Local;

use is\Masters\Modules\Master;
use is\Masters\View;

$data = [];
$sets = &$this -> settings;

//echo '<pre>';
//echo print_r($this, 1);
//exit;

if (!System::typeIterable($this -> result['response']['items'])) {
	return;
}

foreach ($this -> result['response']['items'] as $key => $item) {
	
	$skip = null;
	
	if (!empty($sets['disable']) && in_array($item['id'], $sets['disable'])) {
		$skip = true;
	} elseif (!empty($sets['selfonly']) && !empty($item['signer_id'])) {
		$skip = true;
	} elseif (empty($sets['repost']) && !empty($item['copy_history'][0])) {
		$skip = true;
	} elseif (!empty($sets['rules'])) {
		
		if (empty($sets['repost'])) {
			if (
				($sets['rules'] === 'text' && !$item['text']) ||
				($sets['rules'] === 'images' && !$item['attachments']) ||
				($sets['rules'] === 'both' && (!$item['text'] || !$item['attachments']))
			) {
				//$skip = true;
			}
			if (!empty($item['copy_history'][0])) {
				unset($item['copy_history'][0]);
			}
		} else {
			if (
				($sets['rules'] === 'text' && !$item['text'] && !$item['copy_history'][0] -> text) ||
				($sets['rules'] === 'images' && !$item['attachments'] && !$item['copy_history'][0]['attachments']) ||
				($sets['rules'] === 'both' && (!$item['text'] || !$item['attachments']) && (!$item['copy_history'][0]['text'] || !$item['copy_history'][0]['attachments']))
			) {
				//$skip = true;
			}
		}
		
	}
	
	if ($skip) {
		$sets['count']++;
	}
	
	if (!$skip && $key < $sets['count']) {
		
		//print_r($item);
		//echo '<br>---------------------------<br>';
		
		//copy_history
		if (!empty($item['copy_history'][0])) {
			$item['text'] = $item['copy_history'][0]['text'];
			$item['attachments'] = $item['copy_history'][0]['attachments'];
		}
		
		if (isset($sets['defaults'])) {
			if (!$item['text'] && $sets['defaults']['text'][$currlang]) {
				$item['text'] = $sets['defaults']['text'][$currlang];
			} elseif (!$item['text'] && $sets['defaults']['text']) {
				$item['text'] = $sets['defaults']['text'];
			}
			if (!$item['images'] && $sets['defaults']['images'][$currlang]) {
				$item['images'] = $sets['defaults']['images'][$currlang];
			} elseif (!$item['images'] && $sets['defaults']['images']) {
				$item['images'] = $sets['defaults']['images'];
			}
		}
		
		$data[$key] = [
			'link' => 'http://vk.com/wall' . $item['owner_id'] . '_' . $item['id'],
			'date' => $item['date'],
			'text' => $item['text'],
			'title' => mb_substr($item['text'], 0, 100),
			'images' => []
		];
		
		foreach ($item['attachments'] as $images) {
			if ($images['type'] === 'photo') {
				foreach ($images['photo']['sizes'] as $image) {
					if ($image['type'] === 'x') {
						$data[$key]['images'][] = $image['url'];
					}
				}
			}
		}
		
	}
	
}

if (System::typeIterable($data)) {
	$this -> setData($data);
}

?>