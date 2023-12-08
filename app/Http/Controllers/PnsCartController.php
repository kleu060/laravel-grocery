<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PnsCart;


class PnsCartController extends Controller
{
    //
    /**
     * @param Request $request
     * 
     * @return [type]
     */
    public function addToCart(Request $request)
    {
        echo "add to cart";
        $pnsCart = new PnsCart();
        // $pnsCart->

    }
}
