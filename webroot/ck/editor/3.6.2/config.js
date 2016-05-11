/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.editorConfig = function(config)
{
	////////////////////////////////////
	//    CONFIGURATIONS GENERALES    //
		config.language 					= 'fr'; //Langue de l'éditeur
		config.autoParagraph 				= false;		
		//config.basicEntities 				= false;
		//config.entities 					= false;
		//config.entities_greek 			= false;
		config.entities_latin 				= false;
		//config.htmlEncodeOutput 			= false;
		//config.entities_processNumerical 	= false;		
		config.templates_replaceContent 	= false; //Indique lors de la sélection de templates si il faut remplacer le contenu actuel
		
	///////////////////////////////////////////////
	//    CONFIGURATION DES PLUGINS A CHARGER    //
		config.extraPlugins = 'internpage,syntaxhighlight'; //Mise en place d'un plugin supplémentaire
	
	//////////////////////////////////////////
	//    DEFINITION DE LA BARRE DE MENU    //
		config.toolbar_App =
		[
			{ name: 'document',    items : [ 'Source','Templates'] },
			{ name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
			'/',
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
			{ name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
			{ name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
			{ name: 'insert',      items : [ 'Image','Flash','Iframe','Table','HorizontalRule','SpecialChar','Code' ] },
			'/',
			{ name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
			{ name: 'colors',      items : [ 'TextColor','BGColor' ] },
			{ name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About'] }
		];	
		config.toolbar = 'App';

	//////////////////////////////////////////////
	//    DEFINITION DES CARACTERES SPECIAUX    //
		config.specialChars =
		[
			'&brvbar;','&#8745;','&#131;','!','&quot;','#','$','%','&amp;',"'",'(',')','*','+','-','.','/',
			'0','1','2','3','4','5','6','7','8','9',':',';','&lt;','=','&gt;','?','@',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'[',']','^','_','`',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'{','|','}','~',
			"&euro;", "&lsquo;", "&rsquo;", "&ldquo;", "&rdquo;", "&ndash;", "&mdash;", "&iexcl;", "&cent;", "&pound;", "&curren;", 
			"&yen;", "&brvbar;", "&sect;", "&uml;", "&copy;", "&ordf;", "&laquo;", "&not;", "&reg;", "&macr;", "&deg;", "&sup2;", 
			"&sup3;", "&acute;", "&micro;", "&para;", "&middot;", "&cedil;", "&sup1;", "&ordm;", "&raquo;", "&frac14;", "&frac12;", 
			"&frac34;", "&iquest;", "&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Auml;", "&Aring;", "&AElig;", "&Ccedil;", 
			"&Egrave;", "&Eacute;", "&Ecirc;", "&Euml;", "&Igrave;", "&Iacute;", "&Icirc;", "&Iuml;", "&ETH;", "&Ntilde;", "&Ograve;", 
			"&Oacute;", "&Ocirc;", "&Otilde;", "&Ouml;", "&times;", "&Oslash;", "&Ugrave;", "&Uacute;", "&Ucirc;", "&Uuml;", "&Yacute;", 
			"&THORN;", "&szlig;", "&agrave;", "&aacute;", "&acirc;", "&atilde;", "&auml;", "&aring;", "&aelig;", "&ccedil;", "&egrave;", 
			"&eacute;", "&ecirc;", "&euml;", "&igrave;", "&iacute;", "&icirc;", "&iuml;", "&eth;", "&ntilde;", "&ograve;", "&oacute;", 
			"&ocirc;", "&otilde;", "&ouml;", "&divide;", "&oslash;", "&ugrave;", "&uacute;", "&ucirc;", "&uuml;", "&yacute;", "&thorn;", 
			"&yuml;", "&OElig;", "&oelig;", "&#372;", "&#374", "&#373", "&#375;", "&sbquo;", "&#8219;", "&bdquo;", "&hellip;", "&trade;", 
			"&#9658;", "&bull;", "&rarr;", "&rArr;", "&hArr;", "&diams;", "&asymp;"
		];
	
	/////////////////////////////////////////////////////////////
	//    DEFINITION DE LA TAILLE DES POLICES DE CARACTERES    //
		config.fontSize_sizes = '8/8px;9/9px;10/10px;11/11px;12/12px;13/13px;14/14px;15/15px;16/16px;17/17px;18/18px;19/19px;20/20px;21/21px;22/22px;23/23px;24/24px;25/25px;';
	
	///////////////////////////////////////////////////
	//    RECUPERATION DES FICHIERS CSS A CHARGER    //
		config.contentsCss = $( "body" ).data( "etcf" );

	/////////////////////////////////////////////////////////////////////
	//    RECUPERATION DES STYLES PAR DEFAUT ET DES MODELES DE PAGE    //
		//var jsFilePath 			= get_js_file_path();	
		config.stylesSet 		= 'default:' + $( "body" ).data( "ejfp" ) + 'default_styles.js';	
		config.templates_files 	= [$( "body" ).data( "ejfp" ) + 'default_templates.js'];  

	///////////////////////////////////////////////
	//    DEFINITION DES COULEURS DISPONIBLES    //
		config.colorButton_colors =
			'8c8085,776890,9ccbc1,7c907c,91e4e6,9a1616,777777,776890,acbeac,b7c3c1,'+
			'd171ce,ba91de,b37731,DE5328,e26fb5,7d96a4,128ece,598196,babc8e,DE5328,'+
			'c884d0,b56de5,d83737,329ac0,dadd21,0576ab,bdcb71,9696dd,97cbc0,f0d137,'+		
			'000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,B22222,A52A2A,' +
			'DAA520,006400,40E0D0,0000CD,800080,808080,F00,FF8C00,FFD700,008000,' +
			'0FF,00F,EE82EE,A9A9A9,FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,' +
			'DDA0DD,D3D3D3,FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF';
};