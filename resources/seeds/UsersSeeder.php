<?php

use Faker\Factory as Faker;
use Hash;
use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker::create();
        $data = [];

        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                "name" => $faker->name,
                "password" => Hash::create("foo", PASSWORD_BCRYPT),
                "email" => $faker->email
            ];
        }

        $this->insert('users', $data);
    }
}
