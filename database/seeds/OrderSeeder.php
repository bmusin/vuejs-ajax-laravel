<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Order;
use App\OrderField;
use App\OrderFieldValue;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
       OrderFieldValue::query()->delete();
       OrderField::query()->delete();
       Order::query()->delete();

       DB::table('orders')->insert([[
         'name' => 'Order_1',
         'creator_id' => 1
       ], [
         'name' => 'Order 2',
         'creator_id' => 2
       ]]);

       DB::table('order_fields')->insert([
         ['name' => 'description'],
         ['name' => 'author']
       ]);

       DB::table('order_field_values')->insert([
         ['order_id' => 1, 'field_id' => 1, 'value' => 'First order'],
         ['order_id' => 1, 'field_id' => 2, 'value' => 'Ivan Ivanov'],
         ['order_id' => 2, 'field_id' => 1, 'value' => 'Second order'],
         ['order_id' => 2, 'field_id' => 2, 'value' => 'Petr Petrov']
       ]);
     }
}
