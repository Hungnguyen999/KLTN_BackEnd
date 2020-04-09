<?php


namespace App\Http\Controllers;

use App\Category;
use Illuminate\Routing\Controller as BaseController;

class GuestController extends BaseController
{
    public function getCategory() {
        return [
            'list' => Category::with('topicsEnable','topicsEnable.courseEnable')->where('disable',false)->get()
        ];
    }
}