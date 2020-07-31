<?php

namespace App\Traits;

trait Check 
{
    
    public function checked($collections,$id)
    {
        foreach($collections as $collection){
            if(null !== $collection->id && $collection->id == $id ){
                return $collection->id;
            }
        }
        return false;
    }
}