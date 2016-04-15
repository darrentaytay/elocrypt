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

    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function create(array $attributes)
    {
        $model = new static;

        if(!isset($model->encryptable))
        {
            return parent::create($attributes);
        }

        foreach ($attributes as $key => $value) {

            if(in_array($key, $model->encryptable))
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

        return parent::create($attributes);
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @return bool|int
     */
    public function update(array $attributes = array())
    {
        if(!isset($this->encryptable))
        {
            return parent::update($attributes);
        }

        foreach ($attributes as $key => $value) {

            if(in_array($key, $this->encryptable))
            {
                try
                {
                    $decrypted = Crypt::decrypt($value);
                    $attributes[$key] = $value;
                }
                catch(DecryptException $exception)
                {
                    $attributes[$key] = Crypt::encrypt($value);
                }
            }
        }

        return parent::update($attributes);
    }

    /**
     * Create a new instance of the given model.
     *
     * @param  array  $attributes
     * @param  bool   $exists
     * @return static
     */
    public function newInstance($attributes = array(), $exists = false)
    {
        if(!isset($this->encryptable))
        {
            return parent::newInstance($attributes, $exists);
        }

        foreach ($attributes as $key => $value) {

            if(in_array($key, $this->encryptable))
            {
                try
                {
                    $decrypted = Crypt::decrypt($value);
                    $attributes[$key] = $value;
                }
                catch(DecryptException $exception)
                {
                    $attributes[$key] = Crypt::encrypt($value);
                }
            }
        }

        return parent::newInstance($attributes, $exists);
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        if(!isset($this->encryptable))
        {
            return $attributes;
        }

        foreach ($this->encryptable as $key)
        {
            if ( ! isset($attributes[$key])) continue;

            try
            {
                $attributes[$key] = Crypt::decrypt($attributes[$key]);
            }
            catch(DecryptException $exception)
            {
                //Do nothing, attribute already exists
            }
        }

        return $attributes;
    }

    /**
     * Get the model's original attribute values.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return array
     */
    public function getOriginal($key = null, $default = null)
    {
        $original = parent::getOriginal($key, $default);

        if(!isset($this->encryptable))
        {
            return $original;
        }

        foreach ($this->encryptable as $key)
        {
            if ( ! isset($original[$key])) continue;

            try
            {
                $original[$key] = Crypt::decrypt($original[$key]);
            }
            catch(DecryptException $exception)
            {
                //Do nothing, attribute already exists
            }
        }

        return $original;
    }
}