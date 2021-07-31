<?php
/**
 *  ______  __         ______               __    __
 * |   __ \|__|.-----.|   __ \.----..-----.|  |_ |  |--..-----..----.
 * |   __ <|  ||  _  ||   __ <|   _||  _  ||   _||     ||  -__||   _|
 * |______/|__||___  ||______/|__|  |_____||____||__|__||_____||__|
 *             |_____|
 *
 * BigBrother plugin for PocketMine-MP
 * Copyright (C) 2014-2015 shoghicp <https://github.com/shoghicp/BigBrother>
 * Copyright (C) 2016- BigBrotherTeam
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author BigBrotherTeam
 * @link   https://github.com/BigBrotherTeam/BigBrother
 *
 */

declare(strict_types=1);

namespace shoghicp\BigBrother\network\protocol\Play\Server;

use shoghicp\BigBrother\network\OutboundPacket;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\block\Air;
use pocketmine\block\Anvil;
use pocketmine\block\Wool;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class WindowItemsPacket extends OutboundPacket{

	/** @var int */
	public $windowID;
	/** @var Item[] */
	public $items = [];

	/** @var ItemBlock[] */
	public $blockitem = [];

	public function pid() : int{
		return self::WINDOW_ITEMS_PACKET;
	}

	protected function encode() : void{
		$this->putByte($this->windowID);
		$this->putShort(count($this->items));
		foreach($this->blockitem as $bi){
			$this->putBlockSlot($item->getBlock());
		}
		foreach($this->items as $item){
			if($item instanceof Wool){
				$this->putBlockSlot($item->getBlock());
			}
			if($item instanceof ItemBlock){
				if($item->getID() === 145){
					$this->putBlockSlot($item);
				} else {
					if($item->getID() === 0){
						$this->putShort(-1);
					} else {
						$this->putBlockSlot($item->getBlock());
					}
				}
			} else{
				if($item instanceof ItemStackWrapper){
					if($item->getStackId() === 0){
						$this->putShort(-1);
					} else {
						$this->putWrapSlot($item->getStackId());
					}
				} else {
					if($item->getID() === 0){
						$this->putShort(-1);
					} else{
						$this->putSlot($item);
					}
				}
			}
        }
	}
}
