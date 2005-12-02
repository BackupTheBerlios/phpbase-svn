<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
* @file AkFtpClient.php
* Ftp client helper class taken from the Akelos Framework
*/

// +----------------------------------------------------------------------+
// | PhpBase (PHP Library for integrating data sources with Google Base)  |
// +----------------------------------------------------------------------+
// | Copyright (C) 2005  Bermi Ferrer Martinez                            |
// | Released under the GNU Lesser General Public License                 |
// +----------------------------------------------------------------------+
// | You should have received the following files along with this library |
// | - LICENSE (LGPL License)                                             |
// | - CREDITS (Developpers, contributors and contact information)        |
// | - README (Important information regarding this library)              |
// +----------------------------------------------------------------------+


class AkFtpClient
{
    function put_contents ($file, $contents)
    {
        $result = false;
        if($ftp = AkFtpClient::connect()){
            $file = str_replace('\\','/',$file);
            $tmpfname = tempnam('/tmp', 'tmp');
            $temp = fopen($tmpfname, 'w+');
            fwrite($temp, $contents);
            fclose($temp);
            $temp = fopen($tmpfname, 'rb');
            ftp_pasv($ftp, true);
            $result = ftp_fput($ftp, $file , $temp, FTP_ASCII);
            fclose($temp);
            unlink($tmpfname);
        }
        return $result;
    }

    function get_contents ($file)
    {
        if($ftp = AkFtpClient::connect()){
            $file = str_replace('\\','/',$file);
            $tmpfname = tempnam('/tmp', 'tmp');
            ftp_get($ftp, $tmpfname, $file , FTP_BINARY);
            $file_contents = @file_get_contents($tmpfname);
            unlink($tmpfname);
            return $file_contents;
        }
    }

    function connect($base_dir = null, $dsn = '')
    {
        static $ftp_conn, $_base_dir, $disconnected = false;
        
        if(!isset($ftp_conn) | $disconnected){
            if(!defined('GOOGLE_BASE_FTP_SETTINGS')){
                trigger_error('You must set a valid FTP connection by defining the constant GOOGLE_BASE_FTP_SETTINGS with a string representing the connection details I.e. ftp://usename:password@example.com/public_html',E_USER_ERROR);
            }else {
                $f = parse_url(GOOGLE_BASE_FTP_SETTINGS);
                if(@$f['scheme'] != 'ftps'){
                    $ftp_conn = isset($f['port']) ?  ftp_connect($f['host'], $f['port']) : ftp_connect($f['host']);
                }else{
                    $ftp_conn = isset($f['port']) ?  ftp_ssl_connect($f['host'], $f['port']) : ftp_ssl_connect($f['host']);
                }
                $login_result = ftp_login($ftp_conn, @$f['user'], @$f['pass']);
                if(!$ftp_conn || !$login_result){
                    trigger_error('Could not connect to the ftp server', E_USER_NOTICE);
                    return false;
                }

                $_base_dir = isset($f['path']) ? '/'.trim($f['path'],'/') : '/';

                if(defined('FTP_AUTO_DISCONNECT') && FTP_AUTO_DISCONNECT){
                    register_shutdown_function(array('AkFtpClient', 'disconnect'));
                }
            }
        }
        if(isset($base_dir) && $base_dir === 'DISCONNECT_FTP'){
            $disconnected = true;
            $base_dir = null;
        }else {
            $disconnected = false;
        }

        if(!isset($base_dir) && isset($_base_dir) && ('/'.trim(ftp_pwd($ftp_conn),'/') != $_base_dir)){
            if (!ftp_chdir($ftp_conn, $_base_dir)) {
                trigger_error('Could not change to the FTP base directory '.$_base_dir,E_USER_NOTICE);
            }
        }elseif (isset($base_dir)){
            if (!ftp_chdir($ftp_conn, $base_dir)) {
                trigger_error('Could not change to the FTP directory '.$base_dir,E_USER_NOTICE);
            }
        }
        return $ftp_conn;
    }

    function disconnect()
    {
        static $disconnected = false;
        if(!$disconnected && $ftp_conn = AkFtpClient::connect('DISCONNECT_FTP')){
            $disconnected = ftp_close($ftp_conn);
            return $disconnected;
        }
        return false;
    }

    function make_dir($path)
    {
        if($ftp_conn = AkFtpClient::connect()){
            $path = str_replace('\\','/',$path);
            if(!strstr($path,'/')){
                $dir = array(trim($path,'.'));
            }else{
                $dir = (array)@split('/', trim($path,'/.'));
            }
            $path = '';
            $ret = true;
            for ($i=0; $i<count($dir); $i++){
                $path .= $i === 0 ? $dir[$i] : '/'.$dir[$i];
                if(!@ftp_chdir($ftp_conn, $path)){
                    $ftp_conn = AkFtpClient::connect();
                    if(@ftp_mkdir($ftp_conn, $path)){
                        if (defined('FTP_DEFAULT_DIR_MOD')){
                            if(!@ftp_site($ftp_conn, "CHMOD ".FTP_DEFAULT_DIR_MOD." $path")){
                                trigger_error('Could not set default mode for the FTP created directory '.$path, E_USER_NOTICE);
                            }
                        }
                    }else {
                        $ret = false;
                        break;
                    }
                }
            }
            return $ret;
        }
        return false;
    }

    function delete($path, $only_files = false)
    {
        $result = false;
        if($ftp_conn = AkFtpClient::connect()){
            $path = str_replace('\\','/',$path);
            $path = str_replace(array('..','./'),array('',''),$path);
            $keep_parent_dir = substr($path,-2) != '/*';
            $path = trim($path,'/*');
            $list = ftp_rawlist ($ftp_conn, "-R $path");
            $dirs = $keep_parent_dir ? array($path) : array();
            $files = array($path);
            $current_dir = $path.'/';
            if(count($list) === 1){
                $dirs = array();
                $files[] = $path;
            }else{
                foreach ($list as $k=>$line){
                    if(substr($line,-1) == ':'){
                        $current_dir = substr($line,0,strlen($line)-1).'/';
                    }
                    if (ereg ("([-d][rwxst-]+).* ([0-9]) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9]) ([0-9]{2}:[0-9]{2}) (.+)", $line, $regs)){
                        if((substr ($regs[1],0,1) == "d")){
                            if($regs[8] != '.' && $regs[8] != '..'){
                                $dirs[] = $current_dir.$regs[8];
                            }
                        }else {
                            $files[] = $current_dir.$regs[8];
                        }
                    }
                }
            }
            if(count($files) >= 1){
                array_shift($files);
            }
            rsort($dirs);
            foreach ($files as $file){
                if(!$result = @ftp_delete($ftp_conn,$file)){
                    trigger_error('Could not delete FTP file .'.$file , E_USER_NOTICE);
                    return false;
                }
            }
            if(!$only_files){
                foreach ($dirs as $dir){
                    if(!$result = @ftp_rmdir($ftp_conn,$dir)){
                        trigger_error('Could not delete FTP directory '.$dir , E_USER_NOTICE);
                        return false;
                    }
                }
            }
        }
        return $result;
    }


    function is_dir($path)
    {
        if($ftp_conn = AkFtpClient::connect()){
            $path = str_replace('\\','/',$path);
            $result = @ftp_chdir ($ftp_conn, $path);
            AkFtpClient::connect();
            return $result;
        }
        return false;
    }


}



?>