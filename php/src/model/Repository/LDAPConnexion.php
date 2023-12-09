<?php
namespace App\LDAP\Model\Repository;

use App\LDAP\config\Conf;
use App\LDAP\config\ConfLocal;
class LDAPConnexion{
    public static $ldapConnexion = null;
    private static bool $local = true;
    private static function connect(){
        
        $ldap_conn = null;
        if(self::$local){
            $ldap_conn = ldap_connect(ConfLocal::$ldap_host, ConfLocal::$ldap_port);
            if($ldap_conn){
                echo "Connexion en Local réalisé";
            }else{
                echo "Connexion en Local échué";
            }
        }
        else{
            $ldap_conn = ldap_connect(Conf::$ldap_host, Conf::$ldap_port);
            if($ldap_conn){
                echo "Connexion avec l'IUT fructueuse";
            }else{
                echo "Connexion avec l'IUT echué";
            }
            
        }
        ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        return $ldap_conn;
    }
    public static function getInstance(){
        if(is_null(self::$ldapConnexion)){
            self::$ldapConnexion = self::connect();
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
    public static function getBaseDn(){
        return self::$local ? ConfLocal::$ldap_basedn : Conf::$ldap_basedn;
    }
    public static function getDnCustom($user){
        return self::$local ? "cn={$user}," . ConfLocal::$ldap_basedn : "cn={$user}" . Conf::$ldap_basedn;
    }
}