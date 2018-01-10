<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\UserController;
use \App\Cart;
use App\Product;
use App\Attribute;
use App\Goods;
use App\Address;
use App\Prop;
use App\User_prop;
use App\Order;
use App\Order_goods;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class OrderController extends Controller
{
	use DatabaseTransactions;
	public function orderInfo()
	{
		$goods_list=$this->cart();
		//收货人信息
		$man=Address::where([['user_id', '=', '1'],['is_default', '=', '1']])->get()->toArray();
		$man=$man[0];

		//红包优惠券
		$user_prop=User_prop::where(['user_id'=>1])->get()->toArray();
		//红包优惠券
		foreach ($user_prop as $key => $value) {
		   $prop=Prop::where([['id', '=', $user_prop[$key]['prop_id']],['num', '>', '0']])->first()->toArray();
		   $user_prop[$key]['prop_name']=$prop['name'];
		   $user_prop[$key]['full']=$prop['full'];
		   $user_prop[$key]['price']=$prop['price'];
		   $user_prop[$key]['start']=$prop['start_time'];
		   $user_prop[$key]['end']=$prop['end_time'];
		}
		return view('Order/orderinfo',compact('goods_list','man','user_prop'));
	}

	public function cart(){

		//购物车商品信息
		$cart_id=[47,48];
		$goods_list =Cart::whereIn('id',$cart_id)->orderBy('created_at', 'desc')->get()->toArray();

			foreach ($goods_list as $key => $value) {
				$product = Product::find($goods_list[$key]['product_id'])->toArray();
				$goods = Goods::find($product['goods_id'])->toArray();
				$goods_list[$key]['goods'] = $goods['name'];
				$goods_list[$key]['cover'] = $goods['cover'];
				$goods_list[$key]['price'] = ($goods['sell_price'] + $product['price']) * $goods_list[$key]['num'];
				$attr_id = '(' . $product['attribute_id'] . ')';
				$attr = DB::select("select * from xbs_attribute where id in $attr_id");

				$str = "";
				foreach ($attr as $v) {
					$str .= $v->value . ",";
				}
				$goods_list[$key]['attr'] = rtrim($str, ',');
			}
			return $goods_list;
	}

	public function delCart(){
	  $user_id=request('user_id');
	  $cart_id=request('cart_id');
	  $res=Cart::where([['user_id','=',$user_id],['id','=',$cart_id]])->delete();

	  if($res){
	  	$data['error']=0;
	  }else{
	  	$data['error']=1;
	  }
	  echo json_encode($data);
	}


	public function addOrder(Request $request){
		if($request->isMethod('post')){
			$user_id = \Auth::id();
			$u_id=sprintf("%04d",$user_id);
			$order_no=substr(time(),-8).mt_rand(1000,9999).$u_id;
			$cart=$this->cart();
			$address_id=request('address_id');
			$postscript=request('postscript');
			$money=0;
			$prod_id=0;
		foreach ($cart as $key => $value) {
			 $money+=$value['price'];
			 $num=$cart[$key]['num'];
			 $cart_id=$cart[$key]['id'];
			 //Cart::where(['id'=>$cart_id])->delete();
			 $product_id=$cart[$key]['product_id'];
			 //减库存
			// Product::where(['id'=>$product_id])->decrement('num', $num);
			 $order['product_id']=$product_id;
			 $order['goods_num']=$num;
			 $order['goods_price']=$cart[$key]['price'];
		}
		$prop_id=request('prop_id');
		if($prop_id){
			$prop=Prop::where(['id'=>$prop_id])->first()->toArray();
			User_prop::where(['id'=>$prop_id])->update(['status'=>1]);
			$prop_price=$prop['price'];
			$data['is_prop']=1;
		}else{
			$prop_price=0;
			$data['is_prop']=0;
		}
		$real_money=$money-$prop_price;
		$data['order_no']=$order_no;
		$data['user_id']=$user_id;
		$data['order_money']=$money;
		$data['prop_id']=$prop_id;
		$data['real_money']=$real_money;
		$data['address_id']=$address_id;
		$data['postscript']=$postscript;
		$res=Order::create($data);
		if($res){
			$orders=Order::where([['user_id','=',$user_id],['status','=','0']])->orderBy('created_at', 'desc')->first()->toArray();
			$order['order_id']=$orders['id'];
		    Order_goods::create($order);
			$msg['error']=0;
		}else{
			$msg['error']=1;
		}
		echo json_encode($msg);
	}
		
	}

	public function confirmOrder(){
		$user_id=\Auth::id();
		$data=Order::where([['user_id','=',$user_id],['status','=','0']])->orderBy('created_at', 'desc')->first()->toArray();
		return view('Order/addorder',compact('data'));
	}

	//获取收货地址信息
	public function getAddress(){
		 $user_id=request('user_id');
		 $addinfo=Address::where(['user_id'=>$user_id])->get()->toArray();

		 if($addinfo){
		 	$data['error']=0;
		 	$data['content']=$addinfo;
		 }else{
		 	$data['error']=1;
		 }
		 echo json_encode($data);
	}

	//联动改变收货地址
	public function getAdd(){
		$user=request('user');
      
		$addr=Address::where(['id'=>$user])->get()->toArray();
	
		if($addr){
		 	$data['error']=0;
		 	$data['content']=$addr;
		 }else{
		 	$data['error']=1;
		 }
		 echo json_encode($data);
	}

	//提交订单
	public function submitOrder()
	{
		return view('cart/submitOrder');
	}

	//订单列表
	public function list()
	{
			return view('Order/list');
	}

	//物流-跟踪订单
	public function tailOrder(){
		return view('order/tailOrder');
	}

	//已经购买的宝贝
	public function alreadyBuy(){
		return view('order/alreadyBuy');
	}
}
