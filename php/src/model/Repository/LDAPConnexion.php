<?php
namespace App\LDAP\Model\Repository;

use App\LDAP\config\Conf;
use App\LDAP\config\LocalConf;
class LDAPConnexion{
    private static $ldapConnexion = null;
    public static bool $local = false;
    private function __construct(){
    
        if(self::$local == false){
            $ldap_conn = ldap_connect(Conf::$ldap_host, Conf::$ldap_port);
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            self::$ldapConnexion = $ldap_conn;
        } 
        else{
            $ldap_conn = ldap_connect(LocalConf::$ldap_host, LocalConf::$ldap_port);
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            self::$ldapConnexion = $ldap_conn;
        }
    }
    public static function getInstance(){
        if(is_null(self::$ldapConnexion)){
            self::$instance = new LDAPConnexion();
        }
        return self::$ldapConnexion;
    }
    public static function toggleLocal(){
        self::$ldapConnexion = null;
        self::$local = true;
    }
    public static function toogleIUT(){
        self::$ldapConnexion = null;
        self::$local = false;
    }



}