<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\SystemSetting;
use App\Http\Helper;
use App\CurrencyRate;



trait FormatPrice
{


  protected $setting;


  public function __construct()
  {
    $this->setting = SystemSetting::first();
  }



  public function getCustomerPriceAttribute()
  {
    $this->converted_price;
  }


  public function getIsoCodeAttribute()
  {

    $rate = Helper::rate();
    if ($rate) {
      return Helper::getIsoCode();
    }
    return $this->setting->currency->iso_code3;
  }


  public function getCurrencyAttribute()
  {

    $rate = Helper::rate();
    if ($rate) {
      return $rate->symbol;
    }
    return $this->setting->currency->symbol;
  }

  public function getConvertedBuyPriceAttribute()
  {
    $switch = session('switch', 'NGN');

    if ($switch === 'USD') {
      return $this->buy_price_usd === null ? null : (float) $this->buy_price_usd;
    }

    return $this->buy_price;
  }

  public function getConvertedRentPriceAttribute()
  {
    $switch = session('switch', 'NGN');

    if ($switch === 'USD') {
      return $this->rent_price_usd === null ? null : (float) $this->rent_price_usd;
    }

    return $this->rent_price;
  }

  public function ConvertCurrencyRate($price)
  {

    $rate = Helper::rate();
    if ($rate) {
      return round(($price * $rate->rate), 0);
    }
    return round($price, 0);
  }
}
