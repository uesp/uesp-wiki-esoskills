<?php

/*
 * UespEsoSkills -- by DAve Humphrey, dave@uesp.net, April 2016
 * 
 * Adds the <esoskill> tag extension to MediaWiki for displaying an ESO skill popup tooltip.
 *
 * TODO:
 *
 */


if ( !defined( 'MEDIAWIKI' ) ) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/EsoCharData/EsoCharData.php" );
EOT;
	exit( 1 );
}


require_once("/home/uesp/secrets/esolog.secrets");
require_once('/home/uesp/esolog.static/viewSkills.class.php');


$wgExtensionCredits['specialpage'][] = array(
		'path' => __FILE__,
		'name' => 'EsoSkills',
		'author' => 'Dave Humphrey (dave@uesp.net)',
		'url' => 'http://www.uesp.net/wiki/UESPWiki:EsoSkills',
		'descriptionmsg' => 'esoskills-desc',
		'version' => '0.1.0',
);


$wgAutoloadClasses['SpecialEsoSkills'] = __DIR__ . '/SpecialEsoSkills.php';
$wgMessagesDirs['EsoSkills'] = __DIR__ . "/i18n";
$wgExtensionMessagesFiles['EsoSkills'] = __DIR__ . '/EsoSkills.alias.php';
$wgSpecialPages['EsoSkills'] = 'SpecialEsoSkills';

$wgHooks['ParserFirstCallInit'][] = 'UespEsoSkillsParserInit';
$wgHooks['BeforePageDisplay'][] = 'uesoEsoSkills_beforePageDisplay';


function uesoEsoSkills_beforePageDisplay(&$out) {
	global $wgScriptPath;
	
	$out->addHeadItem("uesp-esoskills-css", "<link rel='stylesheet' href='$wgScriptPath/extensions/UespEsoSkills/uespesoskills.css?4Apr2016' />");
	$out->addHeadItem("uesp-esoskills-js", "<script src='$wgScriptPath/extensions/UespEsoSkills/uespesoskills.js?4Apr2016'></script>");
	
	$out->addHeadItem("uesp-esoskillsbrowser-css", "<link rel='stylesheet' href='http://esolog.uesp.net/resources/esoskills_embed.css' />");
	$out->addHeadItem("uesp-esoskillsbrowser-js", "<script src='http://esolog.uesp.net/resources/esoskills.js'></script>");
	
	return true;
}


function UespEsoSkillsParserInit(Parser $parser)
{
	$parser->setHook('esoskill', 'uespRenderEsoSkillTooltip');
	return true;
}


function uespRenderEsoSkillTooltip($input, array $args, Parser $parser, PPFrame $frame)
{
	global $wgScriptPath;
	
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
	
	$url = "/wiki/Special:EsoSkills?id=$skillId";
	
	$output = "<a class='esoSkillTooltipLink' href='$url' $attributes>$input</a>";
	
	return $output;
}


