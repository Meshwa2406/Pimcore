<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Image;

interface AdapterInterface
{
    public function getHeight(): int;

    public function setHeight(int $height): static;

    public function getWidth(): int;

    public function setWidth(int $width): static;

    public function resize(int $width, int $height): static;

    public function scaleByWidth(int $width, bool $forceResize = false): static;

    public function scaleByHeight(int $height, bool $forceResize = false): static;

    public function contain(int $width, int $height, bool $forceResize = false): static;

    public function cover(int $width, int $height, array|string|null $orientation = 'center', bool $forceResize = false): static;

    public function frame(int $width, int $height, bool $forceResize = false): static;

    public function trim(int $tolerance): static;

    public function rotate(int $angle): static;

    public function crop(int $x, int $y, int $width, int $height): static;

    public function setBackgroundColor(string $color): static;

    public function setBackgroundImage(string $image): static;

    public function roundCorners(int $width, int $height): static;

    public function addOverlay(mixed $image, int $x = 0, int $y = 0, int $alpha = 100, string $composite = 'COMPOSITE_DEFAULT', string $origin = 'top-left'): static;

    public function addOverlayFit(string $image, string $composite = 'COMPOSITE_DEFAULT'): static;

    public function applyMask(string $image): static;

    public function cropPercent(int $width, int $height, int $x, int $y): static;

    public function grayscale(): static;

    public function sepia(): static;

    public function sharpen(): static;

    public function mirror(string $mode): static;

    public function gaussianBlur(int $radius = 0, float $sigma = 1.0): static;

    public function brightnessSaturation(int $brightness = 100, int $saturation = 100, int $hue = 100): static;

    public function load(string $imagePath, array $options = []): static|false;

    public function save(string $path, string $format = null, int $quality = null): static;

    public function getContentOptimizedFormat(): string;

    public function supportsFormat(string $format, bool $force = false): bool;

    public function isVectorGraphic(): bool;
}
