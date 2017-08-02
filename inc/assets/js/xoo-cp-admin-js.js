jQuery(document).ready(function($){
	
	//Color Picker
	$(function(){
		$('.color-field').wpColorPicker();
	})

	//Tabs Change
	$('.xoo-tabs li').on('click',function(){
		var tab_class = $(this).attr('class').split(' ')[0];
		$('li').removeClass('active-tab');
		$('.settings-tab').removeClass('settings-tab-active');
		$(this).addClass('active-tab');
		var class_c = $('[tab-class='+tab_class+']').attr('class');
		$('[tab-class='+tab_class+']').attr('class',class_c+' settings-tab-active');
	})
})