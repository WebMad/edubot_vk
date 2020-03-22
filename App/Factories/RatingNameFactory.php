<?php

namespace App\Factories;

use Faker\Factory;

/**
 * Class RatingNameFactory
 * @package App\Factories
 */
class RatingNameFactory extends AbstractFactory
{
    /**
     * @return mixed|string
     */
    public function generate()
    {
        $value = $this->faker->firstName.' '.mb_substr($this->faker->lastName,0,1);
        return $value;
    }


    /**
     * @param $count
     * @return mixed
     */
    public function generateMany($count)
    {
        $values=[];
        for($i=0;$i<$count;$i++)
        {
            $values[] = $this->generate();
        }
        return $values;
    }
}