<?php
declare(strict_types=1);
namespace MyPlot\forms\subforms;

use MyPlot\forms\ComplexMyPlotForm;
use MyPlot\MyPlot;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class RemoveHelperForm extends ComplexMyPlotForm {
	public function __construct() {
		parent::__construct(null);
		$plugin = MyPlot::getInstance();
		$this->setTitle($plugin->getLanguage()->translateString("form.header", [TextFormat::AQUA."Add Helper Form"]));
		$this->addDropdown(
			$plugin->getLanguage()->translateString("removehelper.dropdown", [TextFormat::WHITE."Helper Name"]),
			$this->plot ? $this->plot->helpers : array_map(function($val) {return $val->getDisplayName();}, $plugin->getServer()->getOnlinePlayers())
		);

		$this->setCallable(function(Player $player, ?string $data) use ($plugin) {
			if(is_null($data)) {
				$player->getServer()->dispatchCommand($player, $plugin->getLanguage()->get("command.name"), true);
				return;
			}
			$player->getServer()->dispatchCommand($player, $plugin->getLanguage()->get("command.name")." ".$plugin->getLanguage()->get("removehelper.name")." \"$data\"", true);
		});
	}

	public function processData(&$data) : void {
		if(is_null($data))
			return;
		var_dump($data);
		// TODO: convert dropdown return value to player name
		$data = "player Name";
		//throw new FormValidationException("Unexpected form data returned");
	}
}