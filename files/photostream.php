<?php
/**
 * Code For Photostream Images
 * this file makes a curl call to get images of photostream(images uploaded publicly on flickr)
 * return the no of pages according to parameter specifies items per page
 * Dynamic pagination
 */
for ($i = 1; $i <= $_SESSION['TOTALPAGES']; $i++) {
    if (isset($_POST['mypage_' . $i])) {
        $_SESSION['current'] = $_POST['mypage_' . $i];
        update_option('mypage', $_SESSION['current']);
    } elseif (isset($_POST['pre'])) {
        $_SESSION['current'] = $_SESSION['pre'];
        update_option('mypage', $_SESSION['current']);
    } elseif (isset($_POST['next'])) {
        $_SESSION['current'] = $_SESSION['next'];
        update_option('mypage', $_SESSION['current']);
    } elseif (isset($_POST['first'])) {
        $_SESSION['current'] = $_SESSION['first'];
        update_option('mypage', $_SESSION['current']);
    } elseif (isset($_POST['last'])) {
        $_SESSION['current'] = $_SESSION['last'];
        update_option('mypage', $_SESSION['current']);
    }
}
if (isset($_POST['goto'])) {
    flickr_options_update_goto();
}
if ($_SESSION['TOTALPAGES'] < $_SESSION['current']) {
    $_SESSION['current'] = 1;
    update_option('mypage', $_SESSION['current']);
}
if (get_option('items') != '' && ctype_digit(get_option('items'))) {
    $mypage = get_option('items');
} else {
    $mypage = 10;
}
if (get_option('presource')!=get_option('source')) {
    $_SESSION['current'] = 1;
    update_option('mypage', $_SESSION['current']);
    update_option('presource',get_option('source'));
    
}
$photourl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=' . get_option('api') . '&user_id=' . get_option('id') . '&per_page=' . $mypage . '&page=' . get_option('mypage') . '&format=php_serial';
$my_obj = curl_call($photourl);
$my_obj = unserialize($my_obj);

$mypic = $my_obj['photos'];
$final = $mypic['photo'];
$pages = $mypic['pages'];
$_SESSION['TOTALPAGES'] = $pages;

?>
<form method="POST" name="page_style" action=''> 
    <input type="hidden" name="totslpg" value="<?php echo $pages ?>"/>
    <div class="holder">
        <?php
        if ($pages > 1) {
            if (get_option('mdrange') != '' && ctype_digit(get_option('mdrange'))) {
                $myrange = get_option('mdrange');
            } else {
                $myrange = 5;
            }
            if (get_option('first') != '') {
                $first = get_option('first');
            } else {
                $first = 'First';
            }
            if (get_option('pre') != '') {
                $prev = get_option('pre');
            } else {
                $prev = 'Prev';
            }
            if (get_option('next') != '') {
                $next = get_option('next');
            } else {
                $next = 'Next';
            }
            if (get_option('last') != '') {
                $last = get_option('last');
            } else {
                $last = 'Last';
            }
            echo '<input type="submit" class="navbutton" name="first" value="' . $first . '">';
            $_SESSION['first'] = 1;
            echo '<input type="submit" class="navbutton" name="pre" value="' . $prev . '">';
            if ($_SESSION['current'] > 1) {
                $_SESSION['pre'] = $_SESSION['current'] - 1;
            } else {
                $_SESSION['pre'] = 1;
            }
            if ($myrange > $_SESSION['TOTALPAGES']) {
                $myrange = $_SESSION['TOTALPAGES'];
            }
            if ($_SESSION['current'] == 1 || $_SESSION['current'] == '') {

                for ($i = 1; $i <= $myrange; $i++) {
                    ?>
                    <input type="submit" class="navbutton" name="mypage_<?php echo $i; ?>"  value="<?php echo $i; ?>" <?php
            if ($i == $_SESSION['current']) {
                echo 'id="currentnav"';
            }
                    ?> >
                           <?php
                       }
                   } elseif ($_SESSION['current'] == $_SESSION['TOTALPAGES']) {
                       $range = $myrange - 1;
                       for ($i = $_SESSION['current'] - $range; $i <= $_SESSION['current']; $i++) {
                           ?>
                    <input type="submit" class="navbutton" name="mypage_<?php echo $i; ?>"  value="<?php echo $i; ?>" <?php
               if ($i == $_SESSION['current']) {
                   echo 'id="currentnav"';
               }
                           ?> >
                           <?php
                       }
                   } else {

                       $range = $myrange;
                       if ($range % 2 == 0) {
                           $mrange = $range / 2;
                           $frange = $mrange - 1;
                       } else {
                           $range = $range - 1;
                           $mrange = $range / 2;
                           $frange = $range / 2;
                       }
                       $starting = $_SESSION['current'] - $mrange;
                       $ending = $_SESSION['current'] + $frange;
                       if ($starting > 0)
                           $st = $starting;
                       else
                           $st = 1;
                       if ($ending <= $_SESSION['TOTALPAGES'])
                           $end = $ending;
                       else
                           $end = $_SESSION['TOTALPAGES'];
                       for ($i = $st; $i <= $end; $i++) {
                           ?>
                    <input type="submit" class="navbutton" name="mypage_<?php echo $i; ?>"  value="<?php echo $i; ?>" <?php
               if ($i == $_SESSION['current']) {
                   echo 'id="currentnav"';
               }
                           ?> >
                           <?php
                       }
                   }
                   echo '<input type="submit" class="navbutton" name="next" value="' . $next . '">';
                   if ($_SESSION['current'] < $_SESSION['TOTALPAGES']) {
                       $_SESSION['next'] = $_SESSION['current'] + 1;
                   } else {
                       $_SESSION['next'] = $_SESSION['TOTALPAGES'];
                   }
                   echo '<input type="submit" class="navbutton" name="last" value="' . $last . '">';
                   $_SESSION['last'] = $_SESSION['TOTALPAGES'];
                   ?>                    
            <select name="text" class="navbutton">
                <?php for ($i = 1; $i <= $_SESSION['TOTALPAGES']; $i++) { ?>
                    <option value="<?php echo $i; ?>" ><?php echo $i; ?></option> <?php } ?>         
            </select>                    
            <input type="submit" class="navbutton" name="goto" value="GoTo">
        <?php } ?>
    </div>
</form>

<?php
if ($final) {
    foreach ($final as $images) {
        $imgsize = get_option('imgsize');
        $img_src = 'http://farm' . $images['farm'] . '.staticflickr.com/' . $images['server'] . '/' . $images['id'] . '_' . $images['secret'] . '_m.jpg';
        $final_src = str_replace('_m', $imgsize, $img_src);
        $href = 'http://farm' . $images['farm'] . '.staticflickr.com/' . $images['server'] . '/' . $images['id'] . '_' . $images['secret'] . '_b.jpg';
        $myoption = get_option('fancy');
        if ($myoption == 'fancybox') {
            $mygrp = 'gallery';
        } elseif ($myoption == 'fancybox-effects-b') {
            $mygrp = '';
        } elseif ($myoption == 'fancybox-buttons') {
            $mygrp = 'button';
        } elseif ($myoption == 'fancybox-thumbs') {
            $mygrp = 'thumb';
        }
        ?>
        <a href="<?php echo $href; ?>" class='<?php echo $myoption; ?>' data-fancybox-group='<?php echo $mygrp; ?>'>
            <img src="<?php echo $final_src; ?>" /> 
        </a>

        <?php
    }
} else {
    echo '<p class="error"> Oops... Page Not Found.</p>';
}
?>