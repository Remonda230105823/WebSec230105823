<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

	public function list(Request $request) {

		$query = Product::select("products.*");

		$query->when($request->keywords, 
		fn($q)=> $q->where("name", "like", "%$request->keywords%"));

		$query->when($request->min_price, 
		fn($q)=> $q->where("price", ">=", $request->min_price));
		
		$query->when($request->max_price, fn($q)=> 
		$q->where("price", "<=", $request->max_price));
		
		$query->when($request->order_by, 
		fn($q)=> $q->orderBy($request->order_by, $request->order_direction??"ASC"));

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null) {

		if(!auth()->user()->hasPermissionTo('edit_products')) abort(401);

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {

		if(!auth()->user()->hasPermissionTo('edit_products')) abort(401);

		$this->validate($request, [
	        'code' => ['required', 'string', 'max:32'],
	        'name' => ['required', 'string', 'max:128'],
	        'model' => ['required', 'string', 'max:256'],
	        'description' => ['required', 'string', 'max:1024'],
	        'price' => ['required', 'numeric'],
            'quantity' => ['required', 'integer', 'min:0'],
            'photo_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
	    ]);

		$product = $product??new Product();
		
		// Handle the photo upload
		if ($request->hasFile('photo_file')) {
		    $image = $request->file('photo_file');
		    $filename = time() . '.' . $image->getClientOriginalExtension();
		    
		    // Move the uploaded file to the public/images directory
		    $image->move(public_path('images'), $filename);
		    
		    // Save the filename to the product
		    $product->photo = $filename;
		} else {
		    // Keep the current photo if no new one is uploaded
		    $product->photo = $request->current_photo;
		}
		
		// Fill other fields from the request
		$product->code = $request->code;
		$product->name = $request->name;
		$product->model = $request->model;
		$product->description = $request->description;
		$product->price = $request->price;
		$product->quantity = $request->quantity;
		
		$product->save();

		return redirect()->route('products_list');
	}

	public function delete(Request $request, Product $product) {

		if(!auth()->user()->hasPermissionTo('delete_products')) abort(401);

		$product->delete();

		return redirect()->route('products_list');
	}

    public function buyProduct(Request $request, Product $product)
    {
        if(!auth()->user()->hasPermissionTo('buy_products')) abort(401);
        
        $user = auth()->user();
        
        // Check if user has enough credits
        if($user->credits < $product->price) {
            // Return view for insufficient credit page
            return view('products.insufficient_credit', [
                'product' => $product,
                'user' => $user,
                'missing' => $product->price - $user->credits
            ]);
        }
        
        // Check if product is available
        if($product->quantity <= 0) {
            return redirect()->route('products_list')
                ->withErrors('This product is out of stock.');
        }
        
        // Create purchase record
        $purchase = new \App\Models\Purchase();
        $purchase->user_id = $user->id;
        $purchase->product_id = $product->id;
        $purchase->price = $product->price;
        $purchase->quantity = 1;
        $purchase->save();
        
        // Update user credits
        $user->credits -= $product->price;
        $user->save();
        
        // Reduce product quantity
        $product->quantity -= 1;
        $product->save();
        
        return redirect()->route('products_list')
            ->with('success', 'Product purchased successfully.');
    }
    
    public function myPurchases(Request $request)
    {
        $user = auth()->user();
        $purchases = \App\Models\Purchase::where('user_id', $user->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('products.my_purchases', compact('purchases'));
    }
} 