<?php

namespace App;

abstract class ItemCategory {
    protected $item;

    public function __construct(GildedRose $item) {
        $this->item = $item;
    }

    abstract public function update();

    protected function increaseQuality($amount = 1) {
        $this->item->quality = min(50, $this->item->quality + $amount);
    }

    protected function decreaseQuality($amount = 1) {
        $this->item->quality = max(0, $this->item->quality - $amount);
    }

    protected function decreaseSellIn() {
        $this->item->sellIn -= 1;
    }
}

class AgedBrie extends ItemCategory {
    public function update() {
        $this->decreaseSellIn();

        $this->increaseQuality();

        if ($this->item->sellIn < 0) {
            $this->increaseQuality();
        }
    }
}

class BackstagePass extends ItemCategory {
    public function update() {
        $this->decreaseSellIn();

        if ($this->item->sellIn < 0) {
            $this->item->quality = 0;
            return;
        }

        if ($this->item->sellIn < 5) {
            $this->increaseQuality(3);
        } elseif ($this->item->sellIn < 10) {
            $this->increaseQuality(2);
        } else {
            $this->increaseQuality();
        }
    }
}

class Sulfuras extends ItemCategory {
    public function update() {}
}

class Conjured extends ItemCategory {
    public function update() {
        $this->decreaseSellIn();

        $degrade = ($this->item->sellIn < 0) ? 4 : 2;
        $this->decreaseQuality($degrade);
    }
}

class NormalItem extends ItemCategory {
    public function update() {
        $this->decreaseSellIn();

        $degrade = ($this->item->sellIn < 0) ? 2 : 1;
        $this->decreaseQuality($degrade);
    }
}

class GildedRose {
    public $name;
    public $quality;
    public $sellIn;

    public function __construct($name, $quality, $sellIn) {
        $this->name = $name;
        $this->quality = $quality;
        $this->sellIn = $sellIn;
    }

    public static function of($name, $quality, $sellIn) {
        return new static($name, $quality, $sellIn);
    }

    public function tick() {
        $category = $this->getCategory();
        $category->update();
    }

    private function getCategory() {
        if ($this->name == 'Aged Brie') {
            return new AgedBrie($this);
        }

        if ($this->name == 'Backstage passes to a TAFKAL80ETC concert') {
            return new BackstagePass($this);
        }

        if ($this->name == 'Sulfuras, Hand of Ragnaros') {
            return new Sulfuras($this);
        }

        if (stripos($this->name, 'Conjured') !== false) {
            return new Conjured($this);
        }

        return new NormalItem($this);
    }
}
