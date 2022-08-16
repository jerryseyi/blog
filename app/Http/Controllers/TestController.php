<?php

use Faker\Provider\File;
use Symfony\Component\Finder\Finder;
use Illuminate\Auth\Middleware\Auth;

class Person {
	private $name;
	private $age;
	protected $gender;

	public function __construct($name, $age, $gender, Finder $finder, File)
	{
		$this->name = $name;
		$this->age = $age;
		$this->gender = $gender;
	}

	
}