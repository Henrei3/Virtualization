FROM osixia/openldap

ENV LDAP_ORGANISATION="LdapVirtualisation" 

COPY Scripts/bootstrap.ldif /container/service/slapd/assets/config/bootstrap/ldif/custom/
