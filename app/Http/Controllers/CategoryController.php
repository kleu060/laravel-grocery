<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    protected  int $max;
    protected  String $file;
    protected  int $category_id;

    /**
     * Category Contructor
     * 
     * @param int $max
     * @param String $file
     * @param int $category_id
     * 
     * 
     */
    function __construct(int $max, String $file, int $category_id){
        
        $this->max = $max;
        $this->file = $file;
        $this->category_id = $category_id;
    }

    /**
     * Retrieve Category Attribute
     * 
     * @param mixed $key
     * 
     * @return [type]
     */
    public function __get( $key )
    {
        return $this->{$key};
    }

    /**
     * Set Category Attribute
     * @param mixed $key
     * @param mixed $value
     * 
     * @return [type]
     */
    public function __set( $key, $value )
    {
        $this->{$key} = $value;
    }
}
