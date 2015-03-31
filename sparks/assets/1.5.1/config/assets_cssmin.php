<?php

/*
|--------------------------------------------------------------------------
| CssMin configuration
|--------------------------------------------------------------------------
|
| No need to change this if the defaults are ok for you
|
*/

$config['assets_cssmin_filters'] = array(
	"ImportImports"                 => false,
	"RemoveComments"                => true, 
	"RemoveEmptyRulesets"           => false,
	"RemoveEmptyAtBlocks"           => false,
	"ConvertLevel3AtKeyframes"      => true,
	"ConvertLevel3Properties"       => false,
	"Variables"                     => true,
	"RemoveLastDelarationSemiColon" => true,
);

$config['assets_cssmin_plugins'] = array(
	"Variables"                     => true,
	"ConvertFontWeight"             => false,
	"ConvertHslColors"              => false,
	"ConvertRgbColors"              => false,
	"ConvertNamedColors"            => false,
	"CompressColorValues"           => false,
	"CompressUnitValues"            => false,
	"CompressExpressionValues"      => false
);