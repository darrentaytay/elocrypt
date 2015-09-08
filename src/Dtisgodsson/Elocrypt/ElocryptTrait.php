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

    public function update(Array $attributes = [])
    {
        if(!isset($this->encryptable))
        {
            return parent::update($id, $attributes);
        }

        foreach ($attributes as $key => $value) {

            if(in_array($key, $this->encryptable))
            {
                try
                {
                    $attributes[$key] = Crypt::encrypt($value);
                }
                catch(DecryptException $exception)
                {
                    $attributes[$key] = $value;
                }
            }
        }

        return parent::update($attributes);
    }
}