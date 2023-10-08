<?php

namespace Database\Seeders;

use Faker\Provider\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferDetailSeeder extends Seeder
{
    private function randomDate($firstDate, $secondDate, $format = 'Y-m-d'): string
    {
        $firstDateTimeStamp = strtotime($firstDate);
        $secondDateTimeStamp = strtotime($secondDate);

        if ($firstDateTimeStamp < $secondDateTimeStamp) {
            return date($format, mt_rand($firstDateTimeStamp, $secondDateTimeStamp));
        }

        return date($format, mt_rand($secondDateTimeStamp, $firstDateTimeStamp));
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $n = 1;
        for ($i = 10; $i >= 1 ; $i--) {
            DB::table('transfer_detail')->insert([
                'transfer_id' => $n,
                'user_id' => $i,
                'date' => $this->randomDate('17-08-2023', '01-10-2023'),
                'time' => DateTime::time('h:i:s'),
                'amount' => rand(100_000, 5_000_000)
            ]);
            $n++;
        }
    }
}
