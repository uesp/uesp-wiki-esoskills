<?php

/*
 * UespEsoSkills -- by DAve Humphrey, dave@uesp.net, April 2016
 * 
 * Adds the <esoskill> tag extension to MediaWiki for displaying an ESO skill popup tooltip.
 *
 * TODO:
 *
 */

$wgHooks['ParserFirstCallInit'][] = 'UespEsoSkillsParserInit';
$wgHooks['BeforePageDisplay'][] = 'uesoEsoSkills_beforePageDisplay';

function uesoEsoSkills_beforePageDisplay(&$out) {
	global $wgScriptPath;
	
	$out->addHeadItem("uesp-esoskills-css", "<link rel='stylesheet' href='$wgScriptPath/extensions/UespEsoSkills/uespesoskills.css?4Apr2016' />");
	$out->addHeadItem("uesp-esoskills-js", "<script src='$wgScriptPath/extensions/UespEsoSkills/uespesoskills.js?4Apr2016'></script>");
	
	return true;
}


function UespEsoSkillsParserInit(Parser $parser)
{
	$parser->setHook('esoskill', 'uespRenderEsoSkillTooltip');
	return true;
}


function uespRenderEsoSkillTooltip($input, array $args, Parser $parser, PPFrame $frame)
{
	$output = "";
	
	$skillId = "";
	$skillLevel = "";
	$skillHealth = "";
	$skillMagicka = "";
	$skillStamina = "";
	$skillSpellDamage = "";
	$skillWeaponDamage = "";
	
	foreach ($args as $name => $value)
	{
		$name = strtolower($name);
	
		switch ($name)
		{
			case "skillid":
				$skillId = $value;
				break;
			case "l":
			case "level":
				$skillLevel = $value;
				break;
			case "h":
			case "hea":
			case "health":
				$skillHealth = $value;
				break;
			case "m":
			case "mag":
			case "magicka":
				$skillMagicka = $value;
				break;
			case "s";
			case "sta":
			case "stamina":
				$skillStamina = $value;
				break;
			case "sd":
			case "spelldmg":
			case "spelldamage":
				$skillSpellDamage = $value;
				break;
			case "wd":
			case "weapdmg":
			case "weapondamage":
				$skillWeaponDamage = $value;
				break;
		}
	
	}
	
	$attributes = "";
	if ($skillId           != "") $attributes .= "skillid='$skillId' ";
	if ($skillLevel        != "") $attributes .= "level='$skillLevel' ";
	if ($skillHealth       != "") $attributes .= "health='$skillHealth' ";
	if ($skillMagicka      != "") $attributes .= "magicka='$skillMagicka' ";
	if ($skillStamina      != "") $attributes .= "stamina='$skillStamina' ";
	if ($skillSpellDamage  != "") $attributes .= "spelldamage='$skillSpellDamage' ";
	if ($skillWeaponDamage != "") $attributes .= "weapondamage='$skillWeaponDamage' ";
	
	$url = "http://esolog.uesp.net/viewSkills.php?id=$skillId";
	
	$output = "<a class='esoSkillTooltipLink' href='$url' $attributes>$input</a>";
	
	return $output;
}


