<?php

class GameClass
{
	protected $id;
	protected $name;
	protected $description;
	protected $basePv;
	protected $baseMana;
	protected $strength;
	protected $initiative;
	protected $maxItems;
	protected $image;

	public function __construct($name, $description = '', $basePv = 0, $baseMana = 0, $strength = 0, $initiative = 0, $maxItems = 0, $id = null, $image = '')
	{
		$this->id = $id;
		$this->name = $name;
		$this->description = $description;
		$this->basePv = (int)$basePv;
		$this->baseMana = (int)$baseMana;
		$this->strength = (int)$strength;
		$this->initiative = (int)$initiative;
		$this->maxItems = (int)$maxItems;
		$this->image = $image;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function getBasePv()
	{
		return $this->basePv;
	}

	public function getBaseMana()
	{
		return $this->baseMana;
	}

	public function getStrength()
	{
		return $this->strength;
	}

	public function getInitiative()
	{
		return $this->initiative;
	}

	public function getMaxItems()
	{
		return $this->maxItems;
	}

	public function getImage()
	{
		return $this->image;
	}

	public function toArray()
	{
		return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'base_pv' => $this->basePv,
			'base_mana' => $this->baseMana,
			'strength' => $this->strength,
			'initiative' => $this->initiative,
			'max_items' => $this->maxItems,
			'image' => $this->image
		];
	}

	/**
	 * Apply this class base stats to a hero-like array/object.
	 * This is a lightweight helper — adapt as needed to your Hero model.
	 */
	public function applyTo(&$hero)
	{
		if (is_array($hero)) {
			$hero['pv'] = $this->basePv;
			$hero['mana'] = $this->baseMana;
			$hero['strength'] = $this->strength;
			$hero['initiative'] = $this->initiative;
		} elseif (is_object($hero)) {
			if (method_exists($hero, 'setPv')) {
				$hero->setPv($this->basePv);
			}
			if (method_exists($hero, 'setMana')) {
				$hero->setMana($this->baseMana);
			}
			if (method_exists($hero, 'setStrength')) {
				$hero->setStrength($this->strength);
			}
			if (method_exists($hero, 'setInitiative')) {
				$hero->setInitiative($this->initiative);
			}
		}
	}
}

