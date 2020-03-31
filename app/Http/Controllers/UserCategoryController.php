<?php


namespace App\Http\Controllers;
use App\Category;
use Illuminate\Routing\Controller as BaseController;


class UserCategoryController extends BaseController
{
    public function getCategories() {
        $categories = Category::with('topics')->where('disable', false)->get();
        return [
            'list' => $categories,
            'RequestSucces' =>  true
        ];
    }
}