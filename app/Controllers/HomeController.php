<?php

namespace App\Controllers;

use Slim\Views\Twig as View;
use App\Models\Product;

class HomeController extends BaseController
{
	public function index($request, $response){
		$getCurrencies = $this->db->table('currency')->get();
		return $this->view->render($response,'index.twig', ['currencies' => $getCurrencies]);
	}
	
	public function getProduct($request, $response){
		$product_array = array();
		$currency_symbol = '';
		$a = 'id';
		$b = 'desc';
		if(!empty($request->getParam('sort_by'))){
			if($request->getParam('sort_by') == 'low_to_high'){
				$a = 'product_price';
				$b = 'asc';
			}
			
			if($request->getParam('sort_by') == 'high_to_low'){
				$a = 'product_price';
				$b = 'desc';
			}
		}
		if(!empty($request->getParam('category'))){
			$getProducts = $this->db->table('products')->orderBy($a,$b)->where('product_category',$request->getParam('category'))->get();
		}else{
			$getProducts = $this->db->table('products')->orderBy($a,$b)->get();
		}
		$getCurrency = $this->db->table('currency')->where('code',$request->getParam('symbol'))->first();
		if(!empty($getCurrency)){
			$currency_symbol = $getCurrency->symbol;
		}
		$product_array['success'] = true;
		if(count($getProducts) != 0){
			foreach($getProducts as $key => $value){
				$rate = $value->product_price / $request->getParam('currency');
				$product_array['data'][$value->id.' ']['product_name'] = $value->product_name;
				$product_array['data'][$value->id.' ']['product_price'] = number_format((float)$rate, 2, '.', '');
				$product_array['data'][$value->id.' ']['product_image'] = $value->product_image;
				$product_array['data'][$value->id.' ']['currency_symbol'] = $currency_symbol;
			}
		}
		return $response->withJson($product_array,200);
	}
}