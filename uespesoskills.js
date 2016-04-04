var g_EsoSkillPopupTooltip = null;
var g_EsoSkillPopupIsVisible = false;


function CreateEsoSkillPopupTooltip()
{
	g_EsoSkillPopupTooltip = $('<div />').addClass('esoSkillPopupTooltip').hide();
	$('body').append(g_EsoSkillPopupTooltip);
}


function ShowEsoSkillPopupTooltip(parent, skillId, level, health, magicka, stamina, spellDamage, weaponDamage)
{
	var linkSrc = "http://esolog.uesp.net/skillTooltip.php?embed";
	var dataOk = false;
	
	if (skillId) { linkSrc += "&id=" + skillId; dataOk = true; }
	if (level) linkSrc += "&level=" + level;
	if (health) linkSrc += "&health=" + health;
	if (magicka) linkSrc += "&magicka=" + magicka;
	if (stamina) linkSrc += "&stamina=" + stamina;
	if (spellDamage) linkSrc += "&spelldamage=" + spellDamage;
	if (weaponDamage) linkSrc += "&weapondamage=" + weaponDamage;
	
	if (!dataOk) return false;
	
	if (g_EsoSkillPopupTooltip == null) CreateEsoSkillPopupTooltip();
	
	var position = $(parent).offset();
	var width = $(parent).width();
	g_EsoSkillPopupTooltip.css({ top: position.top-50, left: position.left + width });
	
	g_EsoSkillPopupIsVisible = true;

	g_EsoSkillPopupTooltip.load(linkSrc, "", function() {
		
		if (g_EsoSkillPopupIsVisible)
		{
			g_EsoSkillPopupTooltip.show();
			AdjustEsoItemLinkTooltipPosition(g_EsoSkillPopupTooltip, $(parent));
		}
	});
}


function AdjustEsoSkillPopupTooltipPosition(tooltip, parent)
{
     var windowWidth = $(window).width();
     var windowHeight = $(window).height();
     var toolTipWidth = tooltip.width();
     var toolTipHeight = tooltip.height();
     var elementHeight = parent.height();
     var elementWidth = parent.width();
     
     var top = parent.offset().top - toolTipHeight/2 + elementHeight/2;
     var left = parent.offset().left + parent.outerWidth() + 3;
     
     tooltip.offset({ top: top, left: left });
     
     var viewportTooltip = tooltip[0].getBoundingClientRect();
     
     if (viewportTooltip.bottom > windowHeight) 
     {
    	 var deltaHeight = viewportTooltip.bottom - windowHeight + 10;
         top = top - deltaHeight
     }
     else if (viewportTooltip.top < 0)
     {
    	 var deltaHeight = viewportTooltip.top - 10;
         top = top - deltaHeight
     }
         
     if (viewportTooltip.right > windowWidth) 
     {
         left = left - toolTipWidth - parent.width() - 28;
     }
     
     tooltip.offset({ top: top, left: left });
     viewportTooltip = tooltip[0].getBoundingClientRect();
     
     if (viewportTooltip.left < 0 )
     {
    	 var el = $('<i/>').css('display','inline').insertBefore(parent[0]);
         var realOffset = el.offset();
         el.remove();
         
         left = realOffset.left - toolTipWidth - 3;
         tooltip.offset({ top: top, left: left });
     }
     
}


function HideEsoSkillPopupTooltip()
{
	g_EsoSkillPopupTooltip.hide();
	g_EsoSkillPopupIsVisible = false;
}


function OnEsoSkillLinkEnter()
{
	ShowEsoSkillPopupTooltip(this, $(this).attr('skillid'), $(this).attr('level'), $(this).attr('health'), $(this).attr('magicka'), $(this).attr('stamina'), $(this).attr('spelldamage'), $(this).attr('weapondamage'));
}


function OnEsoSkillLinkLeave()
{
	HideEsoSkillPopupTooltip();
}


$( document ).ready(function() {
	$('.esoSkillTooltipLink').hover(OnEsoSkillLinkEnter, OnEsoSkillLinkLeave);
});