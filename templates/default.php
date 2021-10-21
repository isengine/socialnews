<?php

namespace is\Masters\Modules\Isengine\Socialnews;

use is\Helpers\System;
use is\Helpers\Objects;
use is\Helpers\Strings;

$instance = $this -> get('instance');
$sets = &$this -> settings;
$data = $this -> getData();

//echo print_r($this, 1);

//$this -> eget('container') -> addClass('new');
//$this -> eget('container') -> open(true);
//$this -> eget('container') -> close(true);
//$this -> eget('container') -> print();

//$this -> block($sets['api']);

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