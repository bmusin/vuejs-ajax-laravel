<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Order;
use App\OrderField;
use App\OrderFieldValue;
use Datetime;
use Redis;

class TableController extends Controller
{
    public function index() {
      return view('table');
    }

    public function new() {
      $order = new Order;
      $order->name = $this->generateRandomString();

      $order->creator_id = 1;
      $order->save();

      $fields = OrderField::all();
      foreach ($fields as $field) {
        $orderFieldValue = new OrderFieldValue;
        $orderFieldValue->order_id = $order->id;
        $orderFieldValue->field_id = $field->id;
        $orderFieldValue->value = $this->generateRandomString();
        $orderFieldValue->save();
      }

      return response()->json($this->getOrders(false));
    }

    public function orders() {
      return response()->json($this->getOrders(false));
    }

    public function getOrders($isAfterReset) {
      $now = (new DateTime())->getTimestamp();

      $redis = new Redis;
      $redis->connect('127.0.0.1', 6379);

      $response = [];
      if (!$isAfterReset  || (!$redis->exists('ts')) ||
          (!$redis->exists('orders')) ||
          ($now - unserialize($redis->get('ts')) >= 5 * 60))
      {
        $orders = Order::all();
        foreach ($orders as $order) {
          $item = [
            ['ID', $order->id],
            ['C reator ID', $order->creator_id],
            ['Name', $order->name]
          ];

          foreach (OrderField::all() as $field) {
            $value = OrderFieldValue
              ::where('order_id', $order->id)
              ->where('field_id', $field->id)->first()->value;
            $item[] = [$field->name, $value];
          }

          $response[] = $item;
        }
        $redis->set('orders', serialize($response));
      } else {
        $response = unserialize($redis->get('orders'));
      }

      $redis->set('ts', serialize((new DateTime())->getTimestamp()));
      $redis->close();
      return $response;
    }

    public function reset() {
        $redis = new Redis;
        $redis->connect('127.0.0.1', 6379);
        $redis->del('ts');
        $redis->del('orders');
        $redis->close();

        Order::query()->delete();
        return response()->json($this->getOrders(true));
    }

    private function generateRandomString($length = 10) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; ++$i) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }
}
