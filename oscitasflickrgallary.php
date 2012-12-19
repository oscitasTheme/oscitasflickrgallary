<?php /*
  Plugin Name: Oscitas Flickr Gallery
  Plugin URI: http://www.oscitas.com
  Description: This plugin is used to show your flickr images on our wordpress web site.
  Provide choice which images user want to show on website.
  User can display images of Public Group/Photosets/Galleries/Photostream from its flickr account.
  Version: 1.0
  Author:osCitas Themes
  Author URI: http://www.oscitasthemes.com

 */ ?>
<?php
session_start();
define('$root', dirname(__FILE__));
require_once($root . '/files/functions.php');
add_action('admin_enqueue_scripts', 'flickr_gallery_init');

/**
 * function to include required JS and CSS files To plugin settings page
 */
function flickr_gallery_init() {
    $as_tejus_plugin_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
    if (is_my_plugin_screen()) {
        wp_enqueue_style('admin_panel', $as_tejus_plugin_path . "css/admin_panel.css");
        wp_enqueue_script('flickr_gallery', $as_tejus_plugin_path . "lib/flickr_gallery.js");
    }
}

add_action('init', 'flickr_gallery_front_init');
/**
 * Function to add JS and CSS files to front end 
 */
function flickr_gallery_front_init() {
    if (!is_admin()) {
        if (is_shortcode_defined('flickr_gallery')) {
            $as_tejus_plugin_path = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
            wp_enqueue_style('flickr_gallery', $as_tejus_plugin_path . "css/front_end.css");
            wp_enqueue_style('jquery.fancybox', $as_tejus_plugin_path . "source/jquery.fancybox.css");
            wp_enqueue_style('jquery.fancybox-buttons', $as_tejus_plugin_path . "source/helpers/jquery.fancybox-buttons.css");
            wp_enqueue_style('jquery.fancybox-thumbs', $as_tejus_plugin_path . "source/helpers/jquery.fancybox-thumbs.css");
            // enqueue scripts
            wp_register_script('myjquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
            wp_enqueue_script('myjquery');
            wp_enqueue_script('jquery.mousewheel-3.0.6.pack', $as_tejus_plugin_path . "lib/jquery.mousewheel-3.0.6.pack.js");
            wp_enqueue_script('flickr_gallery', $as_tejus_plugin_path . "lib/flickr_gallery.js");
            wp_enqueue_script('jquery.fancybox', $as_tejus_plugin_path . "source/jquery.fancybox.js");
            wp_enqueue_script('jquery.fancybox-buttons', $as_tejus_plugin_path . "source/helpers/jquery.fancybox-buttons.js");
            wp_enqueue_script('jquery.fancybox-thumbs', $as_tejus_plugin_path . "source/helpers/jquery.fancybox-thumbs.js");
        }
    }
}

function is_my_plugin_screen() {
    $screen = get_current_screen();
    if (is_object($screen) && $screen->id == 'toplevel_page_flickr_gallery') {
        return true;
    } else {
        return false;
    }
}

function is_shortcode_defined($shortcode) {
    global $shortcode_tags;
    if (isset($shortcode_tags[$shortcode])) {
        return TRUE;
    } else {
        return FALSE;
    }
}

add_action('admin_menu', 'flickr_gallery_toaddmymenu');

/**
 * function to add various menus of plugin to admin panel 
 */
function flickr_gallery_toaddmymenu() {
    /* adding top level menu */
    add_menu_page('flickr gallery', 'Flickr Gallery Options', 'manage_options', 'flickr_gallery', 'flickr_gallery_start');
}

/**
 *  function to be showed in front end short code of this function is added in any page/post/template to show iamges on wordpress website 
 */
function final_flickr_gallery() {
    ?>

    <div id="flickrImages">

    <?php
    /**
     *  Code to get Photoset Images 
     */
    if (get_option('source') == 'photoset') {
        if (get_option('api') && get_option('id') && get_option('photoset') != '') {
            require_once($root . '/files/photoset.php');
        }
    }
    /*
     *  Code to get Group Images 
     */
    if (get_option('source') == 'group') {
        if (get_option('api') && get_option('id') && get_option('group') != '') {
            require_once($root . '/files/group.php');
        }
    }
    /*
     *  Code to get Gallery Images 
     */
    if (get_option('source') == 'gallery') {
        if (get_option('api') && get_option('id') && get_option('gallery') != '') {
            require_once($root . '/files/gallery.php');
        }
    }
    /*
     *  Code to get Photostream Images 
     */
    if (get_option('source') == 'photostream') {
        if (get_option('api') && get_option('id') != '') {
            require_once($root . '/files/photostream.php');
        }
    }
    echo '</div>';
}

/*
 *  function to show flickr options on admin panel this function show a option for plugin where user set required values to 
 * display flickr images     
 */

function flickr_gallery_start() {
    ?>
        <div id="intro" >
            Use shortcode <b>[flickr_gallery]</b> to show Flickr Gallery in posts or pages<br />
            OR <br />
            call this function in template file  <b> do_shortcode('[flickr_gallery]');</b><br />
        </div> 
    <?php
    if ($_POST['update_details'] == 'true') {
        flickr_options_update_details();
    }
    ?>
        <form method="POST" id="admin-details" action=""><input type="hidden" name="update_details" value="true" /> 
            <div class="upper">
                <div class="options_head"><h2>Flickr User Details</h2></div>
                <div class="align"><lable class="labels">Flickr Api</lable>

                    <input type="text" id="api" name="api" size="20" value="<?php echo get_option('api'); ?>"> &nbsp;&nbsp;<font size='2'>Don't have a Flickr API Key?  Get it from <a href="http://www.flickr.com/services/api/keys/" target='blank'>here.</a> Go through the <a href='http://www.flickr.com/services/api/tos/'>Flickr API Terms of Service.</a></font>
                </div>
                <div class="align"><lable class="labels">User Flickr Id</lable>
                    <input type="text" id="id" name="id" size="20" value="<?php echo get_option('id'); ?>">
                </div>

    <?php
    $invalid = invalid();
    echo $invalid;
    ?>

                <input type="submit"  name="submit" id="b2" value="Submit" />
            </div>
        </form>
    <?php
    if ($_POST['update_flickr'] == 'true') {
        flickr_options_update_gallery();
    }
    ?>
        <form method="POST" id="admin_styles" name="admin_styles" action="" onsubmit="greeting()"><input type="hidden" name="update_flickr" value="true" /> 
            <div class="upper"><br />
                <div class="options_head"><h2>Flickr Gallery Options</h2></div>
                <div id="options_left_src" class="align"><lable class="labels">Source</lable>

                    <select name="source" id="source" onChange="onof(this.value)">
                        <option value="def" disabled="disabled" selected="selected">Select Source</option>
                        <option value="photoset" <?php
    if (get_option('source') == "photoset") {
        echo "selected";
    }
    ?>>Photoset</option>
                        <option value="gallery" <?php
                    if (get_option('source') == "gallery") {
                        echo "selected";
                    }
    ?>>Gallery</option>
                        <option value="photostream" <?php
                    if (get_option('source') == "photostream") {
                        echo "selected";
                    }
    ?>>Photo Stream</option>
                        <option value="group" <?php
                    if (get_option('source') == "group") {
                        echo "selected";
                    }
    ?>>Group</option>
                    </select>
                </div>

    <?php
    /**
     * code to get a dropdown list of photosets made by user on flickr
     */
    if (get_option('api') != '' && get_option('id') != '') {

        $photourl = 'http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=' . get_option('api') . '&user_id=' . get_option('id') . '&format=php_serial';
        $rsp_obj = curl_call($photourl);
        $rsp_obj = unserialize($rsp_obj);
        if ($rsp_obj['stat'] == 'ok') {
            $photoset = $rsp_obj['photosets'];
            $tot = $photoset['total'];
            if ($tot == 0) {
                echo '<div id="options_left_1" class="align" ><label class="labels">Photosets</label><h4 class="error">Have have not created any Photoset yet</h4></div>';
            } else {
                $try = $photoset['photoset'];
                ?>


                <div id="options_left_1" class="align"><lable class="labels">Photosets</lable>
                  <select name="photoset"  >
                    <option value="def1" disabled="disabled">Select Photoset</option>
					<?php
                    foreach ($try as $photo) {
                        $photoset_id = $photo['id'];
                        $name = $photo['title'];
                        $photoset_name = $name['_content'];
                        ?>
                                            <option value="<?php echo $photoset_id; ?>"  <?php
                        if (get_option('photoset') == $photoset_id) {
                            echo "selected";
                        }
                        ?> >
                            <?php echo $photoset_name; ?>
                      </option>
                      <?php } ?>      
                    </select>
                 </div>
                <?php
            }
        }
    }

    /**
     * code to get a dropdown list of galleries made by user on flickr
     */
    if (get_option('api') && get_option('id') != '') {
        $photourl = 'http://api.flickr.com/services/rest/?method=flickr.galleries.getList&api_key=' . get_option('api') . '&user_id=' . get_option('id') . '&format=php_serial';
        $rsp_obj = curl_call($photourl);
        $rsp_obj = unserialize($rsp_obj);
        if ($rsp_obj['stat'] == 'ok') {
            $photoset = $rsp_obj['galleries'];


            $tot = $photoset['total'];
            if ($tot == 0) {
                echo '<div id="options_left_2" class="align"><lable class="labels">Galleries</lable><h4 class="error">Have have not created any gallery yet</h4></td></tr></div>';
            } else {
                $try = $photoset['gallery'];
                ?>
                  <div id="options_left_2" class="align"><lable class="labels">Galleries</lable>
                     <select name="gallery">
                        <option value="def2" disabled="disabled">Select Gallery</option>
						<?php
                        foreach ($try as $photo) {
                            $photoset_id = $photo['id'];
                            $name = $photo['title'];
                            $photoset_name = $name['_content'];
                            ?>
                            <option value="<?php echo $photoset_id; ?>" <?php
                            if (get_option('gallery') == $photoset_id) {
                                echo "selected";
                            } ?> >
								<?php echo $photoset_name; ?>
                            </option>
                        <?php } ?>          
                     </select>
                   </div>
                        <?php
                    }
                }
            }
    /**
     * code to get images posted by user publically(photostream)
     */
    if (get_option('api') && get_option('id') != '') {
        $photourl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key=' . get_option('api') . '&user_id=' . get_option('id') . '&format=php_serial';
        $rsp_obj = curl_call($photourl);
        $rsp_obj = unserialize($rsp_obj);
        if ($rsp_obj['stat'] == 'ok') {
            $photoset = $rsp_obj['photos'];
            $tot = $photoset['total'];
            if ($tot == 0) {
                echo '<h4 class="error">Have have not uploaded any images yet</h4>';
            } else {
                $try = $photoset['photo'];
                ?>
                            <?php
                        }
                    }
                }

                /**
                 * code to get a dropdown list of groups made by user on flickr
                 */
                if (get_option('api') && get_option('id') != '') {

                    $photourl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicGroups&api_key=' . get_option('api') . '&user_id=' . get_option('id') . '&format=php_serial';
                    $rsp_obj = curl_call($photourl);
                    $rsp_obj = unserialize($rsp_obj);
                    if ($rsp_obj['stat'] == 'ok') {
                        $photoset = $rsp_obj['groups'];

                        $try = $photoset['group'];

                        if (empty($try)) {
                            echo '<div id="options_left_3" class="align"><lable class="labels">Groups</lable><h4 class="error">Have have not created any group yet</h4></div>';
                        } else {
                            ?>
                            <div id="options_left_3" class="align"><lable class="labels">Groups</lable>
                                <select name="group">
                                    <option value="def3" disabled="disabled">Select Group</option>
									<?php
                                    foreach ($try as $photo) {
                                        $photoset_id = $photo['nsid'];
                                        $photoset_name = $photo['name'];
                                        ?>
                                        <option value="<?php echo $photoset_id; ?>" <?php
                                        if (get_option('group') == $photoset_id) {
                                            echo "selected";
                                        }
                                        ?> ><?php echo $photoset_name; ?></option>                                    
                                                        <?php } ?>      
                                  </select>
                           </div>
                        <?php
							}
						}
					}
					?>

                <!-- Drop down list for Image sizes  -->
                <div class="align"><lable class="labels">Image Size</lable>
                    <select name="imgsize">
                        <option value="_s" <?php
            if (get_option('imgsize') == "_s") {
                echo "selected";
            }
    ?> >small square (75x75)</option>

                        <option value="_q" <?php
                    if (get_option('imgsize') == "_q") {
                        echo "selected";
                    }
    ?>  >large square 150x150</option>

                        <option value="_t" <?php
                    if (get_option('imgsize') == "_t") {
                        echo "selected";
                    }
    ?>  >thumbnail</option>

                        <option value="_m" <?php
                    if (get_option('imgsize') == "_m") {
                        echo "selected";
                    }
    ?>  >small, 240 on longest side</option>

                        <option value="_n" <?php
                    if (get_option('imgsize') == "_n") {
                        echo "selected";
                    }
    ?>  >small, 320 on longest side</option>

                        <option value="_z" <?php
                    if (get_option('imgsize') == "_z") {
                        echo "selected";
                    }
    ?>  >medium (640, 640)on longest side</option>

                        <option value="_c" <?php
                    if (get_option('imgsize') == "_c") {
                        echo "selected";
                    }
    ?>  >medium 800, 800 on longest side</option>
                    </select>
                </div>
                <!-- Drop down list for Fancybox Options  -->
                <div class="align"><lable class="labels">Fancybox Style</lable>
                    <select name="fancy">
                        <option value="fancybox" <?php
                    if (get_option('fancy') == "fancybox") {
                        echo "selected";
                    }
    ?> >Basic</option>

                        <option value="fancybox-effects-b" <?php
                    if (get_option('fancy') == "fancybox-effects-b") {
                        echo "selected";
                    }
    ?>  >Effects</option>

                        <option value="fancybox-buttons" <?php
                    if (get_option('fancy') == "fancybox-buttons") {
                        echo "selected";
                    }
    ?>  >Button Support</option>

                        <option value="fancybox-thumbs" <?php
                    if (get_option('fancy') == "fancybox-thumbs") {
                        echo "selected";
                    }
    ?>  >Thumbnail Support</option>
                    </select>
                </div>

                <!--<div class="options_head"><h2>Navigation Options</h2></div>-->
                <!-- Field for Items per page  -->
                <div class="align"><lable class="labels">Items Per Page</lable>
                    <input type="text" id="items" name="items" size="10" value="<?php
                    if (get_option('items') != '') {
                        echo get_option('items');
                    } else {
                        echo 10;
                    }
    ?>">
                </div>
                <!-- Field for Mid Range  -->
                <div class="align"><lable class="labels">Mid Range</lable>
                    <input type="text" id="mdrange" name="mdrange" size="10" value="<?php
                if (get_option('mdrange') != '') {
                    echo get_option('mdrange');
                } else {
                    echo 5;
                }
    ?>">
                </div>
                <!-- Field for First button text  -->
                <div class="align"><lable class="labels">'First' Text</lable>
                    <input type="text" id="first" name="first" size="10" value="<?php
                if (get_option('first') != '') {
                    echo get_option('first');
                } else {
                    echo 'First';
                }
    ?>">
                </div>
                <!-- Field for Last button text  -->
                <div class="align"><lable class="labels">'Last' Text</lable>
                    <input type="text" id="last" name="last" size="10" value="<?php
                if (get_option('last') != '') {
                    echo get_option('last');
                } else {
                    echo 'Last';
                }
    ?>">
                </div>
                <!-- Field for Previous button text  -->
                <div class="align"><lable class="labels">'Previous' Text</lable>
                    <input type="text" id="pre" name="pre" size="10" value="<?php
                if (get_option('pre') != '') {
                    echo get_option('pre');
                } else {
                    echo 'Prev';
                }
    ?>">
                </div>
                <!-- Field for Next button text  -->
                <div class="align"><lable class="labels">'Next' Text</lable>
                    <input type="text" id="next" name="next" size="10" value="<?php
                if (get_option('next') != '') {
                    echo get_option('next');
                } else {
                    echo 'Next';
                }
    ?>">
                </div>
                <input type="submit"  name="button2" id="button2" value="Save" />
            </div>
        </form> 

    <?php
}

/**
 *  short code
 * this shortcode is added on any post/page/template.           
 */
add_shortcode('flickr_gallery', 'final_flickr_gallery');
?>