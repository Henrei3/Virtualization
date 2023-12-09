<?php

namespace App\LDAP\config;


class ConfLocal {
    // on definit les parametres de la connexion LDAP
    public static $ldap_host = "openldap";

    public static $ldap_basedn = "dc=ldap,dc=local,dc=fr";
    public static $ldap_port = 389;
    public static $ldap_conn = false;
}

?>
