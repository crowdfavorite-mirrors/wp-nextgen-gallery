jQuery(function($){
	$('#wp-admin-bar-ngg-menu').pointer({
		content: nggAdmin.content,
		pointerClass: 'pointer ngg_latest_news_notice',
		close: function(){
			setUserSetting(nggAdmin.setting, 1);
		}
	}).pointer('open');
});