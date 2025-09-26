<?php

namespace App\Utils\Traits;

use JsonException;

trait VersionTrait
{
    protected string $releases = "https://api.github.com/repos/php/php-src/releases";
    protected string $assets_folder = __DIR__ . "/../../Utils/assets/";

    /**
     * @throws JsonException
     */
    public function parseTageName(): array
    {
        $getFile = glob($this->assets_folder . "releases_*.json");
        if (!empty($getFile)) {
            $fileName = $getFile[0];
            if (!$this->isExpired($fileName)) {
                $data = json_decode(file_get_contents($fileName), true, 512, JSON_THROW_ON_ERROR);
                if (is_array($data) && !empty($data)) {
                    return $data;
                }
            } else {
                unlink($fileName);
            }
        }
        $fileName = $this->getFileName();
        $get = $this->request($this->releases);

        $tags = array_map(fn($item) => $item['tag_name'], $get);
        file_put_contents($fileName, json_encode($tags, JSON_THROW_ON_ERROR));
        return $tags;
    }

    // helper functions for this trait
    private function getFileName(): string
    {
        $expire = time() + (60 * 60 * 24 * 30); // 1 month
        return $this->assets_folder . "releases_" . $expire .".json";
    }

    private function isExpired(string $filename): bool
    {
        $expire_day = explode("_", basename($filename, ".json"))[1]; // get expire timestamp
        $today = time();
        return $today > (int)$expire_day;
    }
}