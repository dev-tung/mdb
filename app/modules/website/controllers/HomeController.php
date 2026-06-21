<?php

class HomeController
{
    public function index(): void
    {
        View::render('home/index');
    }


}