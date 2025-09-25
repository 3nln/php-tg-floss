<?php

namespace App\Utils\Traits;

trait UsefulCommandTrait
{
    protected string $useful = __DIR__ . '/../../Utils/assets/useful.json';

    public function parseTitles(): array
    {
        $source = json_decode(file_get_contents($this->useful), true);
        $titles = [];
        foreach ($source as $key => $item) {
            $titles[] = [
                'title' => $key
            ];
        }
        return $titles;
    }

    public function parseUsefulByTitle(string $title)
    {
        $source = json_decode(file_get_contents($this->useful), true);
        $useful = [];
        foreach ($source as $key => $item) {
            if ($key === $title) {
                $useful = $item;
            }
        }
        return $useful;
    }
}