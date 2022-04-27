<?php

namespace Database\Seeders;

use App\Models\Credential;
use App\Models\Module;
use Illuminate\Database\Seeder;

class CredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::all()->each(fn (Module $module) =>
            Credential::factory(rand(1, 60))->create(['module_id' => $module->id])
        );
    }
}
