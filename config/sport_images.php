<?php
/**
 * Centralized sport image helpers
 *
 * sportImage($sport)            → main image URL for a sport (used on cards + detail hero)
 * sportGalleryImages($sport)    → array of 3 URLs for the detail page gallery
 */

if (!function_exists('sportImage')) {

    /**
     * Returns the primary image URL for a given sport type.
     */
    function sportImage(string $sport, string $context = 'card'): string
    {
        static $map = [
            'Football'   => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=900&q=85&auto=format&fit=crop',
            'Tennis'     => 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=900&q=85&auto=format&fit=crop',
            'Basketball' => 'https://images.unsplash.com/photo-1519861531473-9200262188bf?w=900&q=85&auto=format&fit=crop',
            'Padel'      => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=900&q=85&auto=format&fit=crop',
            'default'    => 'https://images.unsplash.com/photo-1517649763962-0c623066013b?w=900&q=85&auto=format&fit=crop',
        ];
        return $map[$sport] ?? $map['default'];
    }
}

if (!function_exists('sportGalleryImages')) {

    /**
     * Returns an array of 3 sport-specific image URLs for the terrain detail gallery.
     * Index 0 = main (hero), 1 & 2 = small thumbnails.
     */
    function sportGalleryImages(string $sport): array
    {
        // Each sport uses ONE verified photo ID with 3 different crops.
        // This guarantees all 3 gallery images always show the correct sport.
        static $ids = [
            'Football'   => '1574629810360-7efbbe195018',
            'Tennis'     => '1622279457486-62dcc4a431d6',
            'Basketball' => '1519861531473-9200262188bf',
            'Padel'      => '1554068865-24cecd4e34b8',
            'default'    => '1517649763962-0c623066013b',
        ];
        $id  = $ids[$sport] ?? $ids['default'];
        $base = "https://images.unsplash.com/photo-{$id}";
        return [
            "{$base}?w=1200&q=85&auto=format&fit=crop",
            "{$base}?w=600&q=80&auto=format&fit=crop&crop=top",
            "{$base}?w=600&q=80&auto=format&fit=crop&crop=bottom",
        ];
    }
}
