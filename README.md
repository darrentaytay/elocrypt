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

# How it Works?

By including the ElocryptTrait, the __set() and __get() methods provided by Eloquent are overridden
to include an additional step. This additional step simply checks whether the attribute being
set or get is included in the "encryptable" array on the model, and either encrypts/decrypts it accordingly
OR calls the parent __set() or __get() method.
