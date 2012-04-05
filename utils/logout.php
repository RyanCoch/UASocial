<?php
unset($_SESSION['uid']); 
setcookie ("eml", "", time() - 2629743, "/", "uasocial.com");
setcookie ("pwd", "", time() - 2629743, "/", "uasocial.com");
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), 'uid', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// ryan, you are genius!
if(session_is_registered("uadnses"))
 session_destroy();
 
header("Location: ../index.php");
?>
