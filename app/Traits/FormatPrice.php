<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\SystemSetting;
use App\Http\Helper;
use App\CurrencyRate;



trait FormatPrice 
{

    
    protected $setting;


    public function __construct(){
	   	$this->setting = SystemSetting::first();
    }
    
    /***
     * Returns the sale price of a product
     */
    public function formatted_discount_price()
    {
      if ( null !== $this->variants  && !empty($this->variants[0]) && optional($this->variants[0]->sale_price_expires)->isFuture() ) {
            return null !== $this->variants  && !empty($this->variants[0])  &&  null !== $this->variants[0]->sale_price 
          ? $this->ConvertCurrencyRate($this->variants[0]->sale_price)
          : null;
      } else {
          return null !== $this->sale_price  &&  optional($this->sale_price_expires)->isFuture()  
          ? $this->ConvertCurrencyRate($this->sale_price)
          : null;
      }
      return null;
    }

    public function display_price(){
      if ($this->formatted_discount_price() !== null) {
        if (  $this->has_variants &&  $this->variants[0]->sale_price ) {
          echo "<i style='text-decoration: line-through;'>" . $this->variants[0]->price. "</i>" .'  '. $this->variants[0]->sale_price; 
        } else {
          echo  "<i style='text-decoration: line-through;'>" . $this->price. "</i>" .'  '. $this->sale_price; 
        }
      } else {
          echo  $this->price; 
      }
    }

    public function getDefaultPercentageOffAttribute(){      
      if ($this->formatted_discount_price() !== null) {
          if (null !== $this->variants  && !empty($this->variants[0])  &&  null !== $this->variants[0]->sale_price ){
            return $this->calPercentageOff($this->variants[0]->price,$this->variants[0]->sale_price);
          } elseif ( null !== $this->sale_price ){
            return $this->calPercentageOff($this->price,$this->sale_price);
          }
      }
	    return null;
    }

    public function percentageOff(){
      if ($this->formatted_discount_price() !== null){
        return $this->calPercentageOff($this->price,$this->sale_price);
      }
      return null;
    }

    public function calPercentageOff($price,$sale_price){
      $discount = (($price - $sale_price) * 100) / $price ;
      return round($discount); 
    }

    public function getPercentageOffAttribute(){      
      return $this->percentageOff();
    }

    public function getDiscountedPriceAttribute(){
      if ( null !== $this->sale_price &&  optional($this->sale_price_expires)->isFuture() ) {
        return $this->ConvertCurrencyRate($this->sale_price);
      }
    }

    public function getCustomerPriceAttribute(){
      return $this->discounted_price ?? $this->converted_price ;
    }
  
    public function getDefaultDiscountedPriceAttribute(){
      return $this->formatted_discount_price();
    }

    public function getCurrencyAttribute(){
        
      $rate = Helper::rate();
      if ($rate){
         return $rate->symbol;
      }
		  return $this->setting->currency->symbol;
    }

    public function getConvertedPriceAttribute(){
  
      return null !== $this->variants  && !empty($this->variants[0])  &&  null !== $this->variants[0]->price 
      ? $this->ConvertCurrencyRate($this->variants[0]->price)
      : $this->ConvertCurrencyRate($this->price);   
    }
    
    public function ConvertCurrencyRate($price){
      
      $rate = Helper::rate();
      if ($rate){
        return round(($price * $rate->rate),0);  
      }
      return round($price,0);  

    }

}
