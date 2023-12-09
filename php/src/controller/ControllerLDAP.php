<?php
namespace App\LDAP\controller;

use App\LDAP\config\Conf;
use App\LDAP\controller\AbstractController;
use App\LDAP\model\Repository\LDAPConnexion;

class ControllerLDAP extends AbstractController{

    public static function choixRepertoire(){
        $repertoire_choisie = $_GET["dirOptions"];    
        if(strcmp($repertoire_choisie,"Local") == 0){
            LDAPConnexion::toggleLocal();
        }else{
            LDAPConnexion::toogleIUT();
        }
        self::afficheVue("authentification.php",["Pagetitle"=>"Authentification","directory"=>$repertoire_choisie]);
    }

    public static function checkUser() {
        $ldap_login = $_GET["user"];
        $ldap_password = $_GET["pass"];
        $ldap_searchfilter = "(objectClass=*)";
        
        $ldap_conn = LDAPConnexion::getInstance();
        $baseDn = LDAPConnexion::getBaseDn(); 

        $bind_result = ldap_bind($ldap_conn, LDAPConnexion::getDnCustom("admin"), "passadmin");
        if(!$bind_result){ echo "Bind avec admin échué";}

        $search = ldap_search($ldap_conn, $baseDn, $ldap_searchfilter, array());
        
        $user_result = ldap_get_entries($ldap_conn, $search);
        // on verifie que l’entree existe bien
        print_r($user_result);
        $user_exist = $user_result["count"] == 1;
        // si l’utilisateur existe bien,
        $passwd_ok = false;
        if($user_exist) {
        $dn = "uid=".$ldap_login.",ou=Ann1,ou=Etudiants,ou=People,dc=info,dc=iutmontp,dc=univ-montp2,dc=fr";
        $passwd_ok = ldap_bind(LDAPConnexion::getInstance(), $dn, $ldap_password);
        }

        return $passwd_ok;
    }
    
    public static function listUsers() {
        $ldap_conn = LDAPConnexion::getInstance();
        //On recherche toutes les entres du LDAP qui sont des personnes
        $search = ldap_search($ldap_conn, Conf::$ldap_basedn, "(objectClass=person)");
        //On recupere toutes les entres de la recherche effectuees auparavant
        $resultats = ldap_get_entries($ldap_conn, $search);
        //Pour chaque utilisateur, on recupere les informations utiles
        for ($i=0; $i < count($resultats) - 1 ; $i++) {
        //On stocke le login, nom/prnom, la classe et la promotion de l’utilisateur courant
        $nomprenom = explode(" ", $resultats[$i]['displayname'][0]);
        $promotion = explode("=", explode(",", $resultats[$i]['dn'])[1])[1];
        }
    }
    public static function disconnect(){
        ldap_close(Conf::$ldap_conn);
    }
}

?>
