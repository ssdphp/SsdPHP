<?php
namespace SsdPHP\View\Adaptor;
function tpl_modifier_tostring($mixed){
	return var_export($mixed,true);
}
function tpl_modifier_default($input,$default=""){
	return empty($input)?$default:$input;
}
function tpl_modifier_version($string,$version="1.0") {
    return $string."?".$version;
}
