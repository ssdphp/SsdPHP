<?php
namespace SsdPHP\Pulgins\Common;

use SsdPHP\SsdPHP;

class Error{

	/**
	 * 
	 */
	static $CONSOLE=true;
	/**
	 * 
	 */
	static $LOG=false;
	/**
	 * 
	 */
	static $LOGFILE="";


	/**
	 * 
	 */
	static $error_type=array(
		"1"=>"E_ERROR",
		"2"=>"E_WARNING",
		"4"=>"E_PARSE",
		"8"=>"E_NOTICE",
		"16"=>"E_CORE_ERROR",
		"32"=>"E_CORE_WARNING",
		"64"=>"E_COMPILE_ERROR",
		"128"=>"E_COMPILE_WARNING",
		"256"=>"E_USER_ERROR",
		"512"=>"E_USER_WARNING",
		"1024"=>"E_USER_NOTICE",
		"2047"=>"E_ALL",
		"2048"=>"E_STRICT",
		);

	static function exception_handler(\Exception $e){
		if(Error::$CONSOLE && PHP_SAPI!='cli'){
            echo Error::getErrorHtml($e->getTrace(),$e);
        }
		if(Error::$LOG){
			$log = Error::getErrorText($e->getTrace(),$e);
			if(!empty(Error::$LOGFILE)){
				error_log($log,3,Error::$LOGFILE);
			}else error_log($log);
		}
	}
	static function error_handler($errno, $errstr, $errfile, $errline) {
		if(Error::$CONSOLE && PHP_SAPI!='cli'){
            echo Error::getErrorHtml(debug_backtrace());
        }
		if(Error::$LOG){
			$log = Error::getErrorText(debug_backtrace());
			
			if(!empty(Error::$LOGFILE)){
				error_log($log,3,Error::$LOGFILE);
			}else error_log($log);
		}
	}
	/**
 	 * @return string
	 */
	public static function getErrorText($backtrace,$e=null){
		$arrLen=count($backtrace);
		$text="\r\n".(empty($e)?"Error":"Exception")."(".date("Y-m-d H:i:s").")\r\n";
		$index=0;
		if($arrLen>0){
			for($i=$arrLen-1;$i>0;$i--){
                if(!isset($backtrace[$i]['file'])){
                    $backtrace[$i]['file'] = "";
                }
                if(!isset($backtrace[$i]['line'])){
                    $backtrace[$i]['line'] = "";
                }
				$text.=($index++)."\t".
					@$backtrace[$i]['file']."(".@$backtrace[$i]['line'].")\t".
					(empty($backtrace[$i]['class'])?"":$backtrace[$i]['class'].'::').
					@$backtrace[$i]['function']."()\r\n";
			}
		}
		$i=0;
		if(!empty($backtrace[$i]['args']) &&!empty($backtrace[$i]['args'][0]) &&!empty($backtrace[$i]['args'][1])){
			//error
			$errorCode = $backtrace[$i]['args'][0];
			$text.=($index++)."\t".
				@$backtrace[$i]['args'][2]."(".
				@$backtrace[$i]['line'].")\t".
				Error::$error_type[$errorCode].':'.
				(!empty($backtrace[$i]['args'])?$backtrace[$i]['args'][1]:"")."\r\n";
		}elseif($e){
			$text.=($index++)."\t".$e->getFile()."(".$e->getLine().")\t".$e->getCode().":".$e->getMessage()."\t\r\n";
		}
		return $text;
	}
	/**
 	 * @return string
	 */
    public static function getErrorHtml($backtrace,$e=null){

		$arrLen=count($backtrace);
		$html="\r\n".'<table border="1" cellpadding="3" style="font-size: 75%;border: 1px solid #000000;border-collapse: collapse;"><tr style="background-color: #ccccff; font-weight: bold; color: #000000;"><th >#</th><th >File</th><th >Line</th><th >Class::Method(Args)</th><th>'.(empty($e)?"Error":"Exception").'</th></tr>';
		$index=0;
		if($arrLen>0){
			for($i=$arrLen-1;$i>0;$i--){
                if(!isset($backtrace[$i]['file'])){
                    $backtrace[$i]['file'] = "";
                }
                if(!isset($backtrace[$i]['line'])){
                    $backtrace[$i]['line'] = "";
                }
				$html.='<tr style="background-color: #cccccc; color: #000000;"><td>'.($index++).'</td><td>'.
					@$backtrace[$i]['file'].'</td><td>'.
					@$backtrace[$i]['line'].'</td><td>'.
					(empty($backtrace[$i]['class'])?"":$backtrace[$i]['class'].'::').
					@$backtrace[$i]['function'].'(';
				if(!empty($backtrace[$i]['args'])){
					$tmpK=array();	
					foreach($backtrace[$i]['args'] as $value){
						if(is_object($value)){
							$tmpK[]=get_class ($value );
						}elseif(is_array($value)){
							$tmpK[]=$value;
						}
					}

                    if(!empty($tmpK)){
                        foreach($tmpK as $k=>$v){
                            if(is_array($v)){
                                unset($tmpK[$k]);
                            }
                        }
                        $html.=implode(",",$tmpK);
                    }


						
				}
				$html.=')<td></td></tr>';
			}
		}
		$i=0;
		if(!empty($backtrace[$i]['args']) &&!empty($backtrace[$i]['args'][0]) &&!empty($backtrace[$i]['args'][1])){
			//error
			$errorCode = $backtrace[$i]['args'][0];
			$line = empty($backtrace[$i]['line'])?0:$backtrace[$i]['line'];
			$html.='<tr style="background-color: #cccccc; color: #000000;"><td>'.($index++).'</td><td>'.$backtrace[$i]['args'][2].'</td><td>'.$line.'</td><td></td><td style="font-weight:bold">'.Error::$error_type[$errorCode].':'.(!empty($backtrace[$i]['args'])?$backtrace[$i]['args'][1]:"").'</td></tr>';
		}elseif($e){
			$html.='<tr style="background-color: #cccccc; color: #000000;"><td>'.($index++).'</td><td>'.$e->getFile().'</td><td>'.$e->getLine().'</td><td></td><td style="font-weight:bold">'.$e->getCode().':'.$e->getMessage().'</td></tr>';
		}
		$html.='</table><hr style="background-color: #cccccc; border: 0px; height: 1px;" />'."\r\n\r\n";
		return $html;
	}

    /**
     * 致命错误捕获
     */
    public static function fatalError(){
        if ($e = error_get_last()) {
            switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    if(PHP_SAPI!='cli'){
						ob_end_clean();
						if(SsdPHP::isDebug()){
							echo "
						<table border='1' cellpadding='3' style='font-size: 75%;border: 1px solid #000000;border-collapse: collapse;'><tr bgcolor='red'><td colspan='4' style='color: white'>fatalError!</td></tr><tr style='background-color: #ccccff; font-weight: bold; color: #000000;'> <th >type</th><th >File</th><th >Line</th><th >Message</th></tr><tr style='background-color: #cccccc; color: #000000;'><td>{$e['type']}</td><td>{$e['file']}</td><td>{$e['line']}</td><td>{$e['message']}</td></tr></table><hr style='background-color: #cccccc; border: 0px; height: 1px;' />";
						}
					}
                    break;
            }
        }
    }
}
set_error_handler('SsdPHP\Pulgins\Common\Error::error_handler',E_ALL);
set_exception_handler('SsdPHP\Pulgins\Common\Error::exception_handler');
?>
