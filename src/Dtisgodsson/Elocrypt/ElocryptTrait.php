<?php namespace Dtisgodsson\Elocrypt;

use Illuminate\Encryption\DecryptException;
use Crypt;

trait ElocryptTrait {

    public function __set($key, $value)
    {
        if(in_array($key, $this->encryptable))
        {
            $value = Crypt::encrypt($value);
        }

        parent::__set($key, $value);
    }

    public function __get($key)
    {
        $value = parent::__get($key);

        if(!isset($this->encryptable))
        {
            return $value;
        }

        if(in_array($key, $this->encryptable))
        {
            try
            {
                return Crypt::decrypt($value);
            }
            catch(DecryptException $exception)
            {
                return $value;
            }
        }

        return $value;
    }
}