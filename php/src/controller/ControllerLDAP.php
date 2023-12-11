<?php
namespace App\LDAP\controller;

use App\LDAP\config\Conf;
use App\LDAP\controller\AbstractController;
use App\LDAP\model\Repository\LDAPConnexion;

class ControllerLDAP extends AbstractController{

    public static $repertoire_choisie;

    public static function choixRepertoire(){
        self::$repertoire_choisie = $_GET["dirOptions"];    
        if(strcmp(self::$repertoire_choisie,"Local") == 0){
            LDAPConnexion::toggleLocal();
        }else{
            LDAPConnexion::toogleIUT();
        }
        self::afficheVue("authentification.php",["Pagetitle"=>"Authentification","directory"=>self::$repertoire_choisie]);
    }

    public static function checkUser() {
        $ldap_login = $_GET["user"];
        $ldap_password = $_GET["pass"];
        $ldap_searchfilter = "(uid={$ldap_login})";
        echo "search filter = ". $ldap_searchfilter;
        $ldap_conn = LDAPConnexion::getInstance();
        $baseDn = LDAPConnexion::getBaseDn(); 

        $bind_result = ldap_bind($ldap_conn, LDAPConnexion::getDnCustom("admin"), "passadmin");
        if(!$bind_result){ echo "Bind avec admin échué";}

        $search = ldap_search($ldap_conn, $baseDn, $ldap_searchfilter, array());
        
        $user_result = ldap_get_entries($ldap_conn, $search);
        print_r($user_result);

        // on verifie que l’entree existe bien
        $user_exist = $user_result["count"] == 1;

        
        // si l’utilisateur existe bien,
        $passwd_ok = false;
        if($user_exist) {
        // $dn = "uid=".$ldap_login.",ou=Ann1,ou=Etudiants,ou=People,dc=info,dc=iutmontp,dc=univ-montp2,dc=fr";
        $dn = $user_result[0]["dn"];
        // Requete log utilisateur avec API REST
        self::sendLogs($ldap_login,$dn);
        $passwd_ok = ldap_bind(LDAPConnexion::getInstance(), $dn, $ldap_password);
        
        }
        // Si l'utilisateur ou l'admin se sont connectés
        if($passwd_ok) {
            self::afficheVue("view.php",["Pagetitle"=>"Bienvenue","cheminVueBody"=>"bienvenue.php", "utilisateur"=>$ldap_login]);
        }
        else{
            self::afficheVue("authentification.php",["Pagetitle"=> "Erreur de Connexion","directory"=>self::$repertoire_choisie, "errormessage"=>ldap_error($ldap_conn)]);
        }
    }
    private static function sendLogs($username, $userDn){
        $params = [
            'user' => $username,
            'dn'=> $userDn
        ];
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "localhost:8080");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . "kjlmopq",
        ]);

        $response = curl_exec($curl);

        // Check for errors
        if (curl_errno($curl)) {
            echo 'Error: ' . curl_error($curl);
        } else {
            // Decode the JSON response
            $responseData = json_decode($response, true);

            // Output the generated text
            echo $responseData['choices'][0]['text'];
        }
        // Close cURL session
        curl_close($curl);
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
