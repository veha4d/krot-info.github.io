Komento.module("admin.acl.recommended",function(e){var t=this;Komento.require().script("admin.language").done(function(){Komento.Controller("AclRecommended",{defaults:{type:"usergroup",usergroup:0}},function(t){return{init:function(){t.rule={read_comment:1,read_stickies:1,read_lovies:1,add_comment:0,edit_own_comment:0,delete_own_comment:0,author_edit_comment:0,author_delete_comment:0,author_publish_comment:0,author_unpublish_comment:0,edit_all_comment:0,delete_all_comment:0,publish_all_comment:0,unpublish_all_comment:0,like_comment:0,report_comment:0,share_comment:1,reply_comment:0,stick_comment:0};var n=[18,19,20,21,23,24,25,29];if(t.options.type=="usergroup"&&(Komento.options.jversion=="1.5"&&e.inArray(t.options.usergroup,n)||Komento.options.jversion!="1.5"&&t.options.usergroup>0&&t.options.usergroup<9))switch(t.options.usergroup){case 1:case 29:t.renderPublic();break;case 2:case 18:t.renderRegistered();break;case 3:case 19:t.renderAuthor();break;case 4:case 20:t.renderEditor();break;case 5:case 21:t.renderPublisher();break;case 6:case 23:t.renderManager();break;case 7:case 24:t.renderAdministrator();break;case 8:case 25:t.renderSuperAdministrator()}},render:function(t){e.each(t,function(t,n){var r=e.language("COM_KOMENTO_NO_OPTION");n&&(r=e.language("COM_KOMENTO_YES_OPTION"));var i='<label class="recommended">'+e.language("COM_KOMENTO_ACL_RECOMMENDED")+": "+r+"</label>";e("."+t).append(i)})},renderPublic:function(){var e=t.rule;t.render(e)},renderRegistered:function(){var e=t.rule},renderAuthor:function(){var e=t.rule},renderEditor:function(){var e=t.rule},renderPublisher:function(){var e=t.rule},renderManager:function(){var e=t.rule},renderAdministrator:function(){var e=t.rule},renderSuperAdministrator:function(){var e=t.rule}}}),t.resolve()})});