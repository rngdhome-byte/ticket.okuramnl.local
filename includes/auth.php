<?php
$user = $_POST['username'];
$pass = $_POST['password'];

$ldap_host = "11.30.1.5"; 
$ldap_domain = "@okuramnl.local"; 
$ldap_base_dn = "DC=okuramnl,DC=local"; 
$allowed_it_group = "CN=Info Tech,OU=IT,OU=Okura,DC=okuramnl,DC=local"; 
$allowed_superadmins = ["dani.renegado"]; 
$allowed_admins = ["marc.garcia", "percival.esugerra"]; 

$ldap_user = $user . $ldap_domain;
$ldap_conn = @ldap_connect($ldap_host);

if ($ldap_conn) {
    ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

    if (@ldap_bind($ldap_conn, $ldap_user, $pass)) {
        session_regenerate_id(true); 
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $user;
        $_SESSION['LAST_ACTIVITY'] = time();
        
        $_SESSION['role'] = 'End-User';
        $_SESSION['is_superadmin'] = false;
        $_SESSION['is_admin'] = false;
        $_SESSION['can_manage'] = false;
        $_SESSION['department'] = 'Unknown Department';
        $_SESSION['displayname'] = $user;
        $_SESSION['job_title'] = 'Staff';

        $filter = "(sAMAccountName=" . $user . ")";
        $attributes = array("memberof", "department", "displayname", "cn", "title");
        $search = @ldap_search($ldap_conn, $ldap_base_dn, $filter, $attributes);
        $entries = @ldap_get_entries($ldap_conn, $search);
        
        if ($entries["count"] > 0) {
            if (isset($entries[0]["department"][0])) $_SESSION['department'] = $entries[0]["department"][0];
            if (isset($entries[0]["title"][0])) $_SESSION['job_title'] = $entries[0]["title"][0];
            if (isset($entries[0]["displayname"][0])) $_SESSION['displayname'] = $entries[0]["displayname"][0];
            elseif (isset($entries[0]["cn"][0])) $_SESSION['displayname'] = $entries[0]["cn"][0];

            if (isset($entries[0]["memberof"])) {
                $memberof = $entries[0]["memberof"];
                for ($i = 0; $i < $memberof["count"]; $i++) {
                    if (strcasecmp($memberof[$i], $allowed_it_group) == 0) { 
                        $_SESSION['role'] = 'IT';
                        $lower_user = strtolower($user);
                        $_SESSION['is_superadmin'] = in_array($lower_user, array_map('strtolower', $allowed_superadmins));
                        $_SESSION['is_admin'] = in_array($lower_user, array_map('strtolower', $allowed_admins));
                        $_SESSION['can_manage'] = $_SESSION['is_superadmin'] || $_SESSION['is_admin'];
                        break; 
                    }
                }
            }
        }
        log_auth_attempt($user, "SUCCESS (" . $_SESSION['role'] . ")");
        header("Location: index.php");
        exit;
    } else { 
        $error = "Invalid Username or Password."; 
        log_auth_attempt($user, "FAILED - Wrong Password");
    }
    ldap_close($ldap_conn);
} else { 
    $error = "Could not connect to Domain Controller."; 
}
?>