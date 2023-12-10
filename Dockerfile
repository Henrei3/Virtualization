FROM osixia/openldap

ENV LDAP_ORGANISATION="LdapVirtualisation" \
    LDAP_DOMAIN="ldap.local.fr" 


COPY Scripts/bootstrap.ldif /container/service/slapd/assets/config/bootstrap/ldif/custom/