<?php



namespace App\Http\Controllers;

use App\Admin;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CategoryController extends BaseController
{
    public function getCategories() {
        $categories = Category::all();
        return [
            'list' => $categories,
            'RequestSucces' =>  true
        ];
    }

    public function getCategory(Request $request) {
        $category = Category::find($request->category_id);
        if($category) {
            return [
                'object' => $category,
                'RequestSuccess' => true
            ];
        }
        return [
          'object' => null,
          'RequestSuccess' => false,
          'msg' => 'Không tìm thấy thể loại'
        ];
    }

    public function insertCategory(Request $request) {
        $data = $request->all();
        $category = new Category($data);
        if(!DB::table('category')->where('name',$category->name)->first()) {
            $category->save();
            return [
                'msg' => 'Thêm thành công',
                'RequestSuccess' => true
            ];
        }
        return [
            'msg' => 'Tên này đã tồn tại, vui lòng nhập lại',
            'RequestSuccess' => false
        ];
    }

    public function updateCategory(Request $request) {
        $category = Category::find($request->category_id);
        if($category) {
            $category->name = $request->name;
            $category->icon_class= $request->icon_class;
            $category->save();
            return [
                'msg' => 'Sửa thành công',
                'list' => Category::all(),
                'RequestSuccess' => true
            ];
        }
        return [
            'msg' => 'Không tìm thấy thể loại',
            'RequestSuccess' => false
        ];
    }

    public function deleteCategory(Request $request) {
        $category = Category::find($request->category_id);
        if($category) {
            $category->disable = false;
            $category->save();
            return [
                'msg' => 'Xóa thành công',
                'RequestSuccess' => true
            ];
        }
        return [
            'msg' => 'Không tìm thấy thể loại',
            'RequestSuccess' => false
        ];
    }
}