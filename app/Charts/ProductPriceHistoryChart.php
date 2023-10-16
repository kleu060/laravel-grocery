<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;

class ProductPriceHistoryChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    /**
     * Build Product Price History Line Chart
     * 
     * @return array
     */
    public function build($price, $date, $product_name): array
    {
        return $this->chart->lineChart()
            ->setTitle($product_name)
            ->addData('Price', $price)
            ->setXAxis($date)
            ->toVue();
    }
}
