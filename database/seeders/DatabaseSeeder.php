<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Moveon\EmailTemplate\Database\Seeders\PermissionSeeder as EmailTemplatePermissionSeeder;
use Moveon\Image\Database\Seeders\PermissionSeeder as ImagePermissionSeeder;
use Moveon\User\Database\Seeders\PermissionSeeder as UserPermissionSeeder;
//use Moveon\Customer\Database\Seeders\PermissionSeeder as CustomerPermissionSeeder;
use Moveon\Tag\Database\Seeders\PermissionSeeder as TagPermissionSeeder;
use Moveon\Product\Database\Seeders\PermissionSeeder as ProductPermissionSeeder;
use Moveon\Setting\Database\Seeders\PermissionSeeder as SettingPermissionSeeder;
use Moveon\Setting\Database\Seeders\IntegrationSeeder;
use Moveon\Segmentation\Database\Seeders\PermissionSeeder as SegmentationPermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UserPermissionSeeder::class);
        $this->call(EmailTemplatePermissionSeeder::class);
        $this->call(ImagePermissionSeeder::class);
        $this->call(SegmentationPermissionSeeder::class);
        $this->call(TagPermissionSeeder::class);
        $this->call(ProductPermissionSeeder::class);
        $this->call(SettingPermissionSeeder::class);
        $this->call(IntegrationSeeder::class);
    }
}
