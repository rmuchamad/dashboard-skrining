<?php

namespace App\Support;

final class CkgScreeningCatalog
{
    /**
     * @return list<array{slug:string,code:string,title:string}>
     */
    public static function categoryOrder(): array
    {
        return require __DIR__.'/CkgScreening/categories.php';
    }

    /**
     * @return list<array<string,mixed>>
     */
    public static function questions(): array
    {
        return array_merge(
            require __DIR__.'/CkgScreening/demografi.php',
            require __DIR__.'/CkgScreening/kanker_usus.php',
            require __DIR__.'/CkgScreening/tb.php',
            require __DIR__.'/CkgScreening/hati.php',
            require __DIR__.'/CkgScreening/mental.php',
            require __DIR__.'/CkgScreening/kanker_paru.php',
            require __DIR__.'/CkgScreening/merokok.php',
            require __DIR__.'/CkgScreening/aktivitas_fisik.php',
            require __DIR__.'/CkgScreening/reproduksi.php',
        );
    }

    public static function slugToCode(string $slug): ?string
    {
        foreach (self::categoryOrder() as $row) {
            if ($row['slug'] === $slug) {
                return $row['code'];
            }
        }

        return null;
    }

    public static function nextSlug(string $slug): ?string
    {
        $rows = self::categoryOrder();
        foreach ($rows as $i => $row) {
            if ($row['slug'] === $slug) {
                return $rows[$i + 1]['slug'] ?? null;
            }
        }

        return null;
    }

    public static function firstSlug(): string
    {
        return self::categoryOrder()[0]['slug'];
    }
}
