/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.stylesSet.add( 'my_styles', [
	{ name: 'Clearfix', element: 'div', styles: { clear: 'both' } },
]);
CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	config.language = 'en';
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
        { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'undo' },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'others' },
		{ name: 'paragraph',   groups: [ 'list', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
        { name: 'tools' }
    ];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	// config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';
	// config.extraPlugins = 'justify';
	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

    config.filebrowserBrowseUrl = dir+'/file_browser/browser?type=Files';
	config.filebrowserUploadUrl = dir+'/file_browser/connector?command=QuickUpload&type=Files';
    config.filebrowserImageBrowseUrl = dir+'/file_browser/browser?type=Images';
    config.filebrowserImageUploadUrl = dir+'/file_browser/connector?command=QuickUpload&type=Images';

    config.extraPlugins = 'justify,filetools,popup,filebrowser,youtube,font';
	config.stylesSet = 'my_styles';
};
// CKEDITOR.on("instanceReady", function(e) {
// 	window.editor = e.editor;
// 	// editor.on("blur", function(e) {
// 		// console.log(editor.GetParentForm())
// 		// editor.setData('aaa');
// 	// });
// });
