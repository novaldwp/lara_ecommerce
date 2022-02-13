<?php

namespace Database\Seeders;

use App\Models\Front\Order;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Front\Province;
use App\Models\Front\City;
use Carbon\Carbon;
use App\Models\Admin\Payment;
use App\Models\Admin\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i = 0; $i < 15; $i++)
        {
            $products = [];
            $orders   = [];
            $payments = [];
            $today = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
            $arrCourier = ['jne', 'pos', 'tiki'];
            $arrCourierService = [];
            $faker = Faker::create('id_ID');
            $randomCourier = array_rand($arrCourier, 1);
            $fakeAddress   = explode(",", $faker->address);

            if ($randomCourier == "jne")
            {
                $arrCourierService = ['oke', 'reg', 'yes'];
            }
            else if ($randomCourier == "pos")
            {
                $arrCourierService = ['express', 'kilat', 'ekonomi'];
            }
            else {
                $arrCourierService = ['sameday', 'ekonomi', 'reguler'];
            }

            $amount             = $faker->numberBetween(1, 3);
            $product            = Product::inRandomOrder()->take(1)->first();
            $user_id            = User::role('customer')->inRandomOrder()->take(1)->first()->id;
            $province_id        = Province::inRandomOrder()->take(1)->first()->id;
            $city_id            = City::whereProvinceId($province_id)->inRandomOrder()->take(1)->first()->id;
            $street             = $fakeAddress[0];
            $postcode           = $faker->postcode;
            $first_name         = $faker->firstName;
            $last_name          = $faker->lastName;
            $phone              = str_replace(" ", "", str_replace("(+62)", "", $faker->phoneNumber));
            $base_price         = $product->price * $amount;
            $shipping_cost      = $faker->numberbetween(8000, 108000);
            $total_price        = (int) $base_price + $shipping_cost;
            $shipping_courier   = $randomCourier;
            $shipping_service   = array_rand($arrCourierService, 1);
            $order_id           = Order::latest()->first()->id + $i + 1;
            $order_code         = Order::GenerateCode() + $i;
            $orders = [
                'code'              => $order_code,
                'user_id'           => $user_id,
                'province_id'       => $province_id,
                'city_id'           => $city_id,
                'street'            => $street,
                'postcode'          => $postcode,
                'first_name'        => $first_name,
                'last_name'         => $last_name,
                'phone'             => $phone,
                'base_price'        => $base_price,
                'shipping_cost'     => $shipping_cost,
                'total_price'       => $total_price,
                'shipping_courier'  => $arrCourier[$shipping_courier],
                'shipping_service'  => $arrCourierService[$shipping_service],
                'order_date'        => date('Y-m-d H:i:s', strtotime($today)),
                'payment_due'       => $today->addDays(Payment::$expiry_duration),
                'status'            => 4
            ];

            $products = [
                'order_id'      => $order_id,
                'product_id'    => $product->id,
                'amount'        => $amount,
                'sub_total'     => $base_price
            ];

            $payments = [
                'code'          => Payment::generateCode($order_code),
                'amount'        => $total_price,
                'payment_type'  => "bank_transfer",
                'va_number'     => $faker->numberBetween(00000000000, 26702497214),
                'bank'          => "bca",
                'store'         => null,
                'bill_key'      => null,
                'biller_code'   => null,
                'token'         => null,
                'payload'       => null,
                'status'        => "settlement"
            ];
            \DB::beginTransaction();
            try {
                $order = Order::create($orders);
                $order->orderproducts()->create($products);
                $order->payments()->create($payments);
                \DB::commit();
                $message = "sukses";
            } catch (\Exception $e) {
                \DB::rollback();
                $message = $e->getMessage();
            }
        }
    }
}
