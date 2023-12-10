FROM osixia/openldap

ENV LDAP_ORGANISATION="ldaplocal"


COPY Scripts/bootstrap.ldif /container/service/slapd/assets/config/bootstrap/ldif/custom/