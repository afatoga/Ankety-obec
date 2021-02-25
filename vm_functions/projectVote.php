<?php //declare(strict_types=1);

add_action( 'wp_ajax_saveUserVoteAjax', 'saveUserVoteAjax' );
//add_action( 'wp_ajax_checkPreviousUserVoteAjax', 'checkPreviousUserVoteAjax' );

function saveUserVoteAjax () 
{
    if (!empty($_POST)) 
    {
        $queried_post =  get_page_by_title( "Projekty", OBJECT, "page");
        $vm_allowedVotesCount = get_post_meta($queried_post->ID, "vm_allowedVotesCount", true);

        $voteToSave = json_decode(stripslashes($_POST['userVote']), true);
        
        if ($voteToSave === null) 
        {
            wp_send_json_error();
        }
        
        else {

            $voteItemsSum = 0;
            $preparedVoteToSave = [];
            
            foreach ($voteToSave as $id => $voteItemValue) 
            {   
                if (!filter_var($id, FILTER_SANITIZE_STRING)) 
                {
                    wp_send_json_error();
                }

                if (!filter_var($voteItemValue, FILTER_SANITIZE_NUMBER_INT)) 
                {
                    wp_send_json_error();
                }

                $voteItemsSum += $voteItemValue;
                
                if ($votesSum > $vm_allowedVotesCount)
                {
                    wp_send_json_error();
                }

                // zapis z 'rating_xx' do 'xx'
                if (($position = strpos($id, "_")) !== FALSE) 
                { 
                    $preparedVoteToSave[substr($id, $position+1)] = $voteItemValue; 
                }
            }

            if (getPreviousUserVote()) 
            {

                if (canUserRemakeVote()) 
                {   
                    // kontrola anulace hlasovani
                    if (!empty($voteToSave)) 
                    {
                        deleteUserVote();
                    }

                } else {
                    wp_send_json_error();
                }

            }
        
            $savedVote = saveUserVote($preparedVoteToSave);

            if ($savedVote) 
            {
                wp_send_json_success( $savedVote );
            }
           
        }
    }   

    wp_send_json_error();
}

function saveUserVote(array $userVote) 
{
   
   global $wpdb;
   $userId = get_current_user_id();
   $projectVotes = $wpdb->prefix . 'project_votes';

   if (!empty($userVote)) 
   {
       $posts = $wpdb->prefix . 'posts';
       $now = new DateTime();
       $currentTime = $now->format('Y-m-d H:i:s');

       foreach($userVote as $postId => $value) 
       {
            $postId = (int) $postId;
            $sqlTitle = $wpdb->prepare("SELECT `post_Title` FROM `$posts` WHERE `ID` = %d", $postId);

                if ($wpdb->get_var($sqlTitle) !== NULL) 
                {
                    $userVote[$postId] = array($userVote[$postId], $wpdb->get_var($sqlTitle));
                }

                if ($value > 0) 
                {
                    $sql = $wpdb->prepare("INSERT INTO `$projectVotes` (`post_Id`, `user_Id`, `vote_insertTime`, `vote_positive`) VALUES (%d, %d, %s, %d)", $postId, $userId, $currentTime, $value);
                    
                }

            $wpdb->query($sql);
            
        }
        
        return $userVote;

   } else  {
        // anulace hlasovani
        $sql = $wpdb->prepare("UPDATE `$projectVotes` SET `vote_positive` = '0' WHERE `user_Id` = %d", $userId);
        $wpdb->query($sql);

        return 'voteInvalidated';
   }

}

function deleteUserVote () 
{
    global $wpdb;
    
    try {
        $userId = get_current_user_id();
        $projectVotes = $wpdb->prefix . 'project_votes';

        $sql = $wpdb->prepare("DELETE FROM `$projectVotes` WHERE `user_Id` = %d", $userId);
        $wpdb->query($sql);

    } catch (Exception $e) {
        return 'Error! '. $wpdb->last_error;
    }

}

function getPreviousUserVote () 
{
    global $wpdb;
    
    $userId = get_current_user_id();
    $projectVotes = $wpdb->prefix . 'project_votes';

    $sql = $wpdb->prepare("SELECT `user_Id` FROM `$projectVotes` WHERE `user_Id` = %d", $userId);
     
    if ($wpdb->get_var($sql) !== NULL) {
        return true;
    }

    return;
}

function canUserRemakeVote () 
{

    global $wpdb;
    
    $userId = get_current_user_id();
    $projectVotes = $wpdb->prefix . 'project_votes';

    $sql = $wpdb->prepare("SELECT `user_Id`, `vote_insertTime` FROM `$projectVotes` WHERE `user_Id` = %d", $userId);
    $row = $wpdb->get_row($sql);

    if ($row !== null) {
        $queried_post =  get_page_by_title( "Projekty pravidla", OBJECT, "page");
        $vm_daysToChangeVote = get_post_meta($queried_post->ID, "vm_daysToChangeVote", true);
        
        $timestamp = strtotime($row->vote_insertTime); //moment insertu
        $timeString = "-". (int)$vm_daysToChangeVote ."day"; 
        $timePeriod = strtotime($timeString); //moment pred x dny
        

        if ($timePeriod <= $timestamp) 
        {   
            return true;
        }
    }
    
    return;
}

function loadCurrentVoteState () 
{
       global $wpdb;
       $userId = get_current_user_id();
       $projectVotes = $wpdb->prefix . 'project_votes';
       $posts = $wpdb->prefix . 'posts';

       $sqlCurrentState = $wpdb->prepare("SELECT `post_Id`, `user_Id`, `vote_positive`, `$posts`.`post_Title` 
                                          FROM `$projectVotes` 
                                          INNER JOIN `$posts` ON `post_Id` = `$posts`.`ID`
                                          WHERE `user_Id` = %d
                                          AND `vote_positive` = '1'",
                                          $userId);

       $results = $wpdb->get_results($sqlCurrentState, ARRAY_A);
        
       if (!empty($results)) {
                
                foreach ($results as $row) {
                    echo 'Projekt <strong>'. $row['post_Title'] . '</strong>, 1 hlas<br />';
                }

       } 
       
       if (empty($results) && getPreviousUserVote()) 
       {
           echo 'Hlasování anulováno';
       }

       return;

}




//add_action('wp_ajax_check_user', 'checkUser');
//add_action('wp_ajax_nopriv_check_user', 'checkUser');

?>