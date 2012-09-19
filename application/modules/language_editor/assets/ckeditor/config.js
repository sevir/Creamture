/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.width = 700;
	config.resize_enabled = false;
	config.autoUpdateElement = true;
	//config.AutoDetectPasteFromWord = true;
	config.skin = 'v2';
	config.filebrowserBrowseUrl = '/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=' + encodeURIComponent( '/File_manager/fck_get_files.html' );
	config.toolbar_Complete =
	[
	    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
	    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['Image','Table','HorizontalRule','SpecialChar','PageBreak'],
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['Link','Unlink','Anchor', '-', 'Source'],
	    ['Styles','Format','Font','FontSize', 'TextColor','BGColor']
	];

};