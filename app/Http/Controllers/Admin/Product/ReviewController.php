<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Front\Order;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\SentimenService;
use App\Models\Front\Review;
use App\Models\User;
use App\Models\Admin\Product;
use App\Services\ReviewService;
use GuzzleHttp\Promise\Create;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    private $productService;
    private $sentimenService;
    private $reviewService;

    public function __construct(ProductService $productService, SentimenService $sentimenService, ReviewService $reviewService)
    {
        $this->productService   = $productService;
        $this->sentimenService  = $sentimenService;
        $this->reviewService    = $reviewService;
    }

    public function create(Request $request)
    {
        return $this->reviewService->create($request);
    }

    public function getReviewByProductId($id)
    {
        $product_id = simple_decrypt($id);
        $reviews    = $this->reviewService->getReviewByProductId($product_id);

        if (request()->ajax()) {
            return datatables()::of($reviews)
            ->addColumn('review_date', function($data) {
                return getDateTimeIndo($data->created_at);
            })
            ->addColumn('name', function($data) {
                return $data->users->first_name . ' ' . $data->users->last_name;
            })
            ->rawColumns(['name', 'review_date'])
            ->addIndexColumn()
            ->make(true);
        }
    }
    public function createDummy()
    {
        $faker = Faker::create('id_ID');
        $file = file_get_contents(asset('499804152.csv'));

        $arr = explode("\n", $file);
        $clean = str_replace('"', '', $arr);
        $item = explode(";", $clean[0]);
        DB::beginTransaction();
        try {

            for ($i = 0; $i < 45; $i++)
            {
                $order = Order::with(['payments'])->has('payments')->inRandomOrder()->take(1)->first();
                $item = explode(";", $clean[$i]);
                $sentimen = $this->sentimenService->getNaiveBayesClassification($item[0]);
                $order_id = $order->id;
                $user_id = $order->user_id;
                $product_id = $order->orderproducts[0]->product_id;
                $rating = $item[1];
                $message = $item[0];
                $stemming = ($sentimen != null) ? "[".join("] [", $sentimen['stemming'])."]" : null;
                $status = ($sentimen != null) ? $sentimen['status'] : 9;

                Review::Create([
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'order_id' => $order_id,
                    'rating' => $rating,
                    'message' => $message,
                    'hasil_stemming' => $stemming,
                    'status_sentimen' => $status
                ]);
            }

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
