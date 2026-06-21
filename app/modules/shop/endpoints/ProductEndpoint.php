<?php

class ProductEndpoint
{
    public function index()
    {
        header('Content-Type: application/json');

        $data = [
            [
                'id' => 1,
                'name' => 'Yonex Astrox 99'
            ],
            [
                'id' => 2,
                'name' => 'Yonex Duora Z Strike'
            ]
        ];

        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    }
}