#!/bin/bash
# KeyC_Config.sh

KEYCLOAK_HOST_PORT=${1:-"localhost:8082"}
echo
echo "KEYCLOAK_HOST_PORT: $KEYCLOAK_HOST_PORT"
echo

echo "Getting admin access token"
echo "=========================="

ADMIN_TOKEN=$(curl -s -X POST "http://$KEYCLOAK_HOST_PORT/realms/master/protocol/openid-connect/token" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "username=admin" \
  -d 'password=admin' \
  -d 'grant_type=password' \
  -d 'client_id=admin-cli' | jq -r '.access_token')

echo "ADMIN_TOKEN=$ADMIN_TOKEN"
echo

echo "Creating realm"
echo "=============="

curl -i -X POST "http://$KEYCLOAK_HOST_PORT/admin/realms" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"realm": "Virtulisation", "enabled": true}'

echo "Creating client"
echo "==============="

CLIENT_ID=$(curl -si -X POST "http://$KEYCLOAK_HOST_PORT/admin/realms/Virtulisation/clients" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"clientId": "nextcloud", "directAccessGrantsEnabled": true, "redirectUris": ["http://127.0.0.1:8082//*"]}' \
  | grep -oE '[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}')

echo "CLIENT_ID=$CLIENT_ID"
echo

echo "Getting client secret"
echo "====================="

SIMPLE_SERVICE_CLIENT_SECRET=$(curl -s -X POST "http://$KEYCLOAK_HOST_PORT/admin/realms/Virtulisation/clients/$CLIENT_ID/client-secret" \
  -H "Authorization: Bearer $ADMIN_TOKEN" | jq -r '.value')

echo "SIMPLE_SERVICE_CLIENT_SECRET=$SIMPLE_SERVICE_CLIENT_SECRET"
echo

echo "Creating client role"
echo "===================="

curl -i -X POST "http://$KEYCLOAK_HOST_PORT/admin/realms/Virtulisation/clients/$CLIENT_ID/roles" \
-H "Authorization: Bearer $ADMIN_TOKEN" \
-H "Content-Type: application/json" \
-d '{"name": "USERNextcloud"}'

ROLE_ID=$(curl -s "http://$KEYCLOAK_HOST_PORT/admin/realms/Virtulisation/clients/$CLIENT_ID/roles" \
  -H "Authorization: Bearer $ADMIN_TOKEN" | jq -r '.[0].id')

echo "ROLE_ID=$ROLE_ID"
echo

echo "Configuring LDAP"
echo "================"
pwd
ls -l 
LDAP_ID=$(curl -si -X POST "http://$KEYCLOAK_HOST_PORT/admin/realms/Virtulisation/components" \
  -H "Authorization: Bearer $ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '@Scripts/ldap-config.json' \
  | grep -oE '[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}')

echo "LDAP_ID=$LDAP_ID"
echo

echo "Sync LDAP Users"
echo "==============="

curl -i -X POST "http://$KEYCLOAK_HOST_PORT/admin/realms/Virtulisation/user-storage/$LDAP_ID/sync?action=triggerFullSync" \
  -H "Authorization: Bearer $ADMIN_TOKEN"

echo
echo "============="
