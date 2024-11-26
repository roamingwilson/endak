<?php

namespace App\Http\Controllers\Furniture;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\FurnitureTransportationProduct;

class ProductFurnitureTransportationsController extends Controller
{
    public function index(Request $request){
        
        $ids = $request->bulk_ids;
        $now = Carbon::now()->toDateTimeString();
        // delete 
        if ($request->bulk_action_btn === 'delete' &&  is_array($ids) && count($ids)) {
            FurnitureTransportationProduct::whereIn('id', $ids)->delete();
            return back()->with('success', __('general.deleted_successfully'));
        }
        // update status
        if ($request->bulk_action_btn === 'update_status' &&  is_array($ids) && count($ids)) {
            $status = ($request->status == 1) ? 'active' : 'disactive';
            $data = ['status' => $status];
            FurnitureTransportationProduct::whereIn('id', $ids)->update($data);
            return back()->with('success', __('تم تحديث حالة المنتج'));
        }
        $products = FurnitureTransportationProduct::orderBy("created_at","asc")->paginate(10);
        return view('admin.furniture_transportations.products.index' , compact('products'));
    }

    public function create(){
        return view('admin.furniture_transportations.products.create');
    }

    public function store(Request $request){
        
        $request->validate([
            'name_ar'           => "required",
            'name_en'           => "required",
            'image'             => "required",
        ]);


        $path = uploadImage( $request , 'furniture_transportation_products' , 'image');

        FurnitureTransportationProduct::create([
            'name_ar'                   => clean_html($request->name_ar),
            'name_en'                   => clean_html($request->name_en),
            'image'                     => $path,
        ]);

        return redirect()->route('main_furniture_transportations.product')->with('success' , __('تم اضافة المنتج'));

    }

    public function edit($id)
    
    {
        $product = FurnitureTransportationProduct::whereId($id)->first();
        if (!$product){
            abort(404);
        }
        return view('admin.furniture_transportations.products.edit', compact('product'));
    }

    public function update(Request $request, $id){
        $product = FurnitureTransportationProduct::whereId($id)->first();
        if ( ! $product){
            abort(404);
        }

        $old_image = $product->image;
        $path = uploadImage( $request , 'furniture_transportation_products' , 'image');


        $data = [
            'name_ar'                  => clean_html($request->name_ar),
            'name_en'                  => clean_html($request->name_en),
            'image'                    => ($path != 0) ? $path : $old_image,
        ];


        $product->update($data);

        if ($old_image && $path) {
            Storage::disk('public')->delete($old_image);
        }

        return redirect()->route('main_furniture_transportations.product')->with('success' , "تم تحديث المنتج بنجاح");

    }
    public function destroy($id)
    {

        $Product = FurnitureTransportationProduct::where('id',$id)->first();
        $Product->delete();

        return redirect()->route('main_furniture_transportations.product')->with('success' , "تم الحذف بنجاح");
    }
}
