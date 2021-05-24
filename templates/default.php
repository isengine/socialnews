<?php

namespace is\Masters\Modules\Isengine\Socialnews;

use is\Helpers\System;
use is\Helpers\Objects;
use is\Helpers\Strings;

$instance = $object -> get('instance');
$sets = &$object -> settings;
$data = $object -> getData();

//echo print_r($object, 1);

//$object -> eget('container') -> addClass('new');
//$object -> eget('container') -> open(true);
//$object -> eget('container') -> close(true);
//$object -> eget('container') -> print();

//$object -> blocks($sets['api']);

?>
<div id="news">
<?php
if (System::typeIterable($data)) {
	foreach ($data as $item) {
?>
	<div>
		<p>NEW!</p>
		<p><?= $item['date']; ?></p>
		<p><?= $item['text']; ?></p>
		
		<?php foreach ($item['images'] as $image) : ?>
			<img src="<?= $image; ?>">
		<?php endforeach; ?>
		
		<?php if (isset($item['video'])) : ?>
			<a href="<?= $item['video']; ?>" target="_blank">смотреть видео</a>
		<?php endif; ?>
		
	</div>
<?php
	}
	unset($item, $data);
}
?>
</div>