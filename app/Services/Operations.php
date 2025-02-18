<?php



namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class Operations{


  public static function decryptId($value){

    try {
      
      $id = Crypt::decrypt($value);

    } catch (DecryptException $e) {
      
      return Redirect()->route("home");

    }

    return $id;

  }


}