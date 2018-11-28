<?php
namespace SsdPHP\View\Adaptor;
function tpl_function_tostring($mixed){
    return var_export($mixed,true);
}
function tpl_function_include($tpl){
	return Tpl::fetch($tpl);
}
function tpl_function_echo($val=""){
	echo $val;
}
