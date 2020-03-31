<?php


namespace App\Factories;
use Faker\Factory;

/**
 * Class AbstractFactory
 * @package App\Factories
 */
abstract class AbstractFactory
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * AbstractFactory constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create('ru_RU');
    }

    /**
     * @return mixed
     */
    abstract public function generate();

    /**
     * @param $count
     * @return mixed
     */
    abstract public function generateMany($count);
}