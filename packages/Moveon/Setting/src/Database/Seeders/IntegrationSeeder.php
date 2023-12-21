<?php

namespace Moveon\Setting\Database\Seeders;

use Illuminate\Database\Seeder;
use Moveon\Setting\Models\Integration;

class IntegrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $integrations = [
            [
                "name"         => "Shopify",
                "slug"         => "shopify",
                "type"         => "ecommerce",
                "logo"         => "images/shopify_logo.png",
                "details"      => "<h1>Shopify details</h1>",
                "instructions" => "<h1>How to install shopify app</h1>",
                "status"       => "active"
            ],
            [
                "name"         => "Private platform",
                "slug"         => "private-platform",
                "type"         => "private",
                "logo"         => "images/private_logo.png",
                "details"      => "<h1>Private platform details</h1>",
                "instructions" => "<h1>How to integrate private platform with Marketing Tool</h1>",
                "status"       => "active"
            ]
        ];

        foreach ($integrations as $integration) {
            $existIntegration = Integration::query()->where('name', $integration['name'])->first();
            if (!$existIntegration) {
                Integration::create($integration);
            }
        }
    }
}
