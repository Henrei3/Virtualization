<?php
namespace App\LDAP\Model\Repository;

use App\LDAP\Config\Conf;

class LDAPConnexion{
    private static $ldapConnexion = null;
    
    private function __construct(){
        $ldap_conn = ldap_connect(Conf::$ldap_host, Conf::$ldap_port);
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        self::$ldapConnexion = $ldap_conn; 
    }
    public static function getInstance(){
        if(is_null(self::$ldapConnexion)){
            self::$instance = new LDAPConnexion();
        }
        return self::$ldapConnexion;
    }

}