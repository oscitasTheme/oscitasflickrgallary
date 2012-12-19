<?php

/**
 * function to make a curl call
 * @param type $url (url sent to get flickr images)
 * @return return curl response i.e. flickr images
 */
function curl_call($url) {
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    return $file_contents;
}

/**
 * function to verrify if user submiited correct user id and api key
 * @return string return a string warning that 'null api key or user id' or 'invalid api key or user id'
 */
function invalid() {
    if (get_option('api') == '' || get_option('id') == '') {
        return "<h3 class='error'>Api Key and User Id Can't be null</h3>";
    } elseif (get_option('api') != '' && get_option('id') != '') {

        $photourl = 'http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=' . get_option('api') . '&user_id=' . get_option('id') . '&format=php_serial';
        $rsp_obj = curl_call($photourl);
        $rsp_obj = unserialize($rsp_obj);
        if ($rsp_obj['stat'] != 'ok') {
            return '<h4 class="error">Invalid User Id or API Key or Check your Internet Connection</h3>';
        }
    }
}

/**
 * function to update user api key and user id;
 */
function flickr_options_update_details() {
    update_option('id', $_POST['id']);
    update_option('api', $_POST['api']);
}
/**
 * function to update flickr gallery options
 */
function flickr_options_update_gallery() {
    update_option('fancy', $_POST['fancy']);
    update_option('random', $_POST['random']);
    update_option('items', $_POST['items']);
    update_option('first', $_POST['first']);
    update_option('last', $_POST['last']);
    update_option('pre', $_POST['pre']);
    update_option('next', $_POST['next']);
    update_option('mdrange', $_POST['mdrange']);
    update_option('imgsize', $_POST['imgsize']);
    update_option('pregroup', get_option('group'));
    update_option('prephotoset', get_option('photoset'));
    update_option('pregallery', get_option('gallery'));
    update_option('presource', get_option('source'));
    update_option('group', $_POST['group']);
    update_option('photoset', $_POST['photoset']);
    update_option('gallery', $_POST['gallery']);
    update_option('source', $_POST['source']);
}
/**
 * function to update value of go to button on front end
 */
function flickr_options_update_goto() {
    update_option('text', $_POST['text']);
    $_SESSION['goto'] = get_option('text');
    $_SESSION['current'] = $_SESSION['goto'];
    update_option('mypage', $_SESSION['current']);
}
?>
