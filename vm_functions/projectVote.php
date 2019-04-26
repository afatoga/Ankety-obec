<?php //declare(strict_types=1);

add_action( 'wp_ajax_checkUserVoteAjax', 'checkUserVoteAjax' );

function checkUserVoteAjax () {

    if (!empty($_POST)) 
    {

        $voteToSave = json_decode(stripslashes($_POST['userRatings']), true);
        if($voteToSave === null) 
        {
            wp_send_json_error();
        }
        
        else 
        {   
           $sumPositiveRatings = 0; 
           $sumNegativeRatings = 0;
           

           foreach ($voteToSave as $id => $rating) 
           {   

                    if (!filter_var($id, FILTER_SANITIZE_STRING)) {
                        wp_send_json_error();
                    }

                    if ($rating > 0 && $rating <=2) 
                    {
                        $sumPositiveRatings += $rating;
                    }
                    else if ($rating > -2 && $rating < 0)
                    {   
                        $sumNegativeRatings += $rating;
                    }

                    else {
                        wp_send_json_error();
                    }
                    //kontrola souctu
                    if ($sumPositiveRatings > 5)
                    {
                        wp_send_json_error();
                    }
                    if ($sumPositiveRatings < 4 && $sumNegativeRatings <= -2)
                    {
                        wp_send_json_error();
                    }
                    if ($sumNegativeRatings < -2)
                    {
                        wp_send_json_error();
                    }
                    // zapis z 'rating_xx' do 'xx'
                    if (($position = strpos($id, "_")) !== FALSE) { 
                        $userVote[substr($id, $position+1)] = $rating; 
                    }
    
                    else {
                      wp_send_json_error();
                    }
            }

           // Kontrola predesleho zaznamu v db, pokud existuje smazat

           if (existsUserVote()) 
           {
               $deleteResult = deleteUserVote();

               if($deleteResult) 
               {
                   $saveResult = saveUserVote($userVote);

                   if ($saveResult) 
                   {
                       wp_send_json_success( $saveResult );
                   }
               }
           }

           else 
           {
               $saveResult = saveUserVote($userVote);

                   if ($saveResult) 
                   {
                       wp_send_json_success( $saveResult );
                   }
           }
        }
    }   

    wp_send_json_error();
}

function saveUserVote(array $userVote) {
   
   if (!empty($userVote)) 
   {
       global $wpdb;
       $userId = get_current_user_id();
       $projectVotes = $wpdb->prefix . 'project_votes';
       $posts = $wpdb->prefix . 'posts';
       $now = new DateTime();
       $currentTime = $now->format('Y-m-d H:i:s');

            foreach($userVote as $postId => $value) {
                $postId = (int) $postId;
                $sqlTitle = $wpdb->prepare("SELECT `post_Title` FROM `$posts` WHERE `ID` = %d", $postId);
                if ($wpdb->get_var($sqlTitle)!==NULL) {
                    $userVote[$postId] = array($userVote[$postId], $wpdb->get_var($sqlTitle));
                }

                if ($value > 0) {
                    $sql = $wpdb->prepare("INSERT INTO `$projectVotes` (`post_Id`, `user_Id`, `vote_insertTime`, `vote_positive`) VALUES (%d, %d, %s, %d)", $postId, $userId, $currentTime, $value);
                    
                }

                else {
                    $sql = $wpdb->prepare("INSERT INTO `$projectVotes` (`post_Id`, `user_Id`, `vote_insertTime`, `vote_negative`) VALUES (%d, %d, %s, %d)", $postId, $userId, $currentTime, $value);
                }
                $wpdb->query($sql);

            
            }
        

            return $userVote;

   }

   else {
       return false;
   }


}

function deleteUserVote () {
    // zvazit smazani nebo deaktivaci hlasu

    global $wpdb;
    
    try {
        $userId = get_current_user_id();
        $projectVotes = $wpdb->prefix . 'project_votes';

        $sql = $wpdb->prepare("DELETE FROM `$projectVotes` WHERE `user_Id` = %d", $userId);
        $wpdb->query($sql);
        return true;

    } catch (Exception $e) {
        return 'Error! '. $wpdb->last_error;
    }

}

function existsUserVote () {

    global $wpdb;
    
    $userId = get_current_user_id();
    $projectVotes = $wpdb->prefix . 'project_votes';

    $sql = $wpdb->prepare("SELECT `user_Id` FROM `$projectVotes` WHERE `user_Id` = %d", $userId);
     
    if ($wpdb->get_var($sql)!==NULL) {
        return true;
    }
    else {
        return false;
    }

}

function loadCurrentVoteState () {

       global $wpdb;
       $userId = get_current_user_id();
       $projectVotes = $wpdb->prefix . 'project_votes';
       $posts = $wpdb->prefix . 'posts';

       $sqlCurrentState = $wpdb->prepare("SELECT `post_Id`, `user_Id`, `vote_positive`, `vote_negative`, `$posts`.`post_Title` 
                                          FROM `$projectVotes` 
                                          LEFT OUTER JOIN `$posts` ON `post_Id` = `$posts`.`ID`
                                          WHERE `user_Id` = %d",
                                          $userId);
        
       if ($wpdb->get_results($sqlCurrentState)!==NULL) {
                $results = $wpdb->get_results($sqlCurrentState, ARRAY_A);
                foreach ($results as $row) {
                    $vote = ($row['vote_positive']=='0')?$row['vote_negative']:$row['vote_positive'];
                    echo '<strong>'. $row['post_Title'] . '</strong>, hodnocení:&nbsp;<strong>' .$vote.'</strong><br />';
                }
       }
       else {
           return null;
       }

}




//add_action('wp_ajax_check_user', 'checkUser');
//add_action('wp_ajax_nopriv_check_user', 'checkUser');

?>