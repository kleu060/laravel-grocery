<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class MonthlyUsersChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($price, $date, $product_name): array
    {

        $class_methods = get_class_methods($this->chart->lineChart());

        // foreach ($class_methods as $method_name) {
        //     echo "$method_name <br />";
        // }
        // exit();
        return $this->chart->lineChart()
            ->setTitle($product_name)
            // ->setSubtitle('Physical sales vs Digital sales.')
            ->addData('Price', $price)
            ->setXAxis($date)
            ->toVue();
    }
}
