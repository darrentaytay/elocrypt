Eloquent Encryption/Decryption for Laravel 4
===============

# Installation

This package can be installed via Composer by adding the following to your composer.json file:

	"require": {
		"dtisgodsson/elocrypt": "1.*"
	}

You must then run the following command:

    composer update

# Usage

Simply reference the ElocryptTrait in any Eloquent Model you wish to apply encryption to and 
then define an "encryptable" array on that model containing a list of the attributes you wish
to Encrypt.

For example:

	class User extends Eloquent {

    use ElocryptTrait;
    
    public $encryptable = ['first_name', 'last_name', 'address_line_1', 'postcode'];
    
  }
