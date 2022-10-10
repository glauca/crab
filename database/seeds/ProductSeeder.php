<?php

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'type'   => 1,
                'titles' => [
                    '6.0公',
                    '5.0公',
                    '4.5公',
                    '4.0公',
                    '3.5公',
                    '3.0公',
                    '2.5公',
                    '2.0公',
                    '中公',
                ],
            ],
            [
                'type'   => 2,
                'titles' => [
                    '5.0母',
                    '4.0母',
                    '3.5母',
                    '3.0母',
                    '2.89母',
                    '2.8母',
                    '2.57母',
                    '2.5母',
                    '2.0母',
                    '1.68母',
                    '1.5母',
                    '中母',
                ],

            ],
            [
                'type'   => null,
                'titles' => [
                    '老头蟹',
                ],
            ],
        ];

        foreach ($items as $item) {
            foreach ($item['titles'] as $title) {
                $product        = new Product;
                $product->type  = $item['type'];
                $product->title = $title;
                $product->save();
            }
        }
    }
}
