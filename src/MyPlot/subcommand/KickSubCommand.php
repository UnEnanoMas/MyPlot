<?php
declare(strict_types=1);
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class KickSubCommand extends SubCommand
{
	/**
	 * @param CommandSender $sender
	 *
 	 * @return bool
	 */
	public function canUse(CommandSender $sender) : bool {
		return ($sender instanceof Player) and $sender->hasPermission("myplot.command.kick");
	}

	/**
	 * @param Player $sender
	 * @param string[] $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args) : bool {
		if (!isset($args[0])) return false;
		$plot = $this->getPlugin()->getPlotByPosition($sender);
		if($plot === null) {
			$sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
			return true;
		}
		if ($plot->owner !== $sender->getName() and !$sender->hasPermission("myplot.admin.kick")) {
			$sender->sendMessage(TextFormat::RED . $this->translateString("notowner"));
			return true;
		}
		$target = $this->getPlugin()->getServer()->getPlayer($args[0]);
		if ($target === null) {
			$sender->sendMessage(TextFormat::RED . $this->translateString("kick.noPlayer"));
			return true;
		}
		if (($plot = $this->getPlugin()->getPlotByPosition($target)) === null or !$plot->isSame($plot)) {
			$sender->sendMessage(TextFormat::RED . $this->translateString("kick.notInPlot"));
			return true;
		}
		if ($target->hasPermission("myplot.admin.kick.bypass")) {
			$sender->sendMessage(TextFormat::RED . $this->translateString("kick.cannotkick"));
			$target->sendMessage($this->translateString("kick.attemptkick", [$target->getName()]));
			return true;
		}
		if ($this->getPlugin()->teleportPlayerToPlot($target, $plot)) {
			$sender->sendMessage($this->translateString("kick.success1", [$target->getName(), $plot->__toString()]));
			$target->sendMessage($this->translateString("kick.success2", [$sender->getName(), $plot->__toString()]));
			return true;
		}
		$sender->sendMessage($this->translateString("error"));
		return true;
	}
}
