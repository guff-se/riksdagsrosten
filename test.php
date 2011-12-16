<?php 

   $app_id = "196658153744046";
   $app_secret = "0349d42b3e5992fe738ee45c95248c7f";
   $my_url = "http://www.riksdagsrosten.se/test.php";

   
    // include the database Class //
    require_once 'classes/Database.class.php';
    $db = new Database();
    $db->connectSQL();

   
   session_start();
   $code = $_REQUEST["code"];

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
     $dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'] . "&scope=email";

     echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }

   if($_REQUEST['state'] == $_SESSION['state']) {
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     
     parse_str($response, $params);

     
     $graph_url = "https://graph.facebook.com/me?access_token=" . $params['access_token'];
     $user_profile = json_decode(file_get_contents($graph_url));
     
     
     if (isset($user_profile->id)) {
        $resultLogin = $db->executeSQL("SELECT * FROM Users WHERE facebook_id = " . $user_profile->id, "SELECT");
        if ($resultLogin->id) {
            $db->executeSQL("UPDATE Users SET senasteinloggning = now(), oauth='" . $fb_OAuth . "', email = '" . $user_profile->email . "' WHERE id = " . $resultLogin->id, "UPDATE");

            $_SESSION['user_id'] = $resultLogin->id;
            header("Location: http://www.riksdagsrosten.se/profil/");
        } else {

            $db->executeSQL("INSERT INTO Users (id,oauth,tilltalsnamn,efternamn,facebook_id,email,senasteinloggning)
                VALUES ('','" . $fb_OAuth . "','" . $user_profile->first_name . "','" . $user_profile->last_name . "'," . $user_profile->id . ",'" . $user_profile->email . "',now())", "INSERT");


            $createdId = $db->executeSQL("SELECT * FROM Users WHERE facebook_id = " . $user_profile['id'], "SELECT");

            $_SESSION['user_id'] = $createdId->id;

            header("Location: http://www.riksdagsrosten.se/profil/");
        }
    }
     
     //echo("Hello " . $user->name);
     //header("Location: http://www.riksdagsrosten.se/")
     
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }

 ?>