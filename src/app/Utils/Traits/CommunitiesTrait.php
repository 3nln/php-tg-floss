<?php

namespace App\Utils\Traits;

trait CommunitiesTrait
{
    protected string $communities = __DIR__ . '/../assets/community.json';
    public function parseCommunities(): array
    {
        $data = json_decode(file_get_contents($this->communities), true);
        $communities = [];
        foreach ($data as $item) {
            $communities[] = [
                'slug' => $item['slug'],
                'name' => $item['name'],
            ];
        }
        return $communities;
    }

    public function parseCommunityBySlug(string $slug): array
    {
        $data = json_decode(file_get_contents($this->communities), true);
        $community = [];
        foreach ($data as $item) {
            if ($item['slug'] === $slug) {
                $community = $item;
            }
        }
        return $community;
    }

    public function communityPageText(array $message): string
    {
        return <<<HTML
            <b>{$message['name']}</b>
        
        <i>{$message["about"]}</i>
        
        Havolaga kirish uchun quyidagi tugmalardan foydalaning:  
        HTML;
    }
}