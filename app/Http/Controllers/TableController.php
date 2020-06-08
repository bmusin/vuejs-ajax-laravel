<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Order;
use App\OrderField;
use App\OrderFieldValue;

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
      return response()->json($this->getOrders());
    }

    public function orders() {
      return response()->json($this->getOrders());
    }

    public function getOrders() {
      $response = [];
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
      return $response;
    }

    public function reset() {
        Order::query()->delete();
        return response()->json($this->getOrders());
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
