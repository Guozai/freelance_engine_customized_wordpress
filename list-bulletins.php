<?php
/**
 * Created by Atom
 * User: Yiping Guo
 * Date: 12/24/2020
 * Time: 10:19 AM
 * Use for page author.php and page-profile.php
 */
global $wpdb;
$is_edit = false;
if(is_author()){
	$author_id        = get_query_var( 'author' );
}else{
	$author_id        = get_current_user_id();
	$is_edit = true;
}

$bulletins = array();
$profile_id = get_user_meta( $author_id, 'user_profile_id', true );
if($profile_id){
	$query = 'SELECT * FROM ' . $wpdb->postmeta . ' WHERE post_id = ' . $profile_id . ' AND meta_key = "bulletin" ORDER BY meta_id DESC';
	$bulletins = $wpdb->get_results( $query );
}

if ( $is_edit or !empty( $bulletins )) {
	?>

	<div class="fre-profile-box">
        <div class="profile-freelance-bulletin">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h2 class="freelance-bulletin-title"><?php _e( 'Bulletin Board', ET_DOMAIN ) ?></h2>
                </div>

                <span id="fre-empty-bulletin">
				    <?php if ( $is_edit ) { ?>
                    <div class="col-sm-6 col-xs-12">
                        <div class="freelance-bulletin-add">
                            <a href="javascript:void(0)"
                               class="fre-normal-btn-o profile-show-edit-tab-btn"
                               data-ctn_edit="ctn-edit-bulletin" data-ctn_hide="fre-empty-bulletin">
								<?php _e( 'Add new', ET_DOMAIN ) ?>
                            </a>
                        </div>
                    </div>
				    <?php } ?>
                    <p class="col-xs-12 fre-empty-optional-profile" <?php echo (empty($bulletins) and $is_edit) ? '' : 'style="display : none"' ?>>
                        <?php _e('Add bulletin post to your profile.',ET_DOMAIN) ?>
                    </p>
                </span>
            </div>

            <ul class="freelance-bulletin-list">
				<?php if ( $is_edit ) { ?>
                    <!-- Box add new bulletin post-->
                    <li class="freelance-bulletin-new-wrap cnt-profile-hide"
                        id="ctn-edit-bulletin">
                        <div class="freelance-bulletin-new">
                            <form class="fre-bulletin-form freelance-bulletin-form" method="post">

                            	<div class="fre-input-field">
                                    <input type="text" name="bulletin[title]"
                                           placeholder="<?php _e( 'Title', ET_DOMAIN ) ?>">
                                </div>

                                <div class="fre-input-field no-margin-bottom">
                                    <textarea name="bulletin[content]" id="" cols="30" rows="10" placeholder="<?php _e('Starting writing post here',ET_DOMAIN) ?>"></textarea>
                                </div>

                                <div class="fre-form-btn">
                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                    <span class="fre-bulletin-close profile-show-edit-tab-btn"
                                          data-ctn_edit="fre-empty-bulletin"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                </div>
                            </form>
                        </div>
                    </li>
                    <!-- End Box add new bulletin post-->
				<?php } ?>

				<?php if ( ! empty( $bulletins ) ) {
					foreach ( $bulletins as $k => $bulletin ) {
						if ( ! empty( $bulletin->meta_value ) && is_serialized( $bulletin->meta_value ) ) {
							$e_value = unserialize( $bulletin->meta_value );
							if ( is_serialized( $e_value ) ) {
								$e_value = unserialize( $e_value );
							}
							if ( ! empty( $e_value ) ) {
								?>

                                <!-- Box show bulletin posts-->
                                <li class="cnt-profile-hide meta_history_item_<?php echo $bulletin->meta_id ?>"
                                    id="cnt-bulletin-default-<?php echo $bulletin->meta_id ?>"
                                    style="<?php echo $k + 1 == count( $bulletins ) ? 'border-bottom: 0;padding-bottom: 0;' : '' ?>">
                                    <div class="freelance-bulletin-wrap">
                                    	<h2><?php echo $e_value['title'] ?></h2>
                                        <?php echo apply_filters( 'the_content', $e_value['content'] ) ?>
                                    </div>
									<?php if ( $is_edit ) { ?>
                                        <div class="freelance-bulletin-action">
                                            <a href="javascript:void(0)" class="profile-show-edit-tab-btn"
                                               data-ctn_edit="ctn-edit-bulletin-<?php echo $bulletin->meta_id ?>">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
												<?php _e( 'Edit', ET_DOMAIN ) ?>
                                            </a>
                                            <a href="javascript:void(0)" class="remove_history_fre" data-id="<?php echo $bulletin->meta_id ?>">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i><?php _e('Remove',ET_DOMAIN) ?></a>
                                        </div>
									<?php } ?>
                                </li>
                                <!-- End Box show bulletin posts-->

								<?php if ( $is_edit ) { ?>
                                    <!-- Box edit bulletin post-->
                                    <li class="freelance-bulletin-new-wrap cnt-profile-hide meta_history_item_<?php echo $bulletin->meta_id ?>"
                                        id="ctn-edit-bulletin-<?php echo $bulletin->meta_id ?>">
                                        <div class="freelance-bulletin-new">
                                            <form class="fre-bulletin-form freelance-bulletin-form "
                                                  method="post">

                                                <div class="fre-input-field">
                                                    <input type="text" name="bulletin[title]"
                                                           placeholder="<?php _e( 'Title', ET_DOMAIN ) ?>"
                                                           value="<?php echo $e_value['title'] ?>">
                                                </div>

                                                <div class="fre-input-field no-margin-bottom">
                                                    <textarea name="bulletin[content]" id="" cols="30"  placeholder="<?php _e('Start writing post here',ET_DOMAIN) ?>"
                                                      rows="10"><?php echo ! empty( $e_value['content'] ) ? $e_value['content'] : '' ?></textarea>
                                                </div>

                                                <input type="hidden" value="<?php echo $bulletin->meta_id ?>"
                                                       name="bulletin[id]">

                                                <div class="fre-form-btn">
                                                    <input type="submit" class="fre-normal-btn btn-submit" name=""
                                                           value="<?php _e( 'Save', ET_DOMAIN ) ?>">
                                                    <span class="fre-bulletin-close profile-show-edit-tab-btn"
                                                          data-ctn_edit="cnt-bulletin-default-<?php echo $bulletin->meta_id ?>"><?php _e( 'Cancel', ET_DOMAIN ) ?></span>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                    <!-- Box edit bulletin post-->
								<?php } 
							}
						}
					}
				} ?>
            </ul>
        </div>
    </div>
<?php } ?>
