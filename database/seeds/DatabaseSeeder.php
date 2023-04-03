<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call(UserSeeder::class);

       Setting::create([
            'code' => 'application_logo',
            'type' => 'FILE',
            'label' => 'Application Logo',
            'value' => 'application_logo.png',
            'hidden' => '0',
        ]);

        Setting::create([
            'code' => 'application_title',
            'type' => 'TEXT',
            'label' => 'Application Title',
            'value' => 'Particle',
            'hidden' => '0',
        ]);

        Setting::create([
            'code' => 'favicon_logo',
            'type' => 'FILE',
            'label' => 'Favicon Logo',
            'value' => 'favicon_logo.png',
            'hidden' => '0',
        ]);

        Setting::create([
            'code' => 'copyright',
            'type' => 'TEXT',
            'label' => 'Copy Right',
            'value' => 'Particle',
            'hidden' => '0',
        ]);
    }
}
