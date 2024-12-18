<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}

trait AccountTrait
{
    public function viewAccount()
    {
        return view(strtolower(class_basename(static::class)) . '.viewAccount');
    }
}
