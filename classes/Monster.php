<?php

// models/Monster.php

abstract class Monster
{
    protected $name;
    protected $health;
    protected $mana;
    protected $experienceValue;
    protected $treasure;
    protected $image;

    public function __construct($name, $health, $mana, $experienceValue, $treasure, $image)
    {
        $this->name = $name;
        $this->health = $health;
        $this->mana = $mana;
        $this->experienceValue = $experienceValue;
        $this->treasure = $treasure;
        $this->image = $image;

    }

    abstract public function attack();

    public function getName()
    {
        return $this->name;
    }

    public function getHealth()
    {
        return $this->health;
    }

    public function getMana()
    {
        return $this->mana;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function takeDamage($damage)
    {
        $this->health -= $damage;
    }

    public function isAlive()
    {
        return $this->health > 0;
    }

    public function getExperienceValue()
    {
        return $this->experienceValue;
    }

    public function getTreasure()
    {
        return $this->treasure;
    }
}
