<?php

// models/Spell.php

class Spell
{
    private $id;
    private $spell_name;
    private $mana_cost;
    private $damage; 
    private $description;

    public function __construct($id, $spell_name, $mana_cost, $damage, $description)
    {
        $this->id = $id;
        $this->spell_name = $spell_name;
        $this->mana_cost = $mana_cost;
        $this->damage = $damage; 
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSpellName()
    {
        return $this->spell_name;
    }

    public function getManaCost()
    {
        return $this->mana_cost;
    }

    public function getDamage()
    {
        return $this->damage; 
    }

    public function getDescription()
    {
        return $this->description;
    }
}
