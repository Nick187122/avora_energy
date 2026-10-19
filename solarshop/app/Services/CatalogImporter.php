<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CatalogImporter
{
    public int $imported = 0;

    public int $featured = 0;

    public int $thumbsGenerated = 0;

    private int $thumbSize = 480;

    private array $topBrands = [
        'jinko-solar', 'ja-solar', 'trina-solar', 'longi-solar',
        'growatt', 'deye', 'victron-energy', 'huawei-solar',
    ];

    /**
     * Wipe every product and re-import the full catalog from the seed JSON,
     * then regenerate the storefront thumbnail set.
     */
    public function run(): void
    {
        $path = storage_path('app/private/seed/all_products.json');

        if (! is_file($path)) {
            throw new \RuntimeException('Products JSON not found at '.$path);
        }

        $data = json_decode((string) file_get_contents($path), true) ?: [];

        if (! $data) {
            throw new \RuntimeException('Products JSON is empty.');
        }

        Product::query()->delete();

        $categories = Category::pluck('id', 'slug');
        $brands = Brand::pluck('id', 'slug');
        $featureSlots = $this->featureSlots($data);

        foreach ($data as $i => $item) {
            $name = trim((string) ($item['name'] ?? ''));

            if ($name === '') {
                continue;
            }

            [$brandId, $brandName] = $this->resolveBrand($item, $brands);
            $categoryId = $this->resolveCategory($item, $categories);

            $short = trim((string) ($item['short_description'] ?? ''));
            $specs = $this->resolveSpecs($item, $brandName);

            $description = $this->buildDescription($name, $brandName, $short, $specs);

            $slug = Str::slug((string) ($item['slug'] ?? ''));
            if ($slug === '') {
                $slug = 'product-'.$i;
            }
            if (Product::where('slug', $slug)->exists()) {
                $slug .= '-'.$i;
            }

            $sku = $this->resolveSku($item, $i);

            $stock = ($item['in_stock'] ?? true) ? (5 + ($i % 20)) : 0;

            $regularPrice = (float) ($item['regular_price'] ?? $item['price'] ?? 0);
            $salePrice = ($item['sale_price'] ?? null) !== null ? (float) $item['sale_price'] : null;
            if ($salePrice !== null && $salePrice >= $regularPrice) {
                $salePrice = null;
            }
            if ($regularPrice <= 0) {
                $regularPrice = (float) ($item['price'] ?? 0);
            }

            $featureKey = $this->brandSlotKey($item);
            $isFeatured = in_array($featureKey, $featureSlots, true);

            if ($isFeatured) {
                $featureSlots = array_values(array_diff($featureSlots, [$featureKey]));
            }

            Product::create([
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'name' => $name,
                'slug' => $slug,
                'sku' => $sku,
                'short_description' => $short === '' ? Str::limit($name, 110) : $short,
                'description' => $description,
                'regular_price' => max($regularPrice, 0),
                'sale_price' => $salePrice,
                'stock' => $stock,
                'image' => $item['img'] ?? null,
                'gallery' => ! empty($item['gallery']) ? array_values(array_unique($item['gallery'])) : null,
                'specifications' => $specs ?: null,
                'views' => (($i * 37) % 887) + 13,
                'is_featured' => $isFeatured,
                'is_active' => true,
                'meta_title' => Str::limit($name, 70),
                'meta_description' => Str::limit($name.' - solar products in Kenya with genuine warranty.', 160),
            ]);

            if ($isFeatured) {
                $this->featured++;
            }

            $this->imported++;
        }

        $this->generateThumbnails();
    }

    /**
     * Deterministic "featured" picks: the first product of every (top brand, top-level category) pair.
     *
     * @return string[]
     */
    private function featureSlots(array $data): array
    {
        $slots = [];
        $keys = [];

        foreach ($data as $item) {
            if (! in_array(Str::slug((string) ($item['brand'] ?? '')), $this->topBrands, true)) {
                continue;
            }

            $key = $this->brandSlotKey($item);

            if (! isset($slots[$key])) {
                $slots[$key] = true;
                $keys[] = $key;
            }

            if (count($keys) >= 48) {
                break;
            }
        }

        return $keys;
    }

    private function brandSlotKey(array $item): string
    {
        $brand = Str::slug((string) ($item['brand'] ?? ''));
        $categories = $item['categories'] ?? [];
        $first = $categories ? (string) reset($categories) : '';
        $top = $first !== '' ? Str::before($first, '\\') : 'uncategorized';

        return $brand.'|'.($top === '' ? 'uncategorized' : $top);
    }

    /**
     * @param  Collection  $brands  slug => id
     * @return array{0: int|null, 1: string|null}
     */
    private function resolveBrand(array $item, $brands): array
    {
        $inferred = trim((string) ($item['brand'] ?? ''));

        if ($inferred !== '') {
            $id = $brands[Str::slug($inferred)] ?? null;

            if ($id) {
                return [$id, $inferred];
            }
        }

        foreach ((array) ($item['categories'] ?? []) as $path) {
            if (! str_starts_with((string) $path, 'brand\\')) {
                continue;
            }
            $name = Str::title(str_replace('-', ' ', Str::after($path, 'brand\\')));
            $slug = Str::slug($name);

            if ($brands->has($slug)) {
                return [$brands[$slug], $name];
            }
        }

        return [null, $inferred !== '' ? $inferred : null];
    }

    /**
     * @param  Collection  $categories  slug => id
     */
    private function resolveCategory(array $item, $categories): ?int
    {
        $best = null;
        $bestDepth = -1;

        foreach ((array) ($item['categories'] ?? []) as $path) {
            $segments = array_values(array_filter(array_map('trim', explode('\\', (string) $path))));

            $slug = end($segments);
            $depth = count($segments);

            if ($slug === false || ! $categories->has($slug) || $depth <= $bestDepth) {
                continue;
            }

            $best = $categories[$slug];
            $bestDepth = $depth;
        }

        return $best;
    }

    private function resolveSku(array $item, int $index): ?string
    {
        $raw = trim((string) ($item['sku'] ?? ''));

        if (str_starts_with(strtolower($raw), 'sku:')) {
            $raw = trim(Str::after($raw, ':'));
        }

        $sku = $raw === '' ? null : strtoupper($raw);

        if ($sku && Product::where('sku', $sku)->exists()) {
            $sku .= '-'.$index;
        }

        return $sku;
    }

    /**
     * @return array<string, string>
     */
    private function resolveSpecs(array $item, ?string $brandName): array
    {
        $name = (string) ($item['name'] ?? '');
        $specs = [];

        if (preg_match('/\(?(\d+(?:\.\d+)?)\s*kwh\b/i', $name, $m)) {
            $specs['Storage Capacity'] = $m[1].' kWh';
        }

        if (preg_match('/\(?(\d+(?:\.\d+)?)\s*(k?w)\b/i', $name, $m)) {
            $specs['Wattage'] = $m[1].strtolower($m[2]);
        }

        if ($brandName !== null && $brandName !== '') {
            $specs['Brand'] = $brandName;
        }

        $sku = trim((string) ($item['sku'] ?? ''));
        if ($sku !== '') {
            $specs['SKU'] = strtoupper(preg_replace('/^sku:\s*/i', '', $sku) ?? $sku);
        }

        return $specs;
    }

    /**
     * @param  array<string, string>  $specs
     */
    private function buildDescription(string $name, ?string $brandName, string $short, array $specs): string
    {
        $html = '<p>'.e($name)
            .($brandName ? ' from <strong>'.e($brandName).'</strong>' : '')
            .' — available at Avora Energy Solutions at competitive prices with genuine warranty and nationwide delivery.</p>';

        if ($short !== '') {
            $html .= '<p>'.e($short).'</p>';
        }

        if ($specs) {
            $rows = collect($specs)
                ->map(fn ($v, $k) => '<tr><td><strong>'.e($k).'</strong></td><td>'.e($v).'</td></tr>')
                ->implode('');
            $html .= '<p>Key attributes:</p><table class="spec-table"><tbody>'.$rows.'</tbody></table>';
        }

        $html .= '<p>Contact us for expert advice, bulk pricing, and installation services.</p>';

        return $html;
    }

    /**
     * (Re)generate 480x480 cover-cropped WebP thumbnails for every product image.
     */
    public function generateThumbnails(): void
    {
        $dir = public_path('img/thumbs');

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $supportWebp = function_exists('imagewebp');
        $ext = $supportWebp ? 'webp' : 'jpg';

        Product::whereNotNull('image')->where('image', '!=', '')->each(function (Product $product) use ($dir, $ext, $supportWebp) {
            $path = public_path($product->image);

            if (! is_file($path)) {
                return;
            }

            $hash = pathinfo($product->image, PATHINFO_FILENAME);
            $thumb = $dir.'/'.$hash.'.'.$ext;

            if (is_file($thumb)) {
                $this->thumbsGenerated++;

                return;
            }

            $src = @imagecreatefromstring((string) file_get_contents($path));

            if (! $src) {
                return;
            }

            $w = imagesx($src);
            $h = imagesy($src);
            $size = $this->thumbSize;

            $ratio = max($size / $w, $size / $h);
            $nw = max(1, (int) round($w * $ratio));
            $nh = max(1, (int) round($h * $ratio));

            $resized = imagecreatetruecolor($nw, $nh);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefilledrectangle($resized, 0, 0, $nw, $nh, $transparent);
            imagecopyresampled($resized, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

            $cropX = max(0, (int) floor(($nw - $size) / 2));
            $cropY = max(0, (int) floor(($nh - $size) / 2));

            $crop = imagecreatetruecolor($size, $size);
            imagealphablending($crop, false);
            imagesavealpha($crop, true);

            if ($supportWebp) {
                // Preserve transparency for WebP
                $bg = imagecolorallocatealpha($crop, 255, 255, 255, 127);
            } else {
                // Use white background for JPEG
                $bg = imagecolorallocate($crop, 255, 255, 255);
            }
            imagefilledrectangle($crop, 0, 0, $size, $size, $bg);
            imagecopy($crop, $resized, 0, 0, $cropX, $cropY, $size, $size);

            if ($supportWebp) {
                imagewebp($crop, $thumb, 82);
            } else {
                imagejpeg($crop, $thumb, 85);
            }

            imagedestroy($src);
            imagedestroy($resized);
            imagedestroy($crop);

            $this->thumbsGenerated++;
        });
    }
}
