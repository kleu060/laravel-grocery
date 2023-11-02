<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\PnsProduct;
use App\Models\ProductPrice;
use App\Models\Category;
use App\Http\Controllers\CategoryController;

use ArielMejiaDev\LarapexCharts\LarapexChart;


use App\Charts\ProductPriceHistoryChart;

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
        echo "<br /><br />";
        $categories = [];
        $categories[] = new CategoryController(41, "drink", 6);
        $categories[] = new CategoryController(50, "pantry", 2);
        $categories[] = new CategoryController(15, "baby", 3);
        $categories[] = new CategoryController(49, "beer", 4);
        $categories[] = new CategoryController(50, "chill", 5);
        $categories[] = new CategoryController(50, "fresh", 1);

        $total = 0;
        foreach ($categories as $a){
            
            $max = $a->max;
            $file = $a->file;
            $category_id = $a->category_id;
            for ( $i = 1; $i <= $max; $i++){
                $folder = "pns_products";
                $filename= $folder."/".$file."_".$i.".txt";
                if (Storage::disk('local')->exists($filename)) {
                    // $products = Storage::disk('local')->get($filename);
                    // $products_arr = preg_split('/\+===\+/', $products);
                    $products_arr = $this->readFileReturnProductsArr($filename);
                    foreach ($products_arr as $product){
                        if (trim($product) != "" ){
                            $product_arr = json_decode($product, true);                
                            $product_id = $this->saveProduct($product_arr, $category_id);
                            $this->saveProductPrice($product_arr, $product_id);
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

    /**
     * Create new Product if not already exist
     * 
     * @param mixed $product_arr
     * @param mixed $category_id
     * 
     * @return int product id
     */
    public function saveProduct($product_arr, $category_id): int
    {
        
        $pnsProduct = PnsProduct::where("productId", $product_arr["productId"])
            ->where("category_id", $category_id)
            ->first();

            // var_dump($pnsProduct);exit();
        if ( $pnsProduct !=  NULL){       // Product already exist in PNS product table
            echo "product exist: ".$product_arr["productId"]." created: ".$pnsProduct->id."<br />";
            $product_id = $pnsProduct->id;
        }
        else{                                //Product does not exist in PNS product table, create new product
            $newPnsProduct = new PnsProduct();
            $newPnsProduct->productId = $product_arr["productId"];
            $newPnsProduct->category_id = $category_id;
            $newPnsProduct->productName = $product_arr["productName"];

            // $date = "2023-10-23 00:00:00";
            // $newPnsProduct->created_at = $date;
            // $newPnsProduct->updated_at = $date;

            $newPnsProduct->save();
            echo "New Product: ".$product_arr["productId"]." created: ".$newPnsProduct->id."<br />";
            $product_id = $newPnsProduct->id;
        }
        return $product_id ?? -1;


    }

    /**
     * Add Product price to DB
     * 
     * @param mixed $product_arr
     * @param mixed $product_id
     * 
     * @return void
     */
    public function saveProductPrice ($product_arr, $product_id):void
    {
        
        $pnsProductPrice = new ProductPrice();
        $pnsProductPrice->pns_product_id = $product_id;
        $pnsProductPrice->PriceMode = $product_arr["ProductDetails"]["PriceMode"];
        $pnsProductPrice->PricePerItem = $product_arr["ProductDetails"]["PricePerItem"];
        $pnsProductPrice->HasMultiBuyDeal = $product_arr["ProductDetails"]["HasMultiBuyDeal"];
        $pnsProductPrice->MultiBuyDeal = $product_arr["ProductDetails"]["MultiBuyDeal"];
        $pnsProductPrice->PricePerBaseUnitText = $product_arr["ProductDetails"]["PricePerBaseUnitText"];
        $pnsProductPrice->MultiBuyBasePrice = $product_arr["ProductDetails"]["MultiBuyBasePrice"];
        $pnsProductPrice->MultiBuyPrice = $product_arr["ProductDetails"]["MultiBuyPrice"];
        $pnsProductPrice->MultiBuyQuantity = $product_arr["ProductDetails"]["MultiBuyQuantity"];
        $pnsProductPrice->PromoBadgeImageLabel = $product_arr["ProductDetails"]["PromoBadgeImageLabel"];

        // $date = "2023-10-23 00:00:00";
        // $pnsProductPrice->created_at = $date;
        // $pnsProductPrice->updated_at = $date;

        echo "".$product_arr["productId"]." Price update<br />";
        $pnsProductPrice->save();
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

        
        // $today = date("Y-m-d");
        $today = Storage::disk('local')->get("latest_date.txt");

        
        if ( $category_id == ""){


            $products = DB::table("pns_products")
            ->join("product_prices", "product_prices.pns_product_id", "=" , "pns_products.id")
            ->join("categories", "pns_products.category_id", "=" , "categories.id")
            ->whereDate("product_prices.created_at", $today)
            ->select(["pns_product_id",  "PriceMode", "PricePerItem", "categories.name as category_name", "productName", "productId"])
            ->get();

            // dd( $products) ; exit();
            
        }
        else{

            $products = DB::table("pns_products")
            ->join("product_prices", "product_prices.pns_product_id", "=" , "pns_products.id")
            ->join("categories", "pns_products.category_id", "=" , "categories.id")
            ->whereDate("product_prices.created_at", $today)
            ->where("pns_products.category_id", $category_id)
            ->select(["pns_product_id",  "PriceMode", "PricePerItem", "categories.name as category_name", "productName", "productId"])
            ->get();

            // dd( $products) ; exit();
        }
        // dd($products);
        // print_r($products);
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

        $chart = new ProductPriceHistoryChart(new LarapexChart());
        $categories = Category::all();

        $product_id = $request->route("id");


        $products = DB::table("pns_products")
            ->join("product_prices", "product_prices.pns_product_id", "=" , "pns_products.id")
            // ->join("categories", "pns_products.category_id", "=" , "categories.id")
            ->groupBy(["productId", DB::raw('DATE(product_prices.created_at)')])
            // ->where("pns_products.category_id", $category_id)
            ->select(["productId", "product_prices.created_at",  "PriceMode", "PricePerItem","productName"])
            ->where("productId" , $product_id)
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

            
        return Inertia::render('PnsProduct', [
            'products' => $products,
            'categories' => $categories,
            'product_name' => $product_name,
            'chart' => $chart

        ]);



     }
}
