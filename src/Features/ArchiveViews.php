<?php

namespace ThemePlate\Cleaner\Features;

class ArchiveViews extends DefaultViews {

	public const TAGS = array(
		'category',
		'tag',
		'author',
		'date',
	);


	public function key(): string {

		return 'archive_views';

	}

}
