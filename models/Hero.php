<?php

require_once __DIR__ . '/Spell.php';

class Hero
{
    protected $id;
    protected $name;
    protected $classId;
    protected $className;
    protected $image;
    protected $biography;

    protected $pv;
    protected $mana;
    protected $strength;
    protected $initiative;

    protected $armorItemId;
    protected $primaryWeaponItemId;
    protected $secondaryWeaponItemId;
    protected $shieldItemId;

    // spellList holds an array of Spell objects (or numeric IDs until loaded)
    protected $spellList = [];
    protected $xp;
    protected $currentLevel;

    protected $inventory = []; // simple in-memory inventory: item_id => quantity

    public function __construct($name, $classId = null, $image = null, $biography = '', $pv = 0, $mana = 0, $strength = 0, $initiative = 0, $armorItemId = null, $primaryWeaponItemId = null, $secondaryWeaponItemId = null, $shieldItemId = null, $xp = 0, $currentLevel = 1, $id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->classId = $classId;
        $this->image = $image;
        $this->biography = $biography;

        $this->pv = (int)$pv;
        $this->mana = (int)$mana;
        $this->strength = (int)$strength;
        $this->initiative = (int)$initiative;

        $this->armorItemId = $armorItemId;
        $this->primaryWeaponItemId = $primaryWeaponItemId;
        $this->secondaryWeaponItemId = $secondaryWeaponItemId;
        $this->shieldItemId = $shieldItemId;

        $this->xp = (int)$xp;
        $this->currentLevel = (int)$currentLevel;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getHealth()
    {
        return $this->pv;
    }

    public function getPv()
    {
        return $this->pv;
    }

    public function getMana()
    {
        return $this->mana;
    }

    public function getStrength()
    {
        return $this->strength;
    }

    public function getInitiative()
    {
        return $this->initiative;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getBiography()
    {
        return $this->biography;
    }

    public function getXp()
    {
        return $this->xp;
    }

    public function getLevel()
    {
        return $this->currentLevel;
    }

    // Setters used by GameClass::applyTo()
    public function setPv($pv)
    {
        $this->pv = (int)$pv;
    }

    public function setMana($mana)
    {
        $this->mana = (int)$mana;
    }

    public function setStrength($strength)
    {
        $this->strength = (int)$strength;
    }

    public function setInitiative($initiative)
    {
        $this->initiative = (int)$initiative;
    }

    public function takeDamage($damage)
    {
        $this->pv -= (int)$damage;
        if ($this->pv < 0) {
            $this->pv = 0;
        }
    }

    public function isAlive()
    {
        return $this->pv > 0;
    }

    public function heal($amount)
    {
        $this->pv += (int)$amount;
    }

    public function useMana($amount)
    {
        $this->mana -= (int)$amount;
        if ($this->mana < 0) {
            $this->mana = 0;
        }
    }

    public function attack()
    {
        // Simple attack calculation: base on strength. Integrate weapon modifiers elsewhere.
        return $this->strength;
    }

    public function gainXp($amount)
    {
        $this->xp += (int)$amount;
        $this->checkLevelUp();
    }

    protected function xpToNextLevel()
    {
        return 100 * $this->currentLevel;
    }

    protected function checkLevelUp()
    {
        while ($this->xp >= $this->xpToNextLevel()) {
            $this->xp -= $this->xpToNextLevel();
            $this->currentLevel++;
            $this->pv += 10;
            $this->mana += 5;
            $this->strength += 1;
            $this->initiative += 1;
        }
    }

    public function equipItem($slot, $itemId)
    {
        switch ($slot) {
            case 'armor':
                $this->armorItemId = $itemId;
                break;
            case 'primary_weapon':
                $this->primaryWeaponItemId = $itemId;
                break;
            case 'secondary_weapon':
                $this->secondaryWeaponItemId = $itemId;
                break;
            case 'shield':
                $this->shieldItemId = $itemId;
                break;
        }
    }

    public function addToInventory($itemId, $quantity = 1)
    {
        $qid = (string)$itemId;
        if (isset($this->inventory[$qid])) {
            $this->inventory[$qid] += (int)$quantity;
        } else {
            $this->inventory[$qid] = (int)$quantity;
        }
    }

    public function removeFromInventory($itemId, $quantity = 1)
    {
        $qid = (string)$itemId;
        if (!isset($this->inventory[$qid])) {
            return false;
        }
        $this->inventory[$qid] -= (int)$quantity;
        if ($this->inventory[$qid] <= 0) {
            unset($this->inventory[$qid]);
        }
        return true;
    }

    public function getInventory()
    {
        return $this->inventory;
    }

    public function addToSpell($spell)
    {
        // Accepte un objet Spell ou un identifiant
        if ($spell instanceof Spell) {
            $qid = (string)$spell->getId();
            $this->spellList[$qid] = $spell;
        } else {
            $qid = (string)$spell;
            // stocke l'id pour compatibilité (mais préférer charger les objets via le contrôleur)
            $this->spellList[$qid] = $qid;
        }
    }

    public function removeFromSpell($spellId)
    {
        $qid = (string)$spellId;
        if (!isset($this->spellList[$qid])) {
            return false;
        }
        unset($this->spellList[$qid]);
        return true;
    }

    public function getSpells()
    {
        return $this->spellList;
    }

    public function getSpell($id) {
        $qid = (string)$id;
        if (!isset($this->spellList[$qid])) return null;
        $val = $this->spellList[$qid];
        // Si c'est un objet Spell, retourne l'objet
        if ($val instanceof Spell) return $val;
        // sinon, on pourrait charger l'objet depuis la BDD si nécessaire (non implémenté ici)
        return null;
    }

    public function setClassName($name) {
        $this->className = $name;
    }

    public function getClassName() {
        return $this->className;
    }

    public function getWeaponBonus()
    {
        if (empty($this->primaryWeaponItemId)) {
            return 0;
        }

        global $db;
        if (!isset($db) || !$db instanceof PDO) {
            return 0;
        }

        try {
            $stmt = $db->prepare('SELECT value FROM Items WHERE id = ?');
            $stmt->execute([(int)$this->primaryWeaponItemId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['value'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function getArmorBonus()
    {
        if (empty($this->armorItemId)) {
            return 0;
        }

        global $db;
        if (!isset($db) || !$db instanceof PDO) {
            return 0;
        }

        try {
            $stmt = $db->prepare('SELECT value FROM Items WHERE id = ?');
            $stmt->execute([(int)$this->armorItemId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['value'] : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function isThief()
    {
        return $this->className == "Voleur";
    }

    public function isMage()
    {
        return $this->className == "Magicien";
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'classId' => $this->classId,
            'image' => $this->image,
            'biography' => $this->biography,
            'pv' => $this->pv,
            'mana' => $this->mana,
            'strength' => $this->strength,
            'initiative' => $this->initiative,
            'armorItemId' => $this->armorItemId,
            'primaryWeaponItemId' => $this->primaryWeaponItemId,
            'secondaryWeaponItemId' => $this->secondaryWeaponItemId,
            'shieldItemId' => $this->shieldItemId,
            'spellList' => $this->spellList,
            'xp' => $this->xp,
            'currentLevel' => $this->currentLevel,
            'inventory' => $this->inventory,
        ];
    }
}
