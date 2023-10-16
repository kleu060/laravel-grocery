<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\PnsProduct;
use App\Models\Category;
use App\Http\Controllers\CategoryController;

use ArielMejiaDev\LarapexCharts\LarapexChart;


use App\Charts\MonthlyUsersChart;

use Inertia\Inertia;
use Inertia\Response;
use DB;


class PnsController extends Controller
{
    //

    /**
     * Import PNS products to database
     * @param Request $request
     * 
     * @return void
     */
    public function index(Request $request):void
    {
 
        $categories = [];
        $categories[] = new CategoryController(41, "drink", 6);
        $categories[] = new CategoryController(50, "pantry", 2);
        $categories[] = new CategoryController(15, "baby", 3);
        $categories[] = new CategoryController(49, "beer", 4);
        $categories[] = new CategoryController(50, "chill", 5);

        $total = 0;
        foreach ($categories as $a){
            
            $max = $a->max;
            $file = $a->file;
            $category_id = $a->category_id;
            for ( $i = 1; $i <= $max; $i++){

                $filename= "pns_products/".$file."_".$i.".txt";
                if (Storage::disk('local')->exists($filename)) {
                    // $products = Storage::disk('local')->get($filename);
                    // $products_arr = preg_split('/\+===\+/', $products);
                    $products_arr = $this->readFileReturnProductsArr($filename);
                    foreach ($products_arr as $product){
                        if (trim($product) != "" ){
                            $product_arr = json_decode($product, true);                
                            $this->saveProduct($product_arr, $category_id);
                            $total ++;
                        }
                    }    
                }
            }
        }
        echo "Total ". $total . " products created<br />";
        $today = date("Y-m-d");
        Storage::disk('local')->put("latest_date.txt", $today);
    }

    /**
     * Read PNS product file
     * @param string $filename
     * 
     * @return Array $products_arr
     */
    public function readFileReturnProductsArr($filename): Array
    {
        $products = Storage::disk('local')->get($filename);
        $products_arr = preg_split('/\+===\+/', $products);
        return $products_arr;
    }

    public function saveProduct($product_arr, $category_id){
        $pnsProduct = new PnsProduct();
        $pnsProduct->productId = $product_arr["productId"];
        $pnsProduct->category_id = $category_id;
        $pnsProduct->productName = $product_arr["productName"];
        $pnsProduct->PriceMode = $product_arr["ProductDetails"]["PriceMode"];
        $pnsProduct->PricePerItem = $product_arr["ProductDetails"]["PricePerItem"];
        $pnsProduct->HasMultiBuyDeal = $product_arr["ProductDetails"]["HasMultiBuyDeal"];
        $pnsProduct->MultiBuyDeal = $product_arr["ProductDetails"]["MultiBuyDeal"];
        $pnsProduct->PricePerBaseUnitText = $product_arr["ProductDetails"]["PricePerBaseUnitText"];
        $pnsProduct->MultiBuyBasePrice = $product_arr["ProductDetails"]["MultiBuyBasePrice"];
        $pnsProduct->MultiBuyPrice = $product_arr["ProductDetails"]["MultiBuyPrice"];
        $pnsProduct->MultiBuyQuantity = $product_arr["ProductDetails"]["MultiBuyQuantity"];
        $pnsProduct->PromoBadgeImageLabel = $product_arr["ProductDetails"]["PromoBadgeImageLabel"];
        echo "".$product_arr["productId"]."created<br />";
        $pnsProduct->save();

    }

    /**
     * List PNS procuts
     * 
     * @param Request $request
     * 
     *  @return \Inertia\Response
     */

    public function listProduct(Request $request): \Inertia\Response
    {
        $category_id = $request->route('category_id');
        
        $categories = Category::all();

        
        $today = date("Y-m-d");
        $today = Storage::disk('local')->get("latest_date.txt");
        
        if ( $category_id == ""){
            $products = PnsProduct::with(["category"])->whereDate("created_at", $today)->get();
        }
        else{
            $products = PnsProduct::with(["category"])->where("category_id", $category_id)->whereDate("created_at", $today)->get();
        }


        return Inertia::render('PnsProductList', [
            'products' => $products,
            'category_id' => $category_id,
            'categories' => $categories,

        ]);
    }

    /**
     * Show Prict history of a producdt
     * @param Request $request
     * 
     * @return \Inertia\Response
     */
    public function product( Request $request ): \Inertia\Response
    {

        $chart = new MonthlyUsersChart(new LarapexChart());
        $categories = Category::all();

        $product_id = $request->route("id");
        $products = PnsProduct::select(["productId", "PriceMode", "PricePerItem", "created_at", "productName"])
        ->where("productId" , $product_id)
        ->groupBy(["productId", DB::raw('DATE(created_at)')])
        ->get();

        $product_name = $products[0]->productName;
        $date = [];
        $price = [];
        foreach ($products as $product){
            $price[] = $product->PricePerItem;
            $date[] = date("d/m/Y", strtotime($product->created_at));
        }
        $chart = $chart->build($price, $date, $product_name);
        $chart["options"]["yaxis"]["min"] = min($price) - 2;
        $chart["options"]["yaxis"]["max"] = max($price) + 2;
        $chart["options"]["xaxis"]["labels"]["format"] = 'dd/MM';

    
        // dd($chart);
        
        return Inertia::render('PnsProduct', [
            'products' => $products,
            'categories' => $categories,
            'product_name' => $product_name,
            'chart' => $chart

        ]);



     }
}
