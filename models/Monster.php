<?php

// models/Monster.php

abstract class Monster
{
    protected $name;
    protected $health;
    protected $mana;
    protected $initiative;
    protected $strength;
    protected $attack;
    protected $experienceValue;
    protected $treasure;
    protected $image;

    public function __construct($name, $health, $mana, $initiative, $strength, $attack, $experienceValue, $treasure, $image)
    {
        $this->name = $name;
        $this->health = $health;
        $this->mana = $mana;
        $this->initiative = $initiative;
        $this->strength = $strength;
        $this->attack = $attack;
        $this->experienceValue = $experienceValue;
        $this->treasure = $treasure;
        $this->image = $image;

    }

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

    public function getStrength()
    {
        return $this->strength;
    }

    public function getAttack()
    {
        return this->attack;
    }

    public function getInitiative()
    {
        return $this->initiative;
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
