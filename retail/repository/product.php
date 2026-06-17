<?php

function get_products(): array
{
    return [
        'racquet' => [
            ['name'=>'Yonex Astrox 100ZZ','brand'=>'yonex','price'=>5990000],
            ['name'=>'Yonex Astrox 99 Pro','brand'=>'yonex','price'=>5200000],
            ['name'=>'Yonex Nanoflare 1000Z','brand'=>'yonex','price'=>5490000],
            ['name'=>'Victor Thruster F','brand'=>'victor','price'=>4200000],
            ['name'=>'Victor Auraspeed 90K','brand'=>'victor','price'=>3900000],
        ],
        'shoes' => [
            ['name'=>'Yonex SHB 65Z3','brand'=>'yonex','price'=>2990000],
            ['name'=>'Victor P9200','brand'=>'victor','price'=>3200000],
            ['name'=>'Mizuno Wave Fang','brand'=>'mizuno','price'=>2800000],
        ],
        'bag' => [
            ['name'=>'Yonex Pro Bag','brand'=>'yonex','price'=>1490000],
            ['name'=>'Victor BR9209','brand'=>'victor','price'=>1390000],
            ['name'=>'Lining ABJT','brand'=>'lining','price'=>1200000],
        ],
        'accessory' => [
            ['name'=>'Quấn cán Yonex','brand'=>'yonex','price'=>50000],
            ['name'=>'BG80 String','brand'=>'yonex','price'=>120000],
            ['name'=>'Victor Grip Powder','brand'=>'victor','price'=>90000],
        ]
    ];
}