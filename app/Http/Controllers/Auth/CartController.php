<?php

namespace App\Http\Controllers\Auth;
use Input;
use Request;
use App\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
class CartController extends Controller
{
	//购物车列表
	public function cartShow()
	{
		//判断用户是否登录 并验证
		
		//逻辑处理
		$userid = 1;
		return view('cart/cartShow',['user_id'=>$userid]);
	}

	//清空购物车
	public function test()
	{
		if(request::ajax() && request::isMethod('get'))
		{
			//验证
			$this->validate(request(),['userid'=>'required']);
			$userid = request('userid');
			//删除数据
			$sre = Cart::where("user_id",$userid)->delete();
			if($sre)
			{
				$status = 'ok';
			}
			else
			{
				$status = 'no';
			}
		}
		else
		{
			$status = 'no';
		}
		return $status;
		
		
	}

	//查询
	public function cartSel()
	{
		if(request::ajax() && request::isMethod('get'))
		{
			//验证
			$this->validate(request(),['userid'=>'required']);
			$userid = request('userid');
			//查询数据
			$data = Db::table('cart')->where('user_id',$userid)->join('product','cart.product_id','=','product.id')->join('goods','product.goods_id','=','goods.id')->select('cart.num','user_id','price','sell_price','cover','goods_id','name')->get();
		}
		else
		{
			$data = [];
		}
		return $data;
	}

	//结算生成订单
	public function createOrder()
	{
		return 123;
		//$aa = request('data');
		// $aa = "[{'goods_id':1,'goods_num':2}]";
		// $a = json_decode($aa);
		// dd($a);
		// $arr = json_decode($aa, true);
		// return $arr;
		// if($request->isMethod('post')){ 
  //   		echo 123;
		// }
	}

	//确认订单信息
	public function cartOrderInfo()
	{
		return view('cart/cartOrderInfo');
	}

	//提交订单
	public function submitOrder()
	{
		return view('cart/submitOrder');
	}
}