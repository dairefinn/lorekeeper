<?php

namespace App\Models\Shop;

use App\Models\Model;

class UserShopLog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id', 'user_id', 'currency_id', 'cost', 'item_id', 'quantity'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_shop_log';

    /**
     * Whether the model contains timestamps to be saved and updated.
     *
     * @var string
     */
    public $timestamps = true;
    
    /**
     * Validation rules for creation.
     *
     * @var array
     */
    public static $createRules = [
        'stock_id' => 'required',
        'shop_id' => 'required',
        'bank' => 'required|in:user'
    ];

    /**********************************************************************************************
    
        RELATIONS

    **********************************************************************************************/
    
    /**
     * Get the user who purchased the item.
     */
    public function user() 
    {
        return $this->belongsTo('App\Models\User\User');
    }
    
    /**
     * Get the purchased item.
     */
    public function item() 
    {
        return $this->belongsTo('App\Models\Item\Item');
    }

    /**
     * Get the shop the item was purchased from.
     */
    public function shop() 
    {
        return $this->belongsTo('App\Models\Shop\UserShop');
    }

    /**
     * Get the currency used to purchase the item.
     */
    public function currency() 
    {
        return $this->belongsTo('App\Models\Currency\Currency');
    }

    /**********************************************************************************************
    
        ACCESSORS

    **********************************************************************************************/

    /**
     * Get the item data that will be added to the stack as a record of its source.
     *
     * @return string
     */
    public function getItemDataAttribute()
    {
        return 'Purchased from '.$this->shop->name.' by '. $this->user->displayName . ' for ' . $this->cost . ' ' . $this->currency->name . '.';
    }
}
