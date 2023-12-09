<body>
    <form action="index.php" method="get">
        <p> Choissisez S'il vous plait le service LDAP à utiliser </p>
        <input type="hidden" name="action" value="choixRepertoire">
        <input type="hidden" name="controller" value="LDAP">
        <select name = "dirOptions" id="choose_LDAP"> 
            <option value="Local"> LDAP Local </option>
            <option value="IUT"> LDAP de l'IUT </option>
        </select>
        <input type="submit">Choisir</button>
    </form>
</body>
