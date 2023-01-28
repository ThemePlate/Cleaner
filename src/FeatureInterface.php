<?php

namespace ThemePlate\Cleaner;

interface FeatureInterface {

	public function register(): void;

	public function feature(): string;

	public function enabled( string $option ): bool;

	public function key(): string;

	public function action(): void;

}
