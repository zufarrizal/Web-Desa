<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(ProgramSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(AnnouncementSeeder::class);
        $this->call(ComplaintSeeder::class);
        $this->call(LetterSettingSeeder::class);
    }
}
