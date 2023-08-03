<body class="builderUI">

    <div class="side" id="fixedSidebar">

        <nav>
            <?php if (isset($templates) && !isset($templateID)) : ?>
                <button data-side="templates" id="templates_button">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve" width="64" height="64"><g class="nc-icon-wrapper" fill="#bdc3c7"><path data-color="color-2" fill="#bdc3c7" d="M63,20V7c0-1.105-0.895-2-2-2H3C1.895,5,1,5.895,1,7v13H63z"></path> <path fill="#bdc3c7" d="M19,22H1v35c0,1.105,0.895,2,2,2h16V22z"></path> <path fill="#bdc3c7" d="M21,22v37h40c1.105,0,2-0.895,2-2V22H21z"></path></g></svg>
                    <span><?php echo $this->lang->line('mainside_templates'); ?></span>
                </button>
            <?php endif;?>
            <button data-side="blocks">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve" width="64" height="64"><g class="nc-icon-wrapper" fill="#bdc3c7"><path fill="#bdc3c7" d="M53,25H11c-1.105,0-2-0.895-2-2V3c0-1.105,0.895-2,2-2h42c1.105,0,2,0.895,2,2v20C55,24.105,54.105,25,53,25 z"></path> <path fill="#bdc3c7" d="M53,63H11c-1.105,0-2-0.895-2-2V41c0-1.105,0.895-2,2-2h42c1.105,0,2,0.895,2,2v20 C55,62.105,54.105,63,53,63z"></path> <path data-color="color-2" fill="#bdc3c7" d="M62,33H2c-0.553,0-1-0.448-1-1s0.447-1,1-1h60c0.553,0,1,0.448,1,1S62.553,33,62,33z"></path></g></svg>
                <span><?php echo $this->lang->line('mainside_blocks'); ?></span>
            </button>
            <button data-side="components" id="components_button">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve" width="64" height="64"><g class="nc-icon-wrapper" fill="#bdc3c7"><path fill="#bdc3c7" d="M27,36H6c-0.552,0-1-0.448-1-1V2c0-0.552,0.448-1,1-1h21c0.552,0,1,0.448,1,1v33C28,35.552,27.552,36,27,36z "></path> <path data-color="color-2" fill="#bdc3c7" d="M27,63H6c-0.552,0-1-0.448-1-1V45c0-0.552,0.448-1,1-1h21c0.552,0,1,0.448,1,1v17 C28,62.552,27.552,63,27,63z"></path> <path data-color="color-2" fill="#bdc3c7" d="M58,20H37c-0.552,0-1-0.448-1-1V2c0-0.552,0.448-1,1-1h21c0.552,0,1,0.448,1,1v17 C59,19.552,58.552,20,58,20z"></path> <path fill="#bdc3c7" d="M58,63H37c-0.552,0-1-0.448-1-1V29c0-0.552,0.448-1,1-1h21c0.552,0,1,0.448,1,1v33 C59,62.552,58.552,63,58,63z"></path></g></svg>
                <span><?php echo $this->lang->line('mainside_components'); ?></span>
            </button>
            <?php if ( !isset($templateID) ):?>
            <button data-side="pages" id="pages_button">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" xml:space="preserve" width="64" height="64"><g class="nc-icon-wrapper" fill="#bdc3c7"><path fill="#bdc3c7" d="M43,17H5c-1.105,0-2,0.895-2,2v42c0,1.105,0.895,2,2,2h38c1.105,0,2-0.895,2-2V19C45,17.895,44.105,17,43,17 z"></path> <path data-color="color-2" fill="#bdc3c7" d="M59,47h-4V9c0-1.105-0.895-2-2-2H19V3c0-1.105,0.895-2,2-2h38c1.105,0,2,0.895,2,2v42 C61,46.105,60.105,47,59,47z"></path> <path data-color="color-2" fill="#bdc3c7" d="M51,55h-4V17c0-1.105-0.895-2-2-2H11v-4c0-1.105,0.895-2,2-2h38c1.105,0,2,0.895,2,2v42 C53,54.105,52.105,55,51,55z"></path></g></svg>
                <span><?php echo $this->lang->line('mainside_pages'); ?></span>
            </button>
            <?php endif;?>
            <?php if( $this->config->item('enable_popups') ):?>
            <button data-side="popups" id="popup_button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="64" height="64"><g class="nc-icon-wrapper" fill="#bdc3c7"><path data-color="color-2" d="M32.8,22.4l12,16c0.229,0.303,0.263,0.708,0.095,1.047C44.725,39.786,44.379,40,44,40H34v21 c0,0.552-0.448,1-1,1h-2c-0.552,0-1-0.448-1-1V40H20c-0.379,0-0.725-0.214-0.895-0.553C19.035,39.306,19,39.152,19,39 c0-0.212,0.067-0.424,0.2-0.6l12-16c0.188-0.252,0.485-0.4,0.8-0.4S32.611,22.148,32.8,22.4z"/> <path fill="#bdc3c7" d="M61,1H3C1.895,1,1,1.895,1,3v46c0,1.105,0.895,2,2,2h21v-2H4c-0.552,0-1-0.448-1-1V14c0-0.552,0.448-1,1-1 h56c0.552,0,1,0.448,1,1v34c0,0.552-0.448,1-1,1H40v2h21c1.105,0,2-0.895,2-2V3C63,1.895,62.105,1,61,1z M7,9C5.895,9,5,8.105,5,7 s0.895-2,2-2c1.105,0,2,0.895,2,2S8.105,9,7,9z M14,9c-1.105,0-2-0.895-2-2s0.895-2,2-2c1.105,0,2,0.895,2,2S15.105,9,14,9z M21,9 c-1.105,0-2-0.895-2-2s0.895-2,2-2c1.105,0,2,0.895,2,2S22.105,9,21,9z"/></g></svg>
                <span><?php echo $this->lang->line('mainside_popups'); ?></span>
            </button>
            <?php endif;?>
        </nav>

    </div><!-- /.side -->

    <header class="clearfix">

        <div class="btn-group" style="float: right;">
            <button class="btn btn-inverse dropdown-toggle btn-left-border" data-toggle="dropdown">
                
				<i class="fa fa-ellipsis-v"></i>
            </button>
            <span class="dropdown-arrow dropdown-arrow-inverse"></span>
            <ul class="dropdown-menu dropdown-menu-inverse dropdown-menu-right">
				<?php if ( !isset($templateID) ):?>
                <li>
                    <a href="#siteSettings" id="siteSettingsButton" class="siteSettingsModalButton" data-siteid="<?php echo $siteData['site']->sites_id; ?>">
                        <span class="fui-arrow-right"></span>
                        <?php echo $this->lang->line('actionbuttons_sitesettings'); ?>
                    </a>
                </li>
				<?php endif;?>
                <li>
                    <a href="#pageSettingsModal" id="pageSettingsButton" data-toggle="modal" data-siteid="<?php echo $siteData['site']->sites_id; ?>">
                        <span class="fui-arrow-right"></span>
                        <?php echo $this->lang->line('actionbuttons_pagesettings'); ?>
                    </a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo (isset($templateID))? site_url('templates') : site_url('sites') ;?>" id="backButton">
                        <span class="fui-arrow-left"></span>
                        <?php echo $this->lang->line('nav_goback_sites'); ?>
                    </a>
                </li>
            </ul>
        </div>

        <?php if( !isset($templateID) ):?>
            <?php if ($this->session->userdata('user_type') == 'Admin' || $package['export_site'] == 'yes') : ?>
                <a href="#exportModal" id="exportPage" data-toggle="modal" class="btn btn-inverse pull-right actionButtons slick btn-left-border" style="display: none">
                    <i class="fui-export"></i>
                    <span class="slide"><?php echo $this->lang->line('actionbuttons_export'); ?></span>
                </a>
            <?php endif; ?>

            <?php if ($this->session->userdata('user_type') == 'Admin' || $package['ftp_publish'] == 'yes') : ?>
				<div id="publish-tooltip-wrapper" class="tooltip-wrapper disabled slick" <?php if ($siteData['site']->ftp_ok == 0) : ?>data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('actionbuttons_ftp_not_ready');?>" <?php endif; ?> >
					<button href="#" id="publishPage" class="btn btn-inverse pull-right actionButtons slick btn-left-border" data-siteid="<?php echo $siteData['site']->sites_id;?>" <?php if ($siteData['site']->ftp_ok == 0) : ?>disabled<?php endif; ?> style="display: none" >
						<i class="fui-upload"></i>
						<span class="slide"><?php echo $this->lang->line('actionbuttons_publish'); ?></span>
						<i class="fui-alert text-danger" <?php if ($siteData['site']->ftp_ok == 1) : ?>style="display:none"<?php endif; ?>>
						</i>
					</button>
				</div>
            <?php endif; ?>
        <?php endif;?>

        <?php if( !isset($templateID) ):?>
        <div class="btn-group slick" style="float: right; display: none" id="button_revisionsDropdown">
            <button class="btn btn-inverse btn-left-border dropdown-toggle <?php if ( ! $revisions) : ?>disabled<?php endif; ?>" data-toggle="dropdown">
                <i class="fui-windows"></i>
                <span class="bLabel slide"><?php echo $this->lang->line('actionbuttons_versions'); ?></span>
            </button>
            <span class="dropdown-arrow dropdown-arrow-inverse"></span>
            <ul class="dropdown-menu dropdown-menu-inverse revisions-dropdown dropdown-menu-right" id="dropdown_revisions">
                <?php if ($revisions) : ?>
                    <?php $this->load->view('shared/revisions', array('revisions'=>$revisions, 'page'=>'index', 'siteID'=>$siteData['site']->sites_id)); ?>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif;?>

        <a href="#previewModal" data-toggle="modal" class="btn btn-inverse pull-right slick btn-left-border" style="display: none" id="buttonPreview">
            <i class="fui-window"></i>
            <span class="slide"><?php echo $this->lang->line('actionbuttons_preview'); ?></span>
        </a>

        <?php if ($this->session->userdata('user_type') == "Admin") : ?>
            <?php if ( isset($templateID) ):?>
            <select class="form-control select select-inverse select-block pull-right" id="selectCategory">
                <option value="0">In category</option>
                <?php foreach( $categories as $category ):?>
                <option <?php if ( isset($categoryID) && $categoryID == $category['templates_categories_id'] ) {echo "selected";} ?> value="<?php echo $category['templates_categories_id'];?>"><?php echo $category['category_name'];?></option>
                <?php endforeach;?>
            </select>
            <a href="#" id="saveTemplate" data-toggle="modal" class="btn btn-primary pull-right actionButtons" data-siteid="<?php echo $siteData['site']->sites_id; ?>"
                ><span class="fui-arrow-right"></span>
                <?php echo $this->lang->line('actionbuttons_savepagetemplate'); ?>
            </a>
            <?php else:?>
            <div class="btn-group" style="float: right;">
                <button class="btn btn-primary" id="savePage" data-loading="<?php echo $this->lang->line('actionbuttons_loading'); ?>" data-label="<?php echo $this->lang->line('actionbuttons_save_nothing'); ?>" data-label2="<?php echo $this->lang->line('actionbuttons_save'); ?>">
                    <span class="fui-check"></span>
                    <span class="bLabel"><?php echo $this->lang->line('actionbuttons_save_nothing'); ?></span>
                </button>
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <span class="dropdown-arrow dropdown-arrow-inverse"></span>
                <ul class="dropdown-menu dropdown-menu-inverse">
                    <li><a href="#" id="saveTemplate" data-toggle="modal" data-siteid="<?php echo $siteData['site']->sites_id; ?>"><span class="fui-arrow-right"></span> <?php echo $this->lang->line('actionbuttons_savepagetemplate'); ?></a></li>
                </ul>
            </div>
            <?php endif;?>
        <?php else : ?>
            <a href="#" id="savePage" data-toggle="modal" class="btn btn-primary btn-embossed pull-right actionButtons" data-loading="<?php echo $this->lang->line('actionbuttons_loading'); ?>" data-label="<?php echo $this->lang->line('actionbuttons_save_nothing'); ?>"  data-label2="<?php echo $this->lang->line('actionbuttons_save'); ?>">
                <span class="fui-check"></span>
                <span class="bLabel"><?php echo $this->lang->line('actionbuttons_save_nothing'); ?></span>
            </a>
        <?php endif; ?>

        <ul class="nav nav-pills nav-inverse pull-left responsiveToggle" id="responsiveToggle">
            <li>
                <a href="" data-responsive="mobile">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#bdc3c7"><path fill="#bdc3c7" d="M12,0H4C2.897,0,2,0.897,2,2v12c0,1.103,0.897,2,2,2h8c1.103,0,2-0.897,2-2V2C14,0.897,13.103,0,12,0z M8,14 c-0.552,0-1-0.448-1-1s0.448-1,1-1s1,0.448,1,1S8.552,14,8,14z M12,10H4V2h8V10z"></path></g></svg>
                </a>
            </li>
            <li>
                <a href="" data-responsive="tablet">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#bdc3c7"><path fill="#bdc3c7" d="M13,0H3C1.895,0,1,0.895,1,2v12c0,1.105,0.895,2,2,2h10c1.105,0,2-0.895,2-2V2C15,0.895,14.105,0,13,0z M8,14c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1s1,0.448,1,1C9,13.552,8.552,14,8,14z M13,10H3V2h10V10z"></path></g></svg>
                </a>
            </li>
            <li class="active">
                <a href="" data-responsive="desktop">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#bdc3c7"><path fill="#bdc3c7" d="M15,0H1C0.4,0,0,0.4,0,1v11c0,0.6,0.4,1,1,1h5v1H3v2h10v-2h-3v-1h5c0.6,0,1-0.4,1-1V1C16,0.4,15.6,0,15,0z M14,2v7H2V2H14z"></path></g></svg>
                </a>
            </li>
        </ul>

        <div class="gridViewToggle">
            <div class="">
               <span style="color:#393939; vertical-align: middle; font-size: 14px;"><?php echo $this->lang->line('show_grid'); ?> </span> <input type="checkbox" data-size="mini" data-on-text='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#00b591"><path fill="#00b591" d="M15,0H1C0.4,0,0,0.4,0,1v14c0,0.6,0.4,1,1,1h14c0.6,0,1-0.4,1-1V1C16,0.4,15.6,0,15,0z M14,7H9V2h5V7z M7,2 v5H2V2H7z M2,9h5v5H2V9z M9,14V9h5v5H9z"></path></g></svg>' data-off-text='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#ecf0f1"><path fill="#ecf0f1" d="M15,0H1C0.4,0,0,0.4,0,1v14c0,0.6,0.4,1,1,1h14c0.6,0,1-0.4,1-1V1C16,0.4,15.6,0,15,0z M14,7H9V2h5V7z M7,2 v5H2V2H7z M2,9h5v5H2V9z M9,14V9h5v5H9z"></path></g></svg>' data-toggle="switch" name="default-switch" id="gridViewSwitch" class="cbx">
            </div>
			
			<!--<div>
				<input type="checkbox" id="gridViewSwitch" class="cbx hidden bootstrap-switch"><span class="icons"><span class="icon-unchecked"></span><span class="icon-checked"></span></span>
				<label for="gridViewSwitch" class="lbl"></label>
			</div> -->
        </div>

    </header>

    <div id="builder">

        <div class="builderLayout">

            <div class="sideSecond" data-sidesecond="blocks">

                <div class="sideSecondInner">

                    <div class="heading">
                        <button class="closeSideSecond" data-js="closeSideSecond">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper" fill="#bdc3c7"><path data-color="color-2" fill="#bdc3c7" d="M22,0H10v2h11v20H10v2h12c0.553,0,1-0.447,1-1V1C23,0.447,22.553,0,22,0z"></path> <polygon fill="#bdc3c7" points="1,12 8,6 8,11 17,11 17,13 8,13 8,18 "></polygon></g></svg>
                        </button>

                        <h4><?php echo $this->lang->line('elements_heading'); ?></h4>
                    </div>

                    <nav data-no-fav-blocks="<?php echo $this->lang->line('no_fav_blocks_yet'); ?>" class="nav_blocks"></nav>

                </div><!-- /.sideSecondInner -->

            </div><!-- /.sideSecond -->

            <?php if (isset($templates)) : ?>
                <div class="sideSecond" data-sidesecond="templates">

                    <div class="sideSecondInner">

                        <div class="heading">
                            <button class="closeSideSecond" data-js="closeSideSecond">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper" fill="#bdc3c7"><path data-color="color-2" fill="#bdc3c7" d="M22,0H10v2h11v20H10v2h12c0.553,0,1-0.447,1-1V1C23,0.447,22.553,0,22,0z"></path> <polygon fill="#bdc3c7" points="1,12 8,6 8,11 17,11 17,13 8,13 8,18 "></polygon></g></svg>
                            </button>

                            <h4><?php echo $this->lang->line('templates_heading'); ?></h4>
                        </div>

                        <nav>
                            <?php foreach( $templates as $templateCat => $templ ):?>
                            <button><span><?php echo $templateCat;?></span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#bdc3c7"><polygon fill="#bdc3c7" points="4.9,15.7 3.4,14.3 9.7,8 3.4,1.7 4.9,0.3 12.6,8 "></polygon></g></svg></button><ul class="">
                                <?php echo $templ; ?>
                            </ul>
                            <?php endforeach;?>
                        </nav>

                    </div><!-- /.sideSecondInner -->

                </div><!-- /.sideSecond -->
            <?php endif;?>

            <div class="sideSecond" data-sidesecond="components">

                <div class="sideSecondInner">

                    <div class="heading">
                        <button class="closeSideSecond" data-js="closeSideSecond">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper" fill="#bdc3c7"><path data-color="color-2" fill="#bdc3c7" d="M22,0H10v2h11v20H10v2h12c0.553,0,1-0.447,1-1V1C23,0.447,22.553,0,22,0z"></path> <polygon fill="#bdc3c7" points="1,12 8,6 8,11 17,11 17,13 8,13 8,18 "></polygon></g></svg>
                        </button>

                        <h4><?php echo $this->lang->line('comopnents_heading'); ?></h4>
                    </div>

                    <nav></nav>

                </div><!-- /.sideSecondInner -->

            </div><!-- /.sideSecond -->

            <div class="sideSecond" data-sidesecond="pages">

                <div class="sideSecondInner">

                    <div class="heading">
                        <button class="closeSideSecond" data-js="closeSideSecond">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper" fill="#bdc3c7"><path data-color="color-2" fill="#bdc3c7" d="M22,0H10v2h11v20H10v2h12c0.553,0,1-0.447,1-1V1C23,0.447,22.553,0,22,0z"></path> <polygon fill="#bdc3c7" points="1,12 8,6 8,11 17,11 17,13 8,13 8,18 "></polygon></g></svg>
                        </button>

                        <h4><?php echo $this->lang->line('pages'); ?></h4>
                    </div>

                    <ul id="pages"></ul>

                    <hr>

					<p id="pages_counter_text">
					<?php echo $this->lang->line('pages_counter_text'); ?>
					</p>
					<p id="pages_counter">
					</p>
					
                    <div class="buttonWrapper">
                        <button class="btn btn-primary btn-lg btn-embossed" id="addPage">
                            <span class="fui-plus"></span>
                            <?php echo $this->lang->line('button_add_page'); ?>
                        </button>
                    </div>

                </div><!-- /.sideSecondInner -->

            </div><!-- /.sideSecond -->

            <?php if( $this->config->item('enable_popups') ):?>
            <div class="sideSecond" data-sidesecond="popups">

                <div class="sideSecondInner">
                    <div class="heading">
                        <button class="closeSideSecond" data-js="closeSideSecond">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="24" height="24"><g class="nc-icon-wrapper" fill="#bdc3c7"><path data-color="color-2" fill="#bdc3c7" d="M22,0H10v2h11v20H10v2h12c0.553,0,1-0.447,1-1V1C23,0.447,22.553,0,22,0z"></path> <polygon fill="#bdc3c7" points="1,12 8,6 8,11 17,11 17,13 8,13 8,18 "></polygon></g></svg>
                        </button>

                        <h4><?php echo $this->lang->line('popups_heading'); ?></h4>
                    </div>

                    <nav></nav>

                </div>

            </div><!-- /.sideSecond -->
            <?php endif;?>

            <div class="canvasWrapper">

                <?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close fui-cross" type="button"></button>
                        <h4><?php echo $this->lang->line('create_all_good'); ?></h4>
                        <p>
                            <?php echo $this->session->flashdata('success'); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="screen" id="screen">

                    <div class="toolbar">

                        <div class="buttons clearfix">
                            <span class="left red"></span>
                            <span class="left yellow"></span>
                            <span class="left green"></span>
                        </div>

                        <div class="title">
                            <span id="pageTitle">index</span>
                        </div>

                    </div>

                    <div id="frameWrapper" class="frameWrapper empty">
                        <div id="pageList">

                        </div>
                        <div class="start" id="start" <?php if (isset($siteData['pages']) && count($siteData['pages']) > 0) : ?>style="display:none"<?php endif; ?>>
                            <span><?php echo $this->lang->line('canvas_empty'); ?></span>
                        </div>
                        <div id="popups" class="popupsWrapper">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="buttonClosePopups"><span aria-hidden="true">&times;</span></button>
                            <div class="popupTypeSelectWrapper">
                                <select class="form-control select select-sm pull-right select-info mbl" data-toggle="select" id="selectPopupType">
                                    <option value="Entry"><?php echo $this->lang->line('popup_type_dropdown_entry');?></option>
                                    <option value="Exit"><?php echo $this->lang->line('popup_type_dropdown_exit');?></option>
                                    <option value="Regular"><?php echo $this->lang->line('popup_type_dropdown_regular');?></option>
                                </select>
                            </div>
                            <div class="wrapper">
                                <div id="entryPopup" class="popupDz entry visible">
                                    <ul></ul>
                                    <div class="dzPlaceholder" id="entryDzPlaceholder"><span><?php echo $this->lang->line('popup_dz_exit_popups');?></span></div>
                                </div>
                                <div id="exitPopup" class="popupDz exit">
                                    <ul></ul>
                                    <div class="dzPlaceholder" id="exitDzPlaceholder"><span><?php echo $this->lang->line('popup_dz_entry_popups');?></span></div>
                                </div>
                                <div id="regularPopup" class="popupDz regular">
                                    <ul></ul>
                                    <div class="dzPlaceholder" id="regularDzPlaceholder"><span><?php echo $this->lang->line('popup_dz_regular_popups');?></span></div>
                                </div>
                            </div>
                        </div><!-- /#popups -->
                    </div>

                </div><!-- /.screen -->

            </div>

            <div id="styleEditor" class="styleEditor">

                <div class="styleEditorInner">
					<button class="move"><span class="fui-arrow-left"></span></button> 
                    <button class="close"><span class="fui-cross-circle"></span></button>

                    <h3><span class="fui-new"></span> <?php echo $this->lang->line('detail_editor_heading'); ?></h3>

                    <ul class="breadcrumb">
                        <li id="editingElement"></li>
                    </ul>

                    <ul class="nav nav-tabs" id="detailTabs">
                        <li class="active"><a href="#tab1"><span class="fui-new"></span> <?php echo $this->lang->line('detail_editor_tab_style'); ?></a></li>
                        <li style="display: none;"><a href="#link_Tab" id="link_Link"><span class="fui-clip"></span> <?php echo $this->lang->line('detail_editor_tab_link'); ?></a></li>
                        <li style="display: none;"><a href="#image_Tab" id="img_Link"><span class="fui-image"></span> <?php echo $this->lang->line('detail_editor_tab_image'); ?></a></li>
                        <li style="display: none;"><a href="#icon_Tab" id="icon_Link"><span class="fa fa-flag"></span> <?php echo $this->lang->line('detail_editor_tab_icons'); ?></a></li>
                        <li style="display: none;"><a href="#video_Tab" id="video_Link"><span class="fa fa-youtube-play"></span> <?php echo $this->lang->line('detail_editor_tab_video'); ?></a></li>
                        <li style="display: none;"><a href="#menuitems_Tab" id="menuitems_Link"><span class="fa fa-list-ul"></span> <?php echo $this->lang->line('detail_editor_tab_brand'); ?></a></li>
                        <li style="display: none;"><a href="#form_Tab" id="form_Link"><span class="fa fa-file-text-o"></span> <?php echo $this->lang->line('detail_editor_tab_form'); ?></a></li>
                        <li style="display: none;"><a href="#slideshow_Tab" id="slideshow_Link"><span class="fa fa-cog"></span> <?php echo $this->lang->line('detail_editor_tab_slideshow'); ?></a></li>
                        <?php if ( $this->config->item('google_api') != '' ):?>
                        <li style="display: none;"><a href="#map_Tab" id="map_Link"><span class="fa fa-map-o"></span> <?php echo $this->lang->line('detail_editor_tab_map'); ?></a></li>
                        <?php endif;?>
                        <li style="display: none"><a href="#countdown_Tab" id="countdown_Link"><span class="fa fa-clock-o"></span> <?php echo $this->lang->line('detail_editor_tab_countdown'); ?></a></li>
                        <li style="display: none"><a href="#input_Tab" id="input_Link">
                                <span class="fa fa-file-text-o"></span> <?php echo $this->lang->line('detail_editor_tab_input'); ?></a>
                        </li>
                        <li style="display: none"><a href="#textarea_Tab" id="textarea_Link">
                                <span class="fa fa-file-text-o"></span> <?php echo $this->lang->line('detail_editor_tab_textarea'); ?></a>
                        </li>
                    </ul><!-- /tabs -->

                    <div class="tab-content">

                        <div class="tab-pane active" id="tab1">

                            <form class="" role="form" id="stylingForm">

                                <div id="styleElements">

                                    <div class="form-group clearfix" style="display: none;" id="styleElTemplate">
                                        <label for="" class="control-label"></label>
                                        <input type="text" class="form-control input-sm" id="" placeholder="">
                                    </div>

                                </div>

                            </form>

                        </div>

                        <!-- /tabs -->
                        <div class="tab-pane textarea_Tab" id="textarea_Tab">

                            <div class="div_textarea_id" style="margin-top: 5px">
                                <label for="textarea_id"><?php echo $this->lang->line('input_id_lable');?> :</label>
                                <input type="text" class="form-control" id="textarea_id" name="textarea_id">
                            </div>

                            <div class="div_textarea_name" style="margin-top: 5px">
                                <label for="textarea_name"><?php echo $this->lang->line('input_name_lable');?> :</label>
                                <input type="text" class="form-control" id="textarea_name" name="textarea_name">
                            </div>

                            <div class="div_textarea_placeholder" style="margin-top: 5px">
                                <label for="textarea_placeholder"><?php echo $this->lang->line('input_placeholder_lable');?> :</label>
                                <input type="text" class="form-control" id="textarea_placeholder" name="textarea_placeholder">
                            </div>
                            <p class="text-center"></p>
                        </div>
                        <!-- /tabs -->
                        <div class="tab-pane input_Tab" id="input_Tab">

                            <div class="div_input_type" style="margin-top: 5px">
                                <label for=""><?php echo $this->lang->line('input_type_lable');?> :</label>
                                <select class="form-control" id="input_type" data-toggle="select">
                                    <option value="email"><?php echo $this->lang->line('input_type_email');?></option>
                                    <option value="number"><?php echo $this->lang->line('input_type_number');?></option>
                                    <option value="password"><?php echo $this->lang->line('input_type_password');?></option>
                                    <option value="tel"><?php echo $this->lang->line('input_type_tel');?></option>
                                    <option value="text"><?php echo $this->lang->line('input_type_text');?></option>
                                    <option value="url"><?php echo $this->lang->line('input_type_url');?></option>
                                </select>
                            </div>

                            <div class="div_input_id" style="margin-top: 5px">
                                <label for="input_id"><?php echo $this->lang->line('input_id_lable');?> :</label>
                                <input type="text" class="form-control" id="input_id" name="input_id">
                            </div>

                            <div class="div_input_name" style="margin-top: 5px">
                                <label for="input_name"><?php echo $this->lang->line('input_name_lable');?> :</label>
                                <input type="text" class="form-control" id="input_name" name="input_name">
                            </div>

                            <div class="div_input_placeholder" style="margin-top: 5px">
                                <label for="input_placeholder"><?php echo $this->lang->line('input_placeholder_lable');?> :</label>
                                <input type="text" class="form-control" id="input_placeholder" name="input_placeholder">
                            </div>
                            <p class="text-center"></p>
                        </div>

                        <!-- /tabs -->
                        <div class="tab-pane link_Tab" id="link_Tab">

                            <div class="form-group fullWidth">
                                <input type="text" class="form-control" id="linkText" name="linkText" placeholder="<?php echo $this->lang->line('create_tab_placeholder_linktext'); ?>" value="">
                            </div>

                            <label class="checkbox" for="checkboxOpeninNewTab">
                                <input type="checkbox" value="" id="checkboxOpeninNewTab" data-toggle="checkbox">
                                <?php echo $this->lang->line('link_tab_link_newtab');?>
                            </label>

                            <div class="form-group">
                                <select id="pageLinksDropdown" class="form-control select select-primary btn-block mbl" data-placeholder="<?php echo $this->lang->line('create_tab_panel_page'); ?>">
                                </select>
                            </div>

                            <p class="text-center or">
                                <span><?php echo $this->lang->line('create_tab_panel_or');?></span>
                            </p>

                            <div class="form-group">
                                <select id="internalLinksDropdown" class="form-control select select-primary btn-block mbl" data-placeholder="<?php echo $this->lang->line('create_tab_panel_block'); ?>">
                                </select>
                            </div>

                            <p class="text-center or">
                                <span><?php echo $this->lang->line('create_tab_panel_down');?></span>
                            </p>

                            <input type="text" class="form-control" id="internalLinksCustom" placeholder="<?php echo $this->lang->line('create_tab_panel_placeholder_link');?>" value="">

                            <label class="checkbox" for="checkboxLinkActive">
                                <input type="checkbox" value="" id="checkboxLinkActive" data-toggle="checkbox">
                                <?php echo $this->lang->line('link_tab_link_active'); ?>
                            </label>

                        </div>

                        <!-- /tabs -->
                        <div class="tab-pane imageFileTab" id="image_Tab">

                            <a href="#imageModal" data-toggle="modal" type="button" class="btn btn-default btn-embossed btn-block margin-bottom-20"><span class="fui-image"></span> <?php echo $this->lang->line('open_image_library'); ?></a>

                            <input type="text" class="form-control margin-bottom-20" id="inputCombinedGallery" placeholder="MyGallery" value="">

                            <div class="showHide">

                                <label for="toggle_xyz"><?php echo $this->lang->line('label_toggle_advanced');?></label>
                                <input type="checkbox" id="toggle_xyz">

                                <div class="showHideContent">
                                    <label><?php echo $this->lang->line('label_image_title'); ?>: </label>
                                    <input type="text" class="form-control margin-bottom-20" id="inputImageTitle" placeholder="" value="">

                                    <label><?php echo $this->lang->line('label_image_alt'); ?>: </label>
                                    <input type="text" class="form-control margin-bottom-20" id="inputImageAlt" placeholder="" value="">
                                </div>

                            </div><!-- /.showHide -->

                        </div><!-- /.tab-pane -->

                        <!-- /tabs -->
                        <div class="tab-pane iconTab" id="icon_Tab">

                            <label><?php echo $this->lang->line('choose_an_icon'); ?>: </label>

                            <select id="icons" data-placeholder="" class>
                                <option value="fa-adjust">&#xf042; adjust</option>
                                <option value="fa-adn">&#xf170; adn</option>
                                <option value="fa-align-center">&#xf037; align-center</option>
                                <option value="fa-align-justify">&#xf039; align-justify</option>
                                <option value="fa-align-left">&#xf036; align-left</option>
                                <option value="fa-align-right">&#xf038; align-right</option>
                                <option value="fa-ambulance">&#xf0f9; ambulance</option>
                                <option value="fa-anchor">&#xf13d; anchor</option>
                                <option value="fa-android">&#xf17b; android</option>
                                <option value="fa-angellist">&#xf209; angellist</option>
                                <option value="fa-angle-double-down">&#xf103; angle-double-down</option>
                                <option value="fa-angle-double-left">&#xf100; angle-double-left</option>
                                <option value="fa-angle-double-right">&#xf101; angle-double-right</option>
                                <option value="fa-angle-double-up">&#xf102; angle-double-up</option>
                                <option value="fa-angle-down">&#xf107; angle-down</option>
                                <option value="fa-angle-left">&#xf104; angle-left</option>
                                <option value="fa-angle-right">&#xf105; angle-right</option>
                                <option value="fa-angle-up">&#xf106; angle-up</option>
                                <option value="fa-apple">&#xf179; apple</option>
                                <option value="fa-archive">&#xf187; archive</option>
                                <option value="fa-area-chart">&#xf1fe; area-chart</option>
                                <option value="fa-arrow-circle-down">&#xf0ab; arrow-circle-down</option>
                                <option value="fa-arrow-circle-left">&#xf0a8; arrow-circle-left</option>
                                <option value="fa-arrow-circle-o-down">&#xf01a; arrow-circle-o-down</option>
                                <option value="fa-arrow-circle-o-left">&#xf190; arrow-circle-o-left</option>
                                <option value="fa-arrow-circle-o-right">&#xf18e; arrow-circle-o-right</option>
                                <option value="fa-arrow-circle-o-up">&#xf01b; arrow-circle-o-up</option>
                                <option value="fa-arrow-circle-right">&#xf0a9; arrow-circle-right</option>
                                <option value="fa-arrow-circle-up">&#xf0aa; arrow-circle-up</option>
                                <option value="fa-arrow-down">&#xf063; arrow-down</option>
                                <option value="fa-arrow-left">&#xf060; arrow-left</option>
                                <option value="fa-arrow-right">&#xf061; arrow-right</option>
                                <option value="fa-arrow-up">&#xf062; arrow-up</option>
                                <option value="fa-arrows">&#xf047; arrows</option>
                                <option value="fa-arrows-alt">&#xf0b2; arrows-alt</option>
                                <option value="fa-arrows-h">&#xf07e; arrows-h</option>
                                <option value="fa-arrows-v">&#xf07d; arrows-v</option>
                                <option value="fa-asterisk">&#xf069; asterisk</option>
                                <option value="fa-at">&#xf1fa; at</option>
                                <option value="fa-automobile">&#xf1b9; automobile</option>
                                <option value="fa-backward">&#xf04a; backward</option>
                                <option value="fa-ban">&#xf05e; ban</option>
                                <option value="fa-bank">&#xf19c; bank</option>
                                <option value="fa-bar-chart">&#xf080; bar-chart</option>
                                <option value="fa-bar-chart-o">&#xf080; bar-chart-o</option>
                                <option value="fa-barcode">&#xf02a; barcode</option>
                                <option value="fa-bars">&#xf0c9; bars</option>
                                <option value="fa-beer">&#xf0fc; beer</option>
                                <option value="fa-behance">&#xf1b4; behance</option>
                                <option value="fa-behance-square">&#xf1b5; behance-square</option>
                                <option value="fa-bell">&#xf0f3; bell</option>
                                <option value="fa-bell-o">&#xf0a2; bell-o</option>
                                <option value="fa-bell-slash">&#xf1f6; bell-slash</option>
                                <option value="fa-bell-slash-o">&#xf1f7; bell-slash-o</option>
                                <option value="fa-bicycle">&#xf206; bicycle</option>
                                <option value="fa-binoculars">&#xf1e5; binoculars</option>
                                <option value="fa-birthday-cake">&#xf1fd; birthday-cake</option>
                                <option value="fa-bitbucket">&#xf171; bitbucket</option>
                                <option value="fa-bitbucket-square">&#xf172; bitbucket-square</option>
                                <option value="fa-bitcoin">&#xf15a; bitcoin</option>
                                <option value="fa-bold">&#xf032; bold</option>
                                <option value="fa-bolt">&#xf0e7; bolt</option>
                                <option value="fa-bomb">&#xf1e2; bomb</option>
                                <option value="fa-book">&#xf02d; book</option>
                                <option value="fa-bookmark">&#xf02e; bookmark</option>
                                <option value="fa-bookmark-o">&#xf097; bookmark-o</option>
                                <option value="fa-briefcase">&#xf0b1; briefcase</option>
                                <option value="fa-btc">&#xf15a; btc</option>
                                <option value="fa-bug">&#xf188; bug</option>
                                <option value="fa-building">&#xf1ad; building</option>
                                <option value="fa-building-o">&#xf0f7; building-o</option>
                                <option value="fa-bullhorn">&#xf0a1; bullhorn</option>
                                <option value="fa-bullseye">&#xf140; bullseye</option>
                                <option value="fa-bus">&#xf207; bus</option>
                                <option value="fa-cab">&#xf1ba; cab</option>
                                <option value="fa-calculator">&#xf1ec; calculator</option>
                                <option value="fa-calendar">&#xf073; calendar</option>
                                <option value="fa-calendar-o">&#xf133; calendar-o</option>
                                <option value="fa-camera">&#xf030; camera</option>
                                <option value="fa-camera-retro">&#xf083; camera-retro</option>
                                <option value="fa-car">&#xf1b9; car</option>
                                <option value="fa-caret-down">&#xf0d7; caret-down</option>
                                <option value="fa-caret-left">&#xf0d9; caret-left</option>
                                <option value="fa-caret-right">&#xf0da; caret-right</option>
                                <option value="fa-caret-square-o-down">&#xf150; caret-square-o-down</option>
                                <option value="fa-caret-square-o-left">&#xf191; caret-square-o-left</option>
                                <option value="fa-caret-square-o-right">&#xf152; caret-square-o-right</option>
                                <option value="fa-caret-square-o-up">&#xf151; caret-square-o-up</option>
                                <option value="fa-caret-up">&#xf0d8; caret-up</option>
                                <option value="fa-cc">&#xf20a; cc</option>
                                <option value="fa-cc-amex">&#xf1f3; cc-amex</option>
                                <option value="fa-cc-discover">&#xf1f2; cc-discover</option>
                                <option value="fa-cc-mastercard">&#xf1f1; cc-mastercard</option>
                                <option value="fa-cc-paypal">&#xf1f4; cc-paypal</option>
                                <option value="fa-cc-stripe">&#xf1f5; cc-stripe</option>
                                <option value="fa-cc-visa">&#xf1f0; cc-visa</option>
                                <option value="fa-certificate">&#xf0a3; certificate</option>
                                <option value="fa-chain">&#xf0c1; chain</option>
                                <option value="fa-chain-broken">&#xf127; chain-broken</option>
                                <option value="fa-check">&#xf00c; check</option>
                                <option value="fa-check-circle">&#xf058; check-circle</option>
                                <option value="fa-check-circle-o">&#xf05d; check-circle-o</option>
                                <option value="fa-check-square">&#xf14a; check-square</option>
                                <option value="fa-check-square-o">&#xf046; check-square-o</option>
                                <option value="fa-chevron-circle-down">&#xf13a; chevron-circle-down</option>
                                <option value="fa-chevron-circle-left">&#xf137; chevron-circle-left</option>
                                <option value="fa-chevron-circle-right">&#xf138; chevron-circle-right</option>
                                <option value="fa-chevron-circle-up">&#xf139; chevron-circle-up</option>
                                <option value="fa-chevron-down">&#xf078; chevron-down</option>
                                <option value="fa-chevron-left">&#xf053; chevron-left</option>
                                <option value="fa-chevron-right">&#xf054; chevron-right</option>
                                <option value="fa-chevron-up">&#xf077; chevron-up</option>
                                <option value="fa-child">&#xf1ae; child</option>
                                <option value="fa-circle">&#xf111; circle</option>
                                <option value="fa-circle-o">&#xf10c; circle-o</option>
                                <option value="fa-circle-o-notch">&#xf1ce; circle-o-notch</option>
                                <option value="fa-circle-thin">&#xf1db; circle-thin</option>
                                <option value="fa-clipboard">&#xf0ea; clipboard</option>
                                <option value="fa-clock-o">&#xf017; clock-o</option>
                                <option value="fa-close">&#xf00d; close</option>
                                <option value="fa-cloud">&#xf0c2; cloud</option>
                                <option value="fa-cloud-download">&#xf0ed; cloud-download</option>
                                <option value="fa-cloud-upload">&#xf0ee; cloud-upload</option>
                                <option value="fa-cny">&#xf157; cny</option>
                                <option value="fa-code">&#xf121; code</option>
                                <option value="fa-code-fork">&#xf126; code-fork</option>
                                <option value="fa-codepen">&#xf1cb; codepen</option>
                                <option value="fa-coffee">&#xf0f4; coffee</option>
                                <option value="fa-cog">&#xf013; cog</option>
                                <option value="fa-cogs">&#xf085; cogs</option>
                                <option value="fa-columns">&#xf0db; columns</option>
                                <option value="fa-comment">&#xf075; comment</option>
                                <option value="fa-comment-o">&#xf0e5; comment-o</option>
                                <option value="fa-comments">&#xf086; comments</option>
                                <option value="fa-comments-o">&#xf0e6; comments-o</option>
                                <option value="fa-compass">&#xf14e; compass</option>
                                <option value="fa-compress">&#xf066; compress</option>
                                <option value="fa-copy">&#xf0c5; copy</option>
                                <option value="fa-copyright">&#xf1f9; copyright</option>
                                <option value="fa-credit-card">&#xf09d; credit-card</option>
                                <option value="fa-crop">&#xf125; crop</option>
                                <option value="fa-crosshairs">&#xf05b; crosshairs</option>
                                <option value="fa-css">css3 &#xf13c;</option>
                                <option value="fa-cube">&#xf1b2; cube</option>
                                <option value="fa-cubes">&#xf1b3; cubes</option>
                                <option value="fa-cut">&#xf0c4; cut</option>
                                <option value="fa-cutlery">&#xf0f5; cutlery</option>
                                <option value="fa-dashboard">&#xf0e4; dashboard</option>
                                <option value="fa-database">&#xf1c0; database</option>
                                <option value="fa-dedent">&#xf03b; dedent</option>
                                <option value="fa-delicious">&#xf1a5; delicious</option>
                                <option value="fa-desktop">&#xf108; desktop</option>
                                <option value="fa-deviantart">&#xf1bd; deviantart</option>
                                <option value="fa-digg">&#xf1a6; digg</option>
                                <option value="fa-dollar">&#xf155; dollar</option>
                                <option value="fa-dot-circle-o">&#xf192; dot-circle-o</option>
                                <option value="fa-download">&#xf019; download</option>
                                <option value="fa-dribbble">&#xf17d; dribbble</option>
                                <option value="fa-dropbox">&#xf16b; dropbox</option>
                                <option value="fa-drupal">&#xf1a9; drupal</option>
                                <option value="fa-edit">&#xf044; edit</option>
                                <option value="fa-eject">&#xf052; eject</option>
                                <option value="fa-ellipsis-h">&#xf141; ellipsis-h</option>
                                <option value="fa-ellipsis-v">&#xf142; ellipsis-v</option>
                                <option value="fa-empire">&#xf1d1; empire</option>
                                <option value="fa-envelope">&#xf0e0; envelope</option>
                                <option value="fa-envelope-o">&#xf003; envelope-o</option>
                                <option value="fa-envelope-square">&#xf199; envelope-square</option>
                                <option value="fa-eraser">&#xf12d; eraser</option>
                                <option value="fa-eur">&#xf153; eur</option>
                                <option value="fa-euro">&#xf153; euro</option>
                                <option value="fa-exchange">&#xf0ec; exchange</option>
                                <option value="fa-exclamation">&#xf12a; exclamation</option>
                                <option value="fa-exclamation-circle">&#xf06a; exclamation-circle</option>
                                <option value="fa-exclamation-triangle">&#xf071; exclamation-triangle</option>
                                <option value="fa-expand">&#xf065; expand</option>
                                <option value="fa-external-link">&#xf08e; external-link</option>
                                <option value="fa-external-link-square">&#xf14c; external-link-square</option>
                                <option value="fa-eye">&#xf06e; eye</option>
                                <option value="fa-eye-slash">&#xf070; eye-slash</option>
                                <option value="fa-eyedropper">&#xf1fb; eyedropper</option>
                                <option value="fa-facebook">&#xf09a; facebook</option>
                                <option value="fa-facebook-square">&#xf082; facebook-square</option>
                                <option value="fa-fast-backward">&#xf049; fast-backward</option>
                                <option value="fa-fast-forward">&#xf050; fast-forward</option>
                                <option value="fa-fax">&#xf1ac; fax</option>
                                <option value="fa-female">&#xf182; female</option>
                                <option value="fa-fighter-jet">&#xf0fb; fighter-jet</option>
                                <option value="fa-file">&#xf15b; file</option>
                                <option value="fa-file-archive-o">&#xf1c6; file-archive-o</option>
                                <option value="fa-file-audio-o">&#xf1c7; file-audio-o</option>
                                <option value="fa-file-code-o">&#xf1c9; file-code-o</option>
                                <option value="fa-file-excel-o">&#xf1c3; file-excel-o</option>
                                <option value="fa-file-image-o">&#xf1c5; file-image-o</option>
                                <option value="fa-file-movie-o">&#xf1c8; file-movie-o</option>
                                <option value="fa-file-o">&#xf016; file-o</option>
                                <option value="fa-file-pdf-o">&#xf1c1; file-pdf-o</option>
                                <option value="fa-file-photo-o">&#xf1c5; file-photo-o</option>
                                <option value="fa-file-picture-o">&#xf1c5; file-picture-o</option>
                                <option value="fa-file-powerpoint-o">&#xf1c4; file-powerpoint-o</option>
                                <option value="fa-file-sound-o">&#xf1c7; file-sound-o</option>
                                <option value="fa-file-text">&#xf15c; file-text</option>
                                <option value="fa-file-text-o">&#xf0f6; file-text-o</option>
                                <option value="fa-file-video-o">&#xf1c8; file-video-o</option>
                                <option value="fa-file-word-o">&#xf1c2; file-word-o</option>
                                <option value="fa-file-zip-o">&#xf1c6; file-zip-o</option>
                                <option value="fa-files-o">&#xf0c5; files-o</option>
                                <option value="fa-film">&#xf008; film</option>
                                <option value="fa-filter">&#xf0b0; filter</option>
                                <option value="fa-fire">&#xf06d; fire</option>
                                <option value="fa-fire-extinguisher">&#xf134; fire-extinguisher</option>
                                <option value="fa-flag">&#xf024; flag</option>
                                <option value="fa-flag-checkered">&#xf11e; flag-checkered</option>
                                <option value="fa-flag-o">&#xf11d; flag-o</option>
                                <option value="fa-flash">&#xf0e7; flash</option>
                                <option value="fa-flask">&#xf0c3; flask</option>
                                <option value="fa-flickr">&#xf16e; flickr</option>
                                <option value="fa-floppy-o">&#xf0c7; floppy-o</option>
                                <option value="fa-folder">&#xf07b; folder</option>
                                <option value="fa-folder-o">&#xf114; folder-o</option>
                                <option value="fa-folder-open">&#xf07c; folder-open</option>
                                <option value="fa-folder-open-o">&#xf115; folder-open-o</option>
                                <option value="fa-font">&#xf031; font</option>
                                <option value="fa-forward">&#xf04e; forward</option>
                                <option value="fa-foursquare">&#xf180; foursquare</option>
                                <option value="fa-frown-o">&#xf119; frown-o</option>
                                <option value="fa-futbol-o">&#xf1e3; futbol-o</option>
                                <option value="fa-gamepad">&#xf11b; gamepad</option>
                                <option value="fa-gavel">&#xf0e3; gavel</option>
                                <option value="fa-gbp">&#xf154; gbp</option>
                                <option value="fa-ge">&#xf1d1; ge</option>
                                <option value="fa-gear">&#xf013; gear</option>
                                <option value="fa-gears">&#xf085; gears</option>
                                <option value="fa-gift">&#xf06b; gift</option>
                                <option value="fa-git">&#xf1d3; git</option>
                                <option value="fa-git-square">&#xf1d2; git-square</option>
                                <option value="fa-github">&#xf09b; github</option>
                                <option value="fa-github-alt">&#xf113; github-alt</option>
                                <option value="fa-github-square">&#xf092; github-square</option>
                                <option value="fa-gittip">&#xf184; gittip</option>
                                <option value="fa-glass">&#xf000; glass</option>
                                <option value="fa-globe">&#xf0ac; globe</option>
                                <option value="fa-google">&#xf1a0; google</option>
                                <option value="fa-google-plus">&#xf0d5; google-plus</option>
                                <option value="fa-google-plus-square">&#xf0d4; google-plus-square</option>
                                <option value="fa-google-wallet">&#xf1ee; google-wallet</option>
                                <option value="fa-graduation-cap">&#xf19d; graduation-cap</option>
                                <option value="fa-group">&#xf0c0; group</option>
                                <option value="fa-h-square">&#xf0fd; h-square</option>
                                <option value="fa-hacker-news">&#xf1d4; hacker-news</option>
                                <option value="fa-hand-o-down">&#xf0a7; hand-o-down</option>
                                <option value="fa-hand-o-left">&#xf0a5; hand-o-left</option>
                                <option value="fa-hand-o-right">&#xf0a4; hand-o-right</option>
                                <option value="fa-hand-o-up">&#xf0a6; hand-o-up</option>
                                <option value="fa-hdd-o">&#xf0a0; hdd-o</option>
                                <option value="fa-header">&#xf1dc; header</option>
                                <option value="fa-headphones">&#xf025; headphones</option>
                                <option value="fa-heart">&#xf004; heart</option>
                                <option value="fa-heart-o">&#xf08a; heart-o</option>
                                <option value="fa-history">&#xf1da; history</option>
                                <option value="fa-home">&#xf015; home</option>
                                <option value="fa-hospital-o">&#xf0f8; hospital-o</option>
                                <option value="fa-html">html5 &#xf13b;</option>
                                <option value="fa-ils">&#xf20b; ils</option>
                                <option value="fa-image">&#xf03e; image</option>
                                <option value="fa-inbox">&#xf01c; inbox</option>
                                <option value="fa-indent">&#xf03c; indent</option>
                                <option value="fa-info">&#xf129; info</option>
                                <option value="fa-info-circle">&#xf05a; info-circle</option>
                                <option value="fa-inr">&#xf156; inr</option>
                                <option value="fa-instagram">&#xf16d; instagram</option>
                                <option value="fa-institution">&#xf19c; institution</option>
                                <option value="fa-ioxhost">&#xf208; ioxhost</option>
                                <option value="fa-italic">&#xf033; italic</option>
                                <option value="fa-joomla">&#xf1aa; joomla</option>
                                <option value="fa-jpy">&#xf157; jpy</option>
                                <option value="fa-jsfiddle">&#xf1cc; jsfiddle</option>
                                <option value="fa-key">&#xf084; key</option>
                                <option value="fa-keyboard-o">&#xf11c; keyboard-o</option>
                                <option value="fa-krw">&#xf159; krw</option>
                                <option value="fa-language">&#xf1ab; language</option>
                                <option value="fa-laptop">&#xf109; laptop</option>
                                <option value="fa-lastfm">&#xf202; lastfm</option>
                                <option value="fa-lastfm-square">&#xf203; lastfm-square</option>
                                <option value="fa-leaf">&#xf06c; leaf</option>
                                <option value="fa-legal">&#xf0e3; legal</option>
                                <option value="fa-lemon-o">&#xf094; lemon-o</option>
                                <option value="fa-level-down">&#xf149; level-down</option>
                                <option value="fa-level-up">&#xf148; level-up</option>
                                <option value="fa-life-bouy">&#xf1cd; life-bouy</option>
                                <option value="fa-life-buoy">&#xf1cd; life-buoy</option>
                                <option value="fa-life-ring">&#xf1cd; life-ring</option>
                                <option value="fa-life-saver">&#xf1cd; life-saver</option>
                                <option value="fa-lightbulb-o">&#xf0eb; lightbulb-o</option>
                                <option value="fa-line-chart">&#xf201; line-chart</option>
                                <option value="fa-link">&#xf0c1; link</option>
                                <option value="fa-linkedin">&#xf0e1; linkedin</option>
                                <option value="fa-linkedin-square">&#xf08c; linkedin-square</option>
                                <option value="fa-linux">&#xf17c; linux</option>
                                <option value="fa-list">&#xf03a; list</option>
                                <option value="fa-list-alt">&#xf022; list-alt</option>
                                <option value="fa-list-ol">&#xf0cb; list-ol</option>
                                <option value="fa-list-ul">&#xf0ca; list-ul</option>
                                <option value="fa-location-arrow">&#xf124; location-arrow</option>
                                <option value="fa-lock">&#xf023; lock</option>
                                <option value="fa-long-arrow-down">&#xf175; long-arrow-down</option>
                                <option value="fa-long-arrow-left">&#xf177; long-arrow-left</option>
                                <option value="fa-long-arrow-right">&#xf178; long-arrow-right</option>
                                <option value="fa-long-arrow-up">&#xf176; long-arrow-up</option>
                                <option value="fa-magic">&#xf0d0; magic</option>
                                <option value="fa-magnet">&#xf076; magnet</option>
                                <option value="fa-mail-forward">&#xf064; mail-forward</option>
                                <option value="fa-mail-reply">&#xf112; mail-reply</option>
                                <option value="fa-mail-reply-all">&#xf122; mail-reply-all</option>
                                <option value="fa-male">&#xf183; male</option>
                                <option value="fa-map-marker">&#xf041; map-marker</option>
                                <option value="fa-maxcdn">&#xf136; maxcdn</option>
                                <option value="fa-meanpath">&#xf20c; meanpath</option>
                                <option value="fa-medkit">&#xf0fa; medkit</option>
                                <option value="fa-meh-o">&#xf11a; meh-o</option>
                                <option value="fa-microphone">&#xf130; microphone</option>
                                <option value="fa-microphone-slash">&#xf131; microphone-slash</option>
                                <option value="fa-minus">&#xf068; minus</option>
                                <option value="fa-minus-circle">&#xf056; minus-circle</option>
                                <option value="fa-minus-square">&#xf146; minus-square</option>
                                <option value="fa-minus-square-o">&#xf147; minus-square-o</option>
                                <option value="fa-mobile">&#xf10b; mobile</option>
                                <option value="fa-mobile-phone">&#xf10b; mobile-phone</option>
                                <option value="fa-money">&#xf0d6; money</option>
                                <option value="fa-moon-o">&#xf186; moon-o</option>
                                <option value="fa-mortar-board">&#xf19d; mortar-board</option>
                                <option value="fa-music">&#xf001; music</option>
                                <option value="fa-navicon">&#xf0c9; navicon</option>
                                <option value="fa-newspaper-o">&#xf1ea; newspaper-o</option>
                                <option value="fa-openid">&#xf19b; openid</option>
                                <option value="fa-outdent">&#xf03b; outdent</option>
                                <option value="fa-pagelines">&#xf18c; pagelines</option>
                                <option value="fa-paint-brush">&#xf1fc; paint-brush</option>
                                <option value="fa-paper-plane">&#xf1d8; paper-plane</option>
                                <option value="fa-paper-plane-o">&#xf1d9; paper-plane-o</option>
                                <option value="fa-paperclip">&#xf0c6; paperclip</option>
                                <option value="fa-paragraph">&#xf1dd; paragraph</option>
                                <option value="fa-paste">&#xf0ea; paste</option>
                                <option value="fa-pause">&#xf04c; pause</option>
                                <option value="fa-paw">&#xf1b0; paw</option>
                                <option value="fa-paypal">&#xf1ed; paypal</option>
                                <option value="fa-pencil">&#xf040; pencil</option>
                                <option value="fa-pencil-square">&#xf14b; pencil-square</option>
                                <option value="fa-pencil-square-o">&#xf044; pencil-square-o</option>
                                <option value="fa-phone">&#xf095; phone</option>
                                <option value="fa-phone-square">&#xf098; phone-square</option>
                                <option value="fa-photo">&#xf03e; photo</option>
                                <option value="fa-picture-o">&#xf03e; picture-o</option>
                                <option value="fa-pie-chart">&#xf200; pie-chart</option>
                                <option value="fa-pied-piper">&#xf1a7; pied-piper</option>
                                <option value="fa-pied-piper-alt">&#xf1a8; pied-piper-alt</option>
                                <option value="fa-pinterest">&#xf0d2; pinterest</option>
                                <option value="fa-pinterest-square">&#xf0d3; pinterest-square</option>
                                <option value="fa-plane">&#xf072; plane</option>
                                <option value="fa-play">&#xf04b; play</option>
                                <option value="fa-play-circle">&#xf144; play-circle</option>
                                <option value="fa-play-circle-o">&#xf01d; play-circle-o</option>
                                <option value="fa-plug">&#xf1e6; plug</option>
                                <option value="fa-plus">&#xf067; plus</option>
                                <option value="fa-plus-circle">&#xf055; plus-circle</option>
                                <option value="fa-plus-square">&#xf0fe; plus-square</option>
                                <option value="fa-plus-square-o">&#xf196; plus-square-o</option>
                                <option value="fa-power-off">&#xf011; power-off</option>
                                <option value="fa-print">&#xf02f; print</option>
                                <option value="fa-puzzle-piece">&#xf12e; puzzle-piece</option>
                                <option value="fa-qq">&#xf1d6; qq</option>
                                <option value="fa-qrcode">&#xf029; qrcode</option>
                                <option value="fa-question">&#xf128; question</option>
                                <option value="fa-question-circle">&#xf059; question-circle</option>
                                <option value="fa-quote-left">&#xf10d; quote-left</option>
                                <option value="fa-quote-right">&#xf10e; quote-right</option>
                                <option value="fa-ra">&#xf1d0; ra</option>
                                <option value="fa-random">&#xf074; random</option>
                                <option value="fa-rebel">&#xf1d0; rebel</option>
                                <option value="fa-recycle">&#xf1b8; recycle</option>
                                <option value="fa-reddit">&#xf1a1; reddit</option>
                                <option value="fa-reddit-square">&#xf1a2; reddit-square</option>
                                <option value="fa-refresh">&#xf021; refresh</option>
                                <option value="fa-remove">&#xf00d; remove</option>
                                <option value="fa-renren">&#xf18b; renren</option>
                                <option value="fa-reorder">&#xf0c9; reorder</option>
                                <option value="fa-repeat">&#xf01e; repeat</option>
                                <option value="fa-reply">&#xf112; reply</option>
                                <option value="fa-reply-all">&#xf122; reply-all</option>
                                <option value="fa-retweet">&#xf079; retweet</option>
                                <option value="fa-rmb">&#xf157; rmb</option>
                                <option value="fa-road">&#xf018; road</option>
                                <option value="fa-rocket">&#xf135; rocket</option>
                                <option value="fa-rotate-left">&#xf0e2; rotate-left</option>
                                <option value="fa-rotate-right">&#xf01e; rotate-right</option>
                                <option value="fa-rouble">&#xf158; rouble</option>
                                <option value="fa-rss">&#xf09e; rss</option>
                                <option value="fa-rss-square">&#xf143; rss-square</option>
                                <option value="fa-rub">&#xf158; rub</option>
                                <option value="fa-ruble">&#xf158; ruble</option>
                                <option value="fa-rupee">&#xf156; rupee</option>
                                <option value="fa-save">&#xf0c7; save</option>
                                <option value="fa-scissors">&#xf0c4; scissors</option>
                                <option value="fa-search">&#xf002; search</option>
                                <option value="fa-search-minus">&#xf010; search-minus</option>
                                <option value="fa-search-plus">&#xf00e; search-plus</option>
                                <option value="fa-send">&#xf1d8; send</option>
                                <option value="fa-send-o">&#xf1d9; send-o</option>
                                <option value="fa-share">&#xf064; share</option>
                                <option value="fa-share-alt">&#xf1e0; share-alt</option>
                                <option value="fa-share-alt-square">&#xf1e1; share-alt-square</option>
                                <option value="fa-share-square">&#xf14d; share-square</option>
                                <option value="fa-share-square-o">&#xf045; share-square-o</option>
                                <option value="fa-shekel">&#xf20b; shekel</option>
                                <option value="fa-sheqel">&#xf20b; sheqel</option>
                                <option value="fa-shield">&#xf132; shield</option>
                                <option value="fa-shopping-cart">&#xf07a; shopping-cart</option>
                                <option value="fa-sign-in">&#xf090; sign-in</option>
                                <option value="fa-sign-out">&#xf08b; sign-out</option>
                                <option value="fa-signal">&#xf012; signal</option>
                                <option value="fa-sitemap">&#xf0e8; sitemap</option>
                                <option value="fa-skype">&#xf17e; skype</option>
                                <option value="fa-slack">&#xf198; slack</option>
                                <option value="fa-sliders">&#xf1de; sliders</option>
                                <option value="fa-slideshare">&#xf1e7; slideshare</option>
                                <option value="fa-smile-o">&#xf118; smile-o</option>
                                <option value="fa-soccer-ball-o">&#xf1e3; soccer-ball-o</option>
                                <option value="fa-sort">&#xf0dc; sort</option>
                                <option value="fa-sort-alpha-asc">&#xf15d; sort-alpha-asc</option>
                                <option value="fa-sort-alpha-desc">&#xf15e; sort-alpha-desc</option>
                                <option value="fa-sort-amount-asc">&#xf160; sort-amount-asc</option>
                                <option value="fa-sort-amount-desc">&#xf161; sort-amount-desc</option>
                                <option value="fa-sort-asc">&#xf0de; sort-asc</option>
                                <option value="fa-sort-desc">&#xf0dd; sort-desc</option>
                                <option value="fa-sort-down">&#xf0dd; sort-down</option>
                                <option value="fa-sort-numeric-asc">&#xf162; sort-numeric-asc</option>
                                <option value="fa-sort-numeric-desc">&#xf163; sort-numeric-desc</option>
                                <option value="fa-sort-up">&#xf0de; sort-up</option>
                                <option value="fa-soundcloud">&#xf1be; soundcloud</option>
                                <option value="fa-space-shuttle">&#xf197; space-shuttle</option>
                                <option value="fa-spinner">&#xf110; spinner</option>
                                <option value="fa-spoon">&#xf1b1; spoon</option>
                                <option value="fa-spotify">&#xf1bc; spotify</option>
                                <option value="fa-square">&#xf0c8; square</option>
                                <option value="fa-square-o">&#xf096; square-o</option>
                                <option value="fa-stack-exchange">&#xf18d; stack-exchange</option>
                                <option value="fa-stack-overflow">&#xf16c; stack-overflow</option>
                                <option value="fa-star">&#xf005; star</option>
                                <option value="fa-star-half">&#xf089; star-half</option>
                                <option value="fa-star-half-empty">&#xf123; star-half-empty</option>
                                <option value="fa-star-half-full">&#xf123; star-half-full</option>
                                <option value="fa-star-half-o">&#xf123; star-half-o</option>
                                <option value="fa-star-o">&#xf006; star-o</option>
                                <option value="fa-steam">&#xf1b6; steam</option>
                                <option value="fa-steam-square">&#xf1b7; steam-square</option>
                                <option value="fa-step-backward">&#xf048; step-backward</option>
                                <option value="fa-step-forward">&#xf051; step-forward</option>
                                <option value="fa-stethoscope">&#xf0f1; stethoscope</option>
                                <option value="fa-stop">&#xf04d; stop</option>
                                <option value="fa-strikethrough">&#xf0cc; strikethrough</option>
                                <option value="fa-stumbleupon">&#xf1a4; stumbleupon</option>
                                <option value="fa-stumbleupon-circle">&#xf1a3; stumbleupon-circle</option>
                                <option value="fa-subscript">&#xf12c; subscript</option>
                                <option value="fa-suitcase">&#xf0f2; suitcase</option>
                                <option value="fa-sun-o">&#xf185; sun-o</option>
                                <option value="fa-superscript">&#xf12b; superscript</option>
                                <option value="fa-support">&#xf1cd; support</option>
                                <option value="fa-table">&#xf0ce; table</option>
                                <option value="fa-tablet">&#xf10a; tablet</option>
                                <option value="fa-tachometer">&#xf0e4; tachometer</option>
                                <option value="fa-tag">&#xf02b; tag</option>
                                <option value="fa-tags">&#xf02c; tags</option>
                                <option value="fa-tasks">&#xf0ae; tasks</option>
                                <option value="fa-taxi">&#xf1ba; taxi</option>
                                <option value="fa-tencent-weibo">&#xf1d5; tencent-weibo</option>
                                <option value="fa-terminal">&#xf120; terminal</option>
                                <option value="fa-text-height">&#xf034; text-height</option>
                                <option value="fa-text-width">&#xf035; text-width</option>
                                <option value="fa-th">&#xf00a; th</option>
                                <option value="fa-th-large">&#xf009; th-large</option>
                                <option value="fa-th-list">&#xf00b; th-list</option>
                                <option value="fa-thumb-tack">&#xf08d; thumb-tack</option>
                                <option value="fa-thumbs-down">&#xf165; thumbs-down</option>
                                <option value="fa-thumbs-o-down">&#xf088; thumbs-o-down</option>
                                <option value="fa-thumbs-o-up">&#xf087; thumbs-o-up</option>
                                <option value="fa-thumbs-up">&#xf164; thumbs-up</option>
                                <option value="fa-ticket">&#xf145; ticket</option>
                                <option value="fa-times">&#xf00d; times</option>
                                <option value="fa-times-circle">&#xf057; times-circle</option>
                                <option value="fa-times-circle-o">&#xf05c; times-circle-o</option>
                                <option value="fa-tint">&#xf043; tint</option>
                                <option value="fa-toggle-down">&#xf150; toggle-down</option>
                                <option value="fa-toggle-left">&#xf191; toggle-left</option>
                                <option value="fa-toggle-off">&#xf204; toggle-off</option>
                                <option value="fa-toggle-on">&#xf205; toggle-on</option>
                                <option value="fa-toggle-right">&#xf152; toggle-right</option>
                                <option value="fa-toggle-up">&#xf151; toggle-up</option>
                                <option value="fa-trash">&#xf1f8; trash</option>
                                <option value="fa-trash-o">&#xf014; trash-o</option>
                                <option value="fa-tree">&#xf1bb; tree</option>
                                <option value="fa-trello">&#xf181; trello</option>
                                <option value="fa-trophy">&#xf091; trophy</option>
                                <option value="fa-truck">&#xf0d1; truck</option>
                                <option value="fa-try">&#xf195; try</option>
                                <option value="fa-tty">&#xf1e4; tty</option>
                                <option value="fa-tumblr">&#xf173; tumblr</option>
                                <option value="fa-tumblr-square">&#xf174; tumblr-square</option>
                                <option value="fa-turkish-lira">&#xf195; turkish-lira</option>
                                <option value="fa-twitch">&#xf1e8; twitch</option>
                                <option value="fa-twitter">&#xf099; twitter</option>
                                <option value="fa-twitter-square">&#xf081; twitter-square</option>
                                <option value="fa-umbrella">&#xf0e9; umbrella</option>
                                <option value="fa-underline">&#xf0cd; underline</option>
                                <option value="fa-undo">&#xf0e2; undo</option>
                                <option value="fa-university">&#xf19c; university</option>
                                <option value="fa-unlink">&#xf127; unlink</option>
                                <option value="fa-unlock">&#xf09c; unlock</option>
                                <option value="fa-unlock-alt">&#xf13e; unlock-alt</option>
                                <option value="fa-unsorted">&#xf0dc; unsorted</option>
                                <option value="fa-upload">&#xf093; upload</option>
                                <option value="fa-usd">&#xf155; usd</option>
                                <option value="fa-user">&#xf007; user</option>
                                <option value="fa-user-md">&#xf0f0; user-md</option>
                                <option value="fa-users">&#xf0c0; users</option>
                                <option value="fa-video-camera">&#xf03d; video-camera</option>
                                <option value="fa-vimeo-square">&#xf194; vimeo-square</option>
                                <option value="fa-vine">&#xf1ca; vine</option>
                                <option value="fa-vk">&#xf189; vk</option>
                                <option value="fa-volume-down">&#xf027; volume-down</option>
                                <option value="fa-volume-off">&#xf026; volume-off</option>
                                <option value="fa-volume-up">&#xf028; volume-up</option>
                                <option value="fa-warning">&#xf071; warning</option>
                                <option value="fa-wechat">&#xf1d7; wechat</option>
                                <option value="fa-weibo">&#xf18a; weibo</option>
                                <option value="fa-weixin">&#xf1d7; weixin</option>
                                <option value="fa-wheelchair">&#xf193; wheelchair</option>
                                <option value="fa-wifi">&#xf1eb; wifi</option>
                                <option value="fa-windows">&#xf17a; windows</option>
                                <option value="fa-won">&#xf159; won</option>
                                <option value="fa-wordpress">&#xf19a; wordpress</option>
                                <option value="fa-wrench">&#xf0ad; wrench</option>
                                <option value="fa-xing">&#xf168; xing</option>
                                <option value="fa-xing-square">&#xf169; xing-square</option>
                                <option value="fa-yahoo">&#xf19e; yahoo</option>
                                <option value="fa-yelp">&#xf1e9; yelp</option>
                                <option value="fa-yen">&#xf157; yen</option>
                                <option value="fa-youtube">&#xf167; youtube</option>
                                <option value="fa-youtube-play">&#xf16a; youtube-play</option>
                                <option value="fa-youtube-square">&#xf166; youtube-square</option>
                            </select>

                        </div><!-- /.tab-pane -->

                        <!-- /tabs -->
                        <div class="tab-pane videoTab" id="video_Tab">

                            <label><?php echo $this->lang->line('enter_youtube_id'); ?>:</label>

                            <input type="text" class="form-control margin-bottom-20" id="youtubeID" placeholder="<?php echo $this->lang->line('video_placeholder_youtubeid'); ?>" value="">

                            <p class="text-center or">
                                <span><?php echo $this->lang->line('OR'); ?></span>
                            </p>

                            <label><?php echo $this->lang->line('enter_vimeo_id'); ?>:</label>

                            <input type="text" class="form-control margin-bottom-20" id="vimeoID" placeholder="<?php echo $this->lang->line('video_placeholder_vimeoid'); ?>" value="">

                        </div><!-- /.tab-pane -->

                        <div class="tab-pane menuitemsTab" id="menuitems_Tab">

                            Menu items

                        </div>

                        <div class="tab-pane formTab" id="form_Tab">

                            <label class="checkbox" for="checkboxEmailForm">
                                <input type="checkbox" value="" id="checkboxEmailForm" data-toggle="checkbox">
                                <?php echo $this->lang->line('form_tab_email_data_to'); ?>
                            </label>

                            <input type="email" class="form-control margin-bottom-20" id="inputEmailFormTo" placeholder="<?php echo $this->lang->line('form_tab_placeholder_emailaddress'); ?>" value="<?php echo $_SESSION['user_email']; ?>" disabled>

                            <textarea rows="6" class="form-control margin-bottom-20" id="textareaCustomMessage" placeholder="<?php echo $this->lang->line('form_tab_placeholder_confirmation'); ?>" disabled></textarea>

                            <p class="text-center or">
                                <span><?php echo $this->lang->line('OR'); ?></span>
                            </p>

                            <label class="checkbox" for="checkboxCustomAction">
                                <input type="checkbox" value="" id="checkboxCustomAction" data-toggle="checkbox">
                                <?php echo $this->lang->line('form_tab_custom_action'); ?>
                            </label>

                            <input type="text" class="form-control margin-bottom-20" id="inputCustomAction" placeholder="<?php echo $this->lang->line('form_tab_placeholder_action'); ?>" value="" disabled>

                            <p class="text-center or">
                                <span><?php echo $this->lang->line('OR'); ?></span>
                            </p>

                            <label class="checkbox" for="checkboxApiIntegration">
                                <input type="checkbox" value="" id="checkboxApiIntegration" data-toggle="checkbox">
                                <?php echo $this->lang->line('form_tab_api_integration'); ?>
                            </label>

                            <select class="form-control select-primary select-sm margin-bottom-20" id="selectConfiguredApi" style="min-width: 0px; width: 100%" disabled>
                                <option value="">Select API</option>
                                <?php foreach( $configured_apis as $configured_api ):?>
                                    <option value="<?php echo $configured_api['name'];?>"><?php echo ucfirst($configured_api['label']);?></option>
                                <?php endforeach;?>
                            </select>

                            <select class="form-control select-primary select-sm margin-bottom-20" id="selectApiList" style="min-width: 0px; width: 100%" disabled>
                                <option value="">Select List</option>
                            </select>

                            <p class="text-center or">
                                <span><?php echo $this->lang->line('OR'); ?></span>
                            </p>
                            <label class="checkbox" for="checkboxApiIntegrationWithHtmlCode">
                                <input type="checkbox" value="" id="checkboxApiIntegrationWithHtmlCode" data-toggle="checkbox">
                                <?php echo $this->lang->line('form_tab_api_integration_with_html_code'); ?>
                            </label>
                            <textarea rows="6" class="form-control margin-bottom-20" id="textareaHtmlCode" placeholder="<?php echo $this->lang->line('form_tab_placeholder_html_code'); ?>"></textarea>
                        </div>

                        <div class="tab-pane slideshowTab" id="slideshow_Tab">

                            <div class="row margin-bottom-20">

                                <div class="col-md-6">
                                    <?php echo $this->lang->line('slideshow_tab_autoplay'); ?>
                                </div>

                                <div class="col-md-6 text-right">
                                    <input type="checkbox" checked data-toggle="switch" name="default-switch" id="checkboxSliderAutoplay">
                                </div>

                            </div><!-- /.row -->

                            <div class="row margin-bottom-20">

                                <div class="col-md-6">
                                    <?php echo $this->lang->line('slideshow_tab_pauseonhover'); ?>
                                </div>

                                <div class="col-md-6 text-right">
                                    <input type="checkbox" checked data-toggle="switch" name="default-switch" id="checkboxSliderPause">
                                </div>

                            </div><!-- /.row -->

                            <div class="row margin-bottom-20">

                                <div class="col-md-6">
                                    <?php echo $this->lang->line('slideshow_tab_effect'); ?>
                                </div>

                                <div class="col-md-6">
                                    <select class="form-control select select-primary select-sm select-block" id="selectSliderAnimation" style="min-width: 0px; width: 100%">
                                        <option value=""><?php echo $this->lang->line('slideshow_tab_effect_slide'); ?></option>
                                        <option value="carousel-fade"><?php echo $this->lang->line('slideshow_tab_effect_fade'); ?></option>
                                    </select>

                                </div>

                            </div><!-- /.row -->

                            <div class="row margin-bottom-20">

                                <div class="col-md-6">
                                    <?php echo $this->lang->line('slideshow_tab_interval'); ?>
                                </div>

                                <div class="col-md-6 text-right">
                                    <input type="number" class="form-control input-sm" id="inputSlideInterval" placeholder="5000" value="">
                                </div>

                            </div><!-- /.row -->

                            <div class="row margin-bottom-20">

                                <div class="col-md-6">
                                    <?php echo $this->lang->line('slideshow_tab_navarrows'); ?>
                                </div>

                                <div class="col-md-6">
                                    <select class="form-control select select-primary select-sm select-block" id="selectSliderNavArrows" style="min-width: 0px; width: 100%">
                                        <option value="nav-arrows-in"><?php echo $this->lang->line('slideshow_tab_navarrows_inside'); ?></option>
                                        <option value="nav-arrows-out"><?php echo $this->lang->line('slideshow_tab_navarrows_outside'); ?></option>
                                        <option value="nav-arrows-none"><?php echo $this->lang->line('slideshow_tab_navarrows_none'); ?></option>
                                    </select>

                                </div>

                            </div><!-- /.row -->

                            <div class="row margin-bottom-20">

                                <div class="col-md-6">
                                    <?php echo $this->lang->line('slideshow_tab_navindicators'); ?>
                                </div>

                                <div class="col-md-6">
                                    <select class="form-control select select-primary select-sm select-block" id="selectSliderNavIndicators" style="min-width: 0px; width: 100%">
                                        <option value="nav-indicators-in"><?php echo $this->lang->line('slideshow_tab_navarrows_inside'); ?></option>
                                        <option value="nav-indicators-out"><?php echo $this->lang->line('slideshow_tab_navarrows_outside'); ?></option>
                                        <option value="nav-indicators-none"><?php echo $this->lang->line('slideshow_tab_navarrows_none'); ?></option>
                                    </select>

                                </div>

                            </div><!-- /.row -->

                        </div>

                        <?php if ($this->config->item('google_api') != '') : ?>
                            <div class="tab-pane mapTab" id="map_Tab">

                                <textarea class="form-control margin-bottom-20" placeholder="<?php echo $this->lang->line('map_tab_address');?>" name="textareaAddress" id="textareaAddress"></textarea>

                                <textarea class="form-control margin-bottom-20" placeholder="<?php echo $this->lang->line('map_tab_infomessage');?>" name="textareaInfoMessage" id="textareaInfoMessage"></textarea>

                                <div class="row margin-bottom-20">

                                    <div class="col-md-6">
                                        <?php echo $this->lang->line('map_tab_zoomlevel'); ?>
                                    </div>

                                    <div class="col-md-6 text-right">
                                        <input type="number" class="form-control" id="inputZoomLevel" value="10">
                                    </div>

                                </div><!-- /.row -->

                                <div class="row margin-bottom-20">

                                    <div class="col-md-6">
                                        <?php echo $this->lang->line('map_tab_blackandwhite'); ?>
                                    </div>

                                    <div class="col-md-6 text-right">
                                        <input type="checkbox" data-toggle="switch" name="default-switch" id="checkBoxMapBW">
                                    </div>

                                </div><!-- /.row -->

                            </div>
                        <?php endif;?>

                        <div class="tab-pane countdownTab" id="countdown_Tab">

                            <div class="alert alert-info">
                                <?php echo $this->lang->line('countdown_tab_warning'); ?>
                            </div>

                            <div style="display: flex; margin-bottom: 20px">
                                <div style="width: 33%; text-align: right">
                                    <?php echo $this->lang->line('countdown_tab_datetime');?>
                                </div>
                                <div style="width: 33%; display: flex; justify-content: center;">
                                    <input type="checkbox" id="checkboxCountdownType" class="cbx hidden">
                                    <label for="checkboxCountdownType" class="lbl"></label>
                                </div>
                                <div style="width: 33%; text-align: left">
                                    <?php echo $this->lang->line('countdown_tab_landing');?>
                                </div>
                            </div>

                            <div id="countdownSectionDatetime" style="margin-bottom: 40px;">

                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn" type="button"><span class="fui-calendar"></span></button>
                                        </span>
                                        <input type="text" class="form-control tail-datetime-picker" value="" id="countdownPicker" placeholder="<?php echo $this->lang->line('countdown_tab_deadline_placeholder');?>" autocomplete="off">
                                    </div>
                                </div>
                                <div id="datetime-demo-holder" class="datetime-folder"></div>

                                <select class="form-control select select-default select-sm select-block" id="selectGmtConversion" style="min-width: 0px; width: 100%" data-with-search>

                                    <option data-offset="" value="" selected>No timezone conversion</option>
                                    <option data-offset="-12:00">GMT -12:00</option>
                                    <option data-offset="-11:00">GMT -11:00</option>
                                    <option data-offset="-10:00">GMT -10:00</option>
                                    <option data-offset="-09:00">GMT -09:00</option>
                                    <option data-offset="-08:00">GMT -08:00</option>
                                    <option data-offset="-07:00">GMT -07:00</option>
                                    <option data-offset="-06:00">GMT -06:00</option>
                                    <option data-offset="-05:00">GMT -05:00</option>
                                    <option data-offset="-04:00">GMT -04:00</option>
                                    <option data-offset="-03:00">GMT -03:00</option>
                                    <option data-offset="-02:00">GMT -02:00</option>
                                    <option data-offset="-01:00">GMT -01:00</option>
                                    <option data-offset="-00:00">GMT -00:00</option>
                                    <option data-offset="+01:00">GMT +01:00</option>
                                    <option data-offset="+02:00">GMT +02:00</option>
                                    <option data-offset="+03:00">GMT +03:00</option>
                                    <option data-offset="+04:00">GMT +04:00</option>
                                    <option data-offset="+05:00">GMT +05:00</option>
                                    <option data-offset="+06:00">GMT +06:00</option>
                                    <option data-offset="+07:00">GMT +07:00</option>
                                    <option data-offset="+08:00">GMT +08:00</option>
                                    <option data-offset="+09:00">GMT +09:00</option>
                                    <option data-offset="+10:00">GMT +10:00</option>
                                    <option data-offset="+11:00">GMT +11:00</option>
                                    <option data-offset="+12:00">GMT +1:00</option>

                                </select>

                            </div>

                            <div id="countdownSectionLanding" style="display: none;">

                                <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">

                                    <div style="width: 30%; text-align: center;">
                                        <span class="small"><?php echo $this->lang->line('countdown_tab_days');?></span>
                                        <input type="text" class="form-control" id="inputCountdownDays" placeholder="00" value="">
                                    </div>

                                    <div style="width: 30%; text-align: center;">
                                        <span class="small"><?php echo $this->lang->line('countdown_tab_hours');?></span>
                                        <input type="text" class="form-control" id="inputCountdownHours" placeholder="00" value="">
                                    </div>

                                    <div style="width: 30%; text-align: center;">
                                        <span class="small"><?php echo $this->lang->line('countdown_tab_minutes');?></span>
                                        <input type="text" class="form-control" id="inputCountdownMinutes" placeholder="00" value="">
                                    </div>

                                </div>

                                <label class="radio" for="radioDailyReset3">
                                    <input type="radio" value="none" id="radioDailyReset3" name="radioCountdownReset" data-toggle="radio" checked>
                                    <?php echo $this->lang->line('countdown_tab_reset_none'); ?>
                                </label>

                                <label class="radio" for="radioDailyReset2">
                                    <input type="radio" value="load" id="radioDailyReset2" name="radioCountdownReset" data-toggle="radio">
                                    <?php echo $this->lang->line('countdown_tab_reset_load'); ?>
                                </label>

                                <label class="radio" for="radioDailyReset1">
                                    <input type="radio" value="daily" id="radioDailyReset1" name="radioCountdownReset" data-toggle="radio">
                                    <?php echo $this->lang->line('countdown_tab_daily_reset'); ?>
                                </label>
                            </div>
                            <div id="checkboxDailySection">
                                <label class="checkbox" for="checkboxDaily">
                                    <input name="checkboxDaily" type="checkbox" value="" id="checkboxDaily" data-toggle="checkbox">
                                    <?php echo $this->lang->line('countdown_tab_checkbox_daily_label'); ?>
                                </label>
                            </div>
                            <label class="checkbox" for="checkboxDeadlineUrl">
                                <input type="checkbox" value="" id="checkboxDeadlineUrl" data-toggle="checkbox">
                                <?php echo $this->lang->line('countdown_tab_deadline_redirect_label'); ?>
                            </label>
                            <div class="form-group">
                                <input type="text" class="form-control margin-bottom-20" id="inputDeadlineRedirect" placeholder="<?php echo $this->lang->line('countdown_tab_deadline_redirect_placeholder'); ?>" value="" disabled style="float:none; width: 100%">
                            </div>
                        </div>
                    </div> <!-- /tab-content -->

                    <div class="alert alert-success" style="display: none;" id="detailsAppliedMessage">
                        <button class="close fui-cross" type="button" id="detailsAppliedMessageHide"></button>
                        <?php echo $this->lang->line('the_changes_were_applied'); ?>
                    </div>

                    <div class="margin-bottom-5">
                        <button type="button" class="btn btn-primary btn-embossed btn-sm btn-block" id="saveStyling">
                            <span class="fui-check-inverted"></span>
                            <?php echo $this->lang->line('sidebuttons_apply_changes'); ?>
                        </button>
                    </div>
                </div><!-- /.styleEditorInner -->

            </div><!-- /.styleEditor -->

        </div><!-- /.builderLayout -->

        <div id="hidden">
            <iframe src="<?php echo base_url(); ?>elements/skeleton.html" id="skeleton"></iframe>
        </div>

        <!-- modals -->

        <!-- export HTML popup -->
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-hidden="TRUE">

            <form action="sites/export" target="_blank" id="markupForm" method="post" class="form-horizontal">

                <input type="hidden" name="siteID" value="<?php echo $siteData['site']->sites_id; ?>">

                <input type="hidden" name="markup" value="" id="markupField1">

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                            <h4 class="modal-title" id="myModalLabel"><span class="fui-export"></span> <?php echo $this->lang->line('modalexport_export_your_markup'); ?></h4>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label"><?php echo $this->lang->line('modalexport_doctype'); ?></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="doctype" id="doctype" placeholder="<?php echo $this->lang->line('modalexport_placeholder_doctype'); ?>" value="<!DOCTYPE html>">
                                </div>
                            </div>

                        </div><!-- /.modal-body -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal" id="exportCancel"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                            <button type="submit" type="button" class="btn btn-primary btn-embossed" id="exportSubmit"><?php echo $this->lang->line('modalexport_export_now'); ?></button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->

            </form>

        </div><!-- /.modal -->


        <!-- publish popup -->
        <div class="modal fade publishModal" id="publishModal" tabindex="-1" role="dialog" aria-hidden="TRUE">

            <form action="sites/publish/<?php echo $siteData['site']->sites_id; ?>" target="_blank" id="publishForm" method="post" class="form-horizontal">

                <input type="hidden" name="siteID" value="<?php echo $siteData['site']->sites_id; ?>">

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                            <h4 class="modal-title" id="myModalLabel"><span class="fui-upload"></span> <?php echo $this->lang->line('modalpublish_publishyoursite'); ?></h4>
                        </div>
                        <div class="modal-body">

                            <div class="loader" style="display: none;">
                                <img src="<?php echo base_url(); ?>img/loading.gif" alt="<?php echo $this->lang->line('loading'); ?>">
                                <?php echo $this->lang->line('loading_saving_data'); ?> ...
                            </div>

                            <div class="alert alert-success" style="display: none;">
                                <h4><?php echo $this->lang->line('modalpublish_success_heading'); ?></h4>
                                <?php echo $this->lang->line('modalpublish_success_message'); ?>
                            </div>

                            <div class="alert alert-error">
                                <h4><?php echo $this->lang->line('modalpublish_error_heading'); ?></h4>
                                <?php echo $this->lang->line('modalpublish_error_message'); ?>
                            </div>

                            <div class="modal-alerts">

                            </div>

                            <div class="alert alert-info" style="display: none;" id="publishPendingChangesMessage">
                                <h4><?php echo $this->lang->line('modalpublish_pendingchanges_heading'); ?></h4>
                                <p>
                                    <?php echo $this->lang->line('modalpublish_pendingchanges_message'); ?>
                                </p>
                                <button type="button" class="btn btn-info btn-wide save" id="buttonSavePendingBeforePublishing"><?php echo $this->lang->line('modalpublish_pendingchanges_button_savechanges'); ?></button>
                            </div>

                            <div class="modal-body-content">
                                <!--<div class="optionPane export">

                                    <h6><?php //echo $this->lang->line('modalpublish_siteassets'); ?></h6>

                                    <div class="table-responsive" id="publishModal_assets">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 30px;">
                                                        <label class="checkbox no-label toggle-all" for="checkbox-table-1">
                                                            <input type="checkbox" value="" id="checkbox-table-1" data-toggle="checkbox" class="toggleAll">
                                                        </label>
                                                    </th>
                                                    <th><?php //echo $this->lang->line('modalpublish_asset'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php //$c = 0;?>
                                                <?php //foreach ($siteData['assetFolders'] as $folder) : ?>
                                                    <tr>
                                                        <td class="text-center" style="width: 30px;">
                                                            <label class="checkbox no-label">
                                                                <input type="checkbox" value="<?php //echo str_replace('/', '', $folder); ?>" id="<?php //echo $folder . $c;?>" data-type="asset" name="assetFolders[]" data-toggle="checkbox">
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <?php //echo str_replace('/', '', $folder); ?>
                                                            <span class="publishing">
                                                                <span class="working"><?php //echo $this->lang->line('modalpublish_publishing'); ?> <img src="<?php //echo base_url(); ?>img/publishLoader.gif"></span>
                                                                <span class="done text-primary"><?php //echo $this->lang->line('modalpublish_published'); ?> &nbsp;<span class="fui-check"></span></span>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php //$c++;?>
                                                <?php //endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div> /.table-responsive

                                </div> /.optionPane -->

                                <div class="optionPane export">

                                    <h6><?php echo $this->lang->line('modalpublish_sitepages'); ?></h6>

                                    <div class="table-responsive" id="publishModal_pages">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 30px;">
                                                        <label class="checkbox no-label toggle-all" for="checkbox-table-2">
                                                            <input type="checkbox" value="" id="checkbox-table-2" data-toggle="checkbox" class="toggleAll">
                                                        </label>
                                                    </th>
                                                    <th><?php echo $this->lang->line('modalpublish_page'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div><!-- /.table-responsive -->

                                </div><!-- /.optionPane -->
                            </div>

                        </div><!-- /.modal-body -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal" id="publishCancel"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                            <button type="button" type="button" class="btn btn-primary btn-embossed disabled" id="publishSubmit"><?php echo $this->lang->line('modalpublish_publish_now'); ?></button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->

            </form>

        </div><!-- /.modal -->


        <div class="modal fade imageModal" id="imageModal" tabindex="-1" role="dialog" aria-hidden="TRUE">

            <div class="modal-dialog modal-hg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                        <h4 class="modal-title" id="myModalLabel"><span class="fui-upload"></span> <?php echo $this->lang->line('modal_imagelibrary_heading'); ?></h4>
                    </div>
                    <div class="modal-body">

                        <div class="loader" style="display: none;">
                            <img src="<?php echo base_url(); ?>img/loading.gif" alt="<?php echo $this->lang->line('loading'); ?>">
                            <?php echo $this->lang->line('modal_imagelibrary_loadertext'); ?>
                        </div>

                        <div class="modal-alerts">

                        </div>

                        <div class="modal-body-content">

                            <?php $this->load->view("shared/imageLibrary.php", array('userImages' => $userImages, 'adminImages' => $adminImages, 'page' => $page)); ?>

                        </div>

                    </div><!-- /.modal-body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-embossed btn-wide" data-dismiss="modal"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                        <button type="button" class="btn btn-primary btn-embossed btn-wide" id="buttonImageModalUseImage"><?php echo $this->lang->line('modal_imagelibrary_button_insert'); ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->


        <!-- delete single block popup -->
        <div class="modal fade small-modal" id="deleteBlock" tabindex="-1" role="dialog" aria-hidden="TRUE">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">

                        <?php echo $this->lang->line('modaldeleteblock_areyousure'); ?>

                    </div><!-- /.modal-body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                        <button type="button" type="button" class="btn btn-primary btn-embossed" id="deleteBlockConfirm"><?php echo $this->lang->line('modal_delete'); ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

        <!-- reset block popup -->
        <div class="modal fade small-modal" id="resetBlock" tabindex="-1" role="dialog" aria-hidden="TRUE">

            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>
                            <?php echo $this->lang->line('modalresetblock_areyousure'); ?>
                        </p>
                        <p>
                            <?php echo $this->lang->line('modalresetblock_message'); ?>
                        </p>
                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                        <button type="button" type="button" class="btn btn-primary btn-embossed" id="resetBlockConfirm"><?php echo $this->lang->line('modalresetblock_button_reset'); ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

        <!-- delete page popup -->
        <div class="modal fade small-modal" id="deletePage" tabindex="-1" role="dialog" aria-hidden="TRUE">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>
                            <?php echo $this->lang->line('modaldeletepage_areyousure'); ?>
                        </p>
                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal" id="deletePageCancel"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                        <button type="button" type="button" class="btn btn-primary btn-embossed" id="deletePageConfirm"><?php echo $this->lang->line('modaldeletepage_button_deletepage'); ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

        <!-- delete elemnent popup -->
        <div class="modal fade small-modal" id="deleteElement" tabindex="-1" role="dialog" aria-hidden="TRUE">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>
                            <?php echo $this->lang->line('modaldeleteelement_areyousure'); ?>
                        </p>
                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal" id="deletePageCancel"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                        <button type="button" type="button" class="btn btn-primary btn-embossed" id="deleteElementConfirm"><?php echo $this->lang->line('modaldeleteelement_button_deleteelement'); ?></button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

        <div id="loader">
            <span>{</span><span>}</span>
        </div>

    </div>


    <!-- modals -->

    <?php $this->load->view("shared/modal_sitesettings.php"); ?>

    <?php $this->load->view("shared/modal_account.php"); ?>

    <div class="modal fade pageSettingsModal" id="pageSettingsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="TRUE">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                    <h4 class="modal-title" id="myModalLabel"><span class="fui-gear"></span> <?php echo $this->lang->line('modal_pagesettings_header'); ?> <span class="text-primary pName" id="spanPageName">index.html</span></h4>
                </div>

                <div class="modal-body">
                    <div class="loader" style="display: none;">
                        <img src="<?php echo base_url(); ?>img/loading.gif" alt="<?php echo $this->lang->line('loading'); ?>">
                        <?php echo $this->lang->line('modal_pagesettings_loadertext'); ?>
                    </div>

                    <div class="modal-alerts"></div>

                    <?php
                    if (isset($pagesData))
                    {
                        echo $this->load->view('shared/pagedata.php', array('pagesData'=>$pagesData, 'siteData'=>$siteData['site']), TRUE);
                    }
                    else
                    {
                        echo $this->load->view('shared/pagedata.php', array('siteData'=>$siteData['site']), TRUE);
                    }
                    ?>
                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_cancelclose'); ?></button>
                    <button type="button" class="btn btn-primary btn-embossed" id="pageSettingsSubmittButton"><span class="fui-check"></span> <?php echo $this->lang->line('sitesettings_button_savesettings'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->


    <div class="modal fade errorModal" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="TRUE">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-body">

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_close'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <div class="modal fade successModal" id="successModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="TRUE">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-body">

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_close'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <div class="modal fade backModal" id="backModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="TRUE">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                    <h4 class="modal-title" id="myModalLabel"> <?php echo $this->lang->line('modal_areyousure'); ?></h4>
                </div>

                <div class="modal-body">
                    <p>
                        <?php echo $this->lang->line('modal_pendingchanges_areyousure'); ?>
                    </p>
                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_pendingchanges_button_stay'); ?></button>
                    <a href="sites" class="btn btn-primary btn-embossed" id="leavePageButton"><span class="fui-check"></span> <?php echo $this->lang->line('modal_pendingchanges_button_leave'); ?></a>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->


    <!-- edit content popup -->
    <div class="modal fade" id="editContentModal" tabindex="-1" role="dialog" aria-hidden="TRUE">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <textarea id="contentToEdit"></textarea>
                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal"><?php echo $this->lang->line('modal_cancelclose'); ?></button>
                    <button type="button" type="button" class="btn btn-primary btn-embossed" id="updateContentInFrameSubmit"><?php echo $this->lang->line('modal_editcontent_updatecontent'); ?></button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <!-- preview popup -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-hidden="TRUE">

        <form action="sites/livepreview" target="_blank" id="markupPreviewForm" method="post" class="form-horizontal">

            <input type="hidden" name="markup" value="" id="markupField2">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                        <h4 class="modal-title" id="myModalLabel"><span class="fui-window"></span> <?php echo $this->lang->line('modal_preview_heading'); ?></h4>
                    </div>

                    <div class="modal-body">
                        <p>
                            <?php echo $this->lang->line('modal_preview_message'); ?>
                        </p>
                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_cancelclose'); ?></button>
                        <button type="submit" class="btn btn-primary btn-embossed"><span class="fui-export"></span> <?php echo $this->lang->line('modal_preview_button2');?></button>
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </form>

    </div><!-- /.modal -->

    <?php if ( $this->session->userdata('user_type') == "Admin") : ?>
        <!-- del template popup -->
        <div class="modal fade" id="delTemplateModal" tabindex="-1" role="dialog" aria-hidden="TRUE">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="TRUE">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                        <h4 class="modal-title" id="myModalLabel"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_deltemplate_heading'); ?></h4>
                    </div>

                    <div class="modal-body">
                        <p>
                            <?php echo $this->lang->line('modal_deltemplate_message'); ?>
                        </p>
                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-embossed" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_cancelclose'); ?></button>
                        <a href="sites/deltempl/<?php echo $siteData['site']->sites_id; ?>" class="btn btn-primary btn-embossed" id="templateDelButton"><span class="fui-check"></span> <?php echo $this->lang->line('modal_deltemplate_button'); ?></a>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->
    <?php endif;?>

    <div class="sandboxes" id="sandboxes" style="display: none"></div>

    <template id="frameCoverPopupTemplate">
        <div class="frameCover forPopup">
            <div class="pull-right divPopupID"></div>
            <div class="pull-right divPopupDelayWrapper">
                <select class="form-control select select-sm pull-right select-default mbl selectPopupDelay" data-toggle="select">
                    <option value="0"><?php echo $this->lang->line('popup_delay');?> 0 <?php echo $this->lang->line('popup_delay_seconds');?></option>
                    <option value="5"><?php echo $this->lang->line('popup_delay');?> 5 <?php echo $this->lang->line('popup_delay_seconds');?></option>
                    <option value="10"><?php echo $this->lang->line('popup_delay');?> 10 <?php echo $this->lang->line('popup_delay_seconds');?></option>
                    <option value="20"><?php echo $this->lang->line('popup_delay');?> 20 <?php echo $this->lang->line('popup_delay_seconds');?></option>
                    <option value="30"><?php echo $this->lang->line('popup_delay');?> 30 <?php echo $this->lang->line('popup_delay_seconds');?></option>
                    <option value="60"><?php echo $this->lang->line('popup_delay');?> 1 <?php echo $this->lang->line('popup_delay_minutes');?></option>
                    <option value="120"><?php echo $this->lang->line('popup_delay');?> 2 <?php echo $this->lang->line('popup_delay_minutes');?></option>
                    <option value="300"><?php echo $this->lang->line('popup_delay');?> 5 <?php echo $this->lang->line('popup_delay_minutes');?></option>
                    <option value="600"><?php echo $this->lang->line('popup_delay');?> 10 <?php echo $this->lang->line('popup_delay_minutes');?></option>
                    <option value="900"><?php echo $this->lang->line('popup_delay');?> 15 <?php echo $this->lang->line('popup_delay_minutes');?></option>
                    <option value="1800"><?php echo $this->lang->line('popup_delay');?> 30 <?php echo $this->lang->line('popup_delay_minutes');?></option>
                </select>
            </div>
            <div class="pull-right divPopupOccurrenceWrapper">
                <select class="form-control select select-sm pull-right select-info mbl selectPopupRecurrence" data-toggle="select">
                    <option value="All"><?php echo $this->lang->line('popup_reocurrance_dropdown_all');?></option>
                    <option value="Once"><?php echo $this->lang->line('popup_reocurrance_dropdown_once');?></option>
                </select>
            </div>
            <button class="btn btn-inverse btn-sm deleteBlock" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_delete'); ?>">
                <i class="fui-trash"></i>
            </button>
            <button class="btn btn-inverse btn-sm resetBlock" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_reset'); ?>">
                <i class="fa fa-refresh"></i>
            </button>
            <button class="btn btn-inverse btn-sm htmlBlock" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_source'); ?>">
                <i class="fa fa-code"></i>
            </button>
            <button class="frameCoverToggle"><span class="fui-gear"></span></button>
        </div>
    </template>

    <template id="frameCoverTemplate">
        <div class="frameCover <?php if ($this->session->userdata('user_type') == 'Admin') {echo "forAdmin";}?>">
            <?php if ($this->session->userdata('user_type') == 'Admin') : ?>
            <div class="btn-group pull-right dropdownCloneInCat">
                <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    <span><?php echo $this->lang->line('sites_framecover_button_clone');?></span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <?php foreach( $blockCategories as $category ):?>
                    <?php if ( $category['blocks_categories_id'] != 1 ):?>
                    <li><a href="#" data-cat-id="<?php echo $category['blocks_categories_id']?>"><?php echo $category['category_name'];?></a></li>
                    <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
            <button class="btn btn-info btn-sm buttonDelOriginal" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_admin_delete'); ?>" data-saving="<?php echo $this->lang->line('sites_framecover_title_admin_delete_working'); ?>" data-confirmation="<?php echo $this->lang->line('sites_framecover_title_admin_delete_confirmed');?>">
                <i class="fui-trash"></i>
            </button>
            <button class="btn btn-info btn-sm buttonSaveOriginal" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_admin_save'); ?>" data-saving="<?php echo $this->lang->line('sites_framecover_title_admin_save_saving'); ?>" data-confirmation="<?php echo $this->lang->line('sites_framecover_title_admin_save_confirmed'); ?>">
                <i class="fui-check-circle"></i>
            </button>
            <?php endif;?>
            <button class="btn btn-inverse btn-sm deleteBlock" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_delete'); ?>">
                <i class="fui-trash"></i>
            </button>
            <button class="btn btn-inverse btn-sm resetBlock" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_reset'); ?>">
                <i class="fa fa-refresh"></i>
            </button>
            <button class="btn btn-inverse btn-sm htmlBlock <?php if ($this->session->userdata('user_type') !== 'Admin' && $package['edit_sourcecode'] !== 'yes') {echo 'disabled';} ?>" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_source'); ?>">
                <i class="fa fa-code"></i>
            </button>
            <button class="btn btn-inverse btn-sm favBlock" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_favourite'); ?>" data-saving="<?php echo $this->lang->line('sites_framecover_title_favourite_saving'); ?>" data-confirmation="<?php echo $this->lang->line('sites_framecover_title_favourite_confirmed'); ?>">
                <i class="fa fa-star-o"></i>
            </button>
            <button class="btn btn-inverse btn-sm dragBlock" type="button" data-toggle="tooltip" title="<?php echo $this->lang->line('sites_framecover_title_drag'); ?>">
                <i class="fa fa-arrows"></i>
            </button>
            <label class="checkbox primary">
                <input type="checkbox" data-toggle="checkbox" class="custom-checkbox">
                <?php echo $this->lang->line('sites_framecover_global'); ?>
            </label>
            <button class="frameCoverToggle"><span class="fui-gear"></span></button>
        </div>
    </template>

    <template id="templateParallaxInfo">
        <div class="alert alert-info">
            <button class="close fui-cross" data-dismiss="alert"></button>
            <p>
                <?php echo $this->lang->line('sites_parallax_info'); ?>
            </p>
        </div>
    </template>

    <template id="sourceEditorButtons">
        <button class="btn btn-danger editCancelButton btn-sm" id="editCancelButton"><i class="fui-cross"></i> <span><?php echo $this->lang->line('sites_sourcecode_editor_button_cancel');?></span></button>
        <button class="btn btn-primary editSaveButton btn-sm" id="editSaveButton"><i class="fui-check"></i> <span><?php echo $this->lang->line('sites_sourcecode_editor_button_save');?></span></button>
    </template>

    <!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
        <script src="<?php echo base_url('build/builder.bundle.js') . '?' . filemtime( FCPATH . 'build/builder.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
        <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/builder.bundle.js"></script>
    <?php endif; ?>
</body>
</html>
