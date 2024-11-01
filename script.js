jQuery(document).ready(function() {
	jQuery("#settings").show();
	jQuery("#help").hide();	
	jQuery("#settingsB").removeClass("settingsB").addClass("settingsBH"); 
 

jQuery("#settingsB").click( function() {
jQuery("#settings").show();	
jQuery("#help").hide();	 
jQuery("#helpB").removeClass("helpBH").addClass("helpB"); 
jQuery("#settingsB").removeClass("settingsB").addClass("settingsBH"); 
});   
 

jQuery("#helpB").click( function() {
jQuery("#settings").hide();	
jQuery("#help").show();	
jQuery("#helpB").removeClass("helpB").addClass("helpBH"); 
jQuery("#settingsB").removeClass("settingsBH").addClass("settingsB");
});
								
});



