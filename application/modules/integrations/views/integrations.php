<?php $this->load->view("shared/header.php"); ?>

<body>

	<?php $this->load->view("shared/nav.php"); ?>

	<div class="container-fluid">

		<div class="row">

			<div class="col-md-9 col-sm-8">
				<h1><span class="fui-gear"></span> <?php echo $this->lang->line('integrations_heading'); ?></h1>
			</div><!-- /.col -->

			<div class="col-md-3 col-sm-4 text-right">

			</div><!-- /.col -->

		</div><!-- /.row -->

		<hr class="dashed margin-bottom-30">

		<div class="row">

			<div class="col-md-3">

				<div class="integration card">
					<div class="head" style="background-image: url('<?php echo base_url('img/logos/Mailchimp_Logo-Horizontal_Black.png')?>')"></div>

					<div class="body">
						<b>Mailchimp</b>
                        <div class="mailchimp_label">
                            <?php if(isset($mailchimp)):?>
                                 <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                 <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
						<div class="buttons">
                            <?php if(isset($mailchimp)):?>
							    <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="Mailchimp" data-template="templateMailchimpForm"><?php echo $this->lang->line('integrations_button_update');?></a>
                            <?php else :?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="Mailchimp" data-template="templateMailchimpForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
                            <?php endif; ?>
                        </div>
						<template id="templateMailchimpForm">
                            <input type="hidden" name="api_name" class="api_name" value="mailchimp" data-id="<?= (isset($mailchimp) ? $mailchimp->id : '') ?>">
                            <div class="form-group">
            					<input type="email" value="<?= (isset($mailchimp_configs->email) ? $mailchimp_configs->email : '') ?>" placeholder="Email" name="email" class="conf_email form-control">
	          				</div>
	          				<div class="form-group">
	            				<input type="text" value="<?= (isset($mailchimp_configs->api_key) ? $mailchimp_configs->api_key : '') ?>" placeholder="API Key" name="api_key" class="conf_api_key form-control">
	          				</div>
						</template>

					</div>

				</div>
				
			</div><!-- /.col -->

			<div class="col-md-3">
				<div class="integration card">
					<div class="head" style="background-image: url('<?php echo base_url('img/logos/Infusionsoft.png')?>')">
					</div>
					<div class="body">
						<b>Infusionsoft</b>
                        <div class="infusionsoft_label">
                            <?php if(isset($infusionsoft) && $infusionsoft->configured == 1):?>
                                <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
                        <div class="buttons">
                            <?php if(isset($aweber) && $aweber->configured == 1):?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="Infusionsoft" data-template="templateInfusionsoftForm"><?php echo $this->lang->line('integrations_button_update');?></a>
                            <?php else :?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="Infusionsoft" data-template="templateInfusionsoftForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
                            <?php endif; ?>
                        </div>

						<template id="templateInfusionsoftForm">
                            <input type="hidden" name="api_name" class="api_name" value="infusionsoft" data-id="<?= (isset($infusionsoft) ? $infusionsoft->id : '') ?>">
                            <div class="form-group">
            				<input type="email" name="email" value="<?= (isset($infusionsoft_configs->email) ? $infusionsoft_configs->email : '') ?>" placeholder="Email" class="form-control">
	          				</div>
	          				<div class="form-group">
	            				<input type="text" name="clientId" value="<?= (isset($infusionsoft_configs->clientId) ? $infusionsoft_configs->clientId : '') ?>" placeholder="Client Id" class="form-control">
	          				</div>
	          				<div class="form-group">
	            				<input type="text" name="clientSecret" value="<?= (isset($infusionsoft_configs->clientSecret) ? $infusionsoft_configs->clientSecret : '') ?>" placeholder="Client Secret" class="form-control">
	          				</div>
						</template>
					</div>
				</div>
			</div><!-- /.col -->

			<div class="col-md-3" id="CampaignMonitorForm">
				<div class="integration card">
					<div class="head" style="background-image: url('<?php echo base_url('img/logos/CampaignMonitor.png')?>')">
					</div>
					<div class="body">
						<b>Campaign Monitor</b>
                        <div class="campaign_monitor_label">
                            <?php if(isset($campaign_monitor) && $campaign_monitor->configured == 1):?>
                                <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
						<div class="buttons">
							<a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="Campaign Monitor" data-template="templateCampaignMonitorForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
						</div>
						<template id="templateCampaignMonitorForm">
                            <input type="hidden" name="api_name" class="api_name" value="campaign_monitor" data-id="<?= (isset($campaign_monitor) ? $campaign_monitor->id : '') ?>">
                            <div class="form-group">
            				<input type="email" name="email" value="<?= (isset($campaign_monitor_configs->email) ? $campaign_monitor_configs->email : '') ?>" placeholder="Email" class="form-control">
	          				</div>
                            <div class="form-group">
                                <input type="text" name="api_key" value="<?= (isset($campaign_monitor_configs->api_key) ? $campaign_monitor_configs->api_key : '') ?>" placeholder="Api Key" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="client_id" value="<?= (isset($campaign_monitor_configs->clientId) ? $campaign_monitor_configs->clientId : '') ?>" placeholder="Client Id" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="client_oauth_id" value="<?= (isset($campaign_monitor_configs->clientOauthId) ? $campaign_monitor_configs->clientOauthId : '') ?>" placeholder="Client OAuth Id" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="client_secret" value="<?= (isset($campaign_monitor_configs->clientSecret) ? $campaign_monitor_configs->clientSecret : '') ?>" placeholder="Client Secret" class="form-control">
                            </div>
						</template>
					</div>
				</div>
			</div><!-- /.col -->

            <div class="col-md-3">
                <div class="integration card">
                    <div class="head" style="background-image: url('<?php echo base_url('img/logos/GetResponse.png')?>')">
                    </div>
                    <div class="body">
                        <b>Get Response</b>
                        <div class="getresponse_label">
                            <?php if(isset($getresponse) && $getresponse->configured == 1):?>
                                <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
                        <div class="buttons">
                            <?php if(isset($getresponse) && $getresponse->configured == 1):?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="Get Response" data-template="templateGetresponseForm"><?php echo $this->lang->line('integrations_button_update');?></a>
                            <?php else :?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="Get Response" data-template="templateGetresponseForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
                            <?php endif; ?>
                        </div>
                        <template id="templateGetresponseForm">
                            <input type="hidden" name="api_name" class="api_name" value="getresponse" data-id="<?= (isset($getresponse) ? $getresponse->id : '') ?>">
                            <div class="form-group">
                                <input type="email" name="email" value="<?= (isset($getresponse_configs->email) ? $getresponse_configs->email : '') ?>" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="api_key" value="<?= (isset($getresponse_configs->api_key) ? $getresponse_configs->api_key : '') ?>" placeholder="Api Key" class="form-control">
                            </div>

                        </template>
                    </div>
                </div>
            </div><!-- /.col -->

            <div class="col-md-3">
                <div class="integration card">
                    <div class="head" style="background-image: url('<?php echo base_url('img/logos/gotowebinar.png')?>')">
                    </div>
                    <div class="body">
                        <b>GoToWebinar</b>
                        <div class="gotowebinar_label">
                            <?php if(isset($gotowebinar) && $gotowebinar->configured == 1):?>
                                <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
                        <div class="buttons">
                            <?php if(isset($gotowebinar) && $gotowebinar->configured == 1):?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="GoToWebinar" data-template="templateGoToWebinarForm"><?php echo $this->lang->line('integrations_button_update');?></a>
                            <?php else :?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="GoToWebinar" data-template="templateGoToWebinarForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
                            <?php endif; ?>
                        </div>
                        <template id="templateGoToWebinarForm">
                            <input type="hidden" name="api_name" class="api_name" value="gotowebinar" data-id="<?= (isset($gotowebinar) ? $gotowebinar->id : '') ?>">
                            <div class="form-group">
                                <input type="email" name="email" value="<?= (isset($gotowebinar_configs->email) ? $gotowebinar_configs->email : '') ?>" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="consumer_key" value="<?= (isset($gotowebinar_configs->consumer_key) ? $gotowebinar_configs->consumer_key : '') ?>" placeholder="Consumer Key" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="consumer_secret" value="<?= (isset($gotowebinar_configs->consumer_secret) ? $gotowebinar_configs->consumer_secret : '') ?>" placeholder="Consumer Secret" class="form-control">
                            </div>
                        </template>
                    </div>
                </div>
            </div><!-- /.col -->

            <div class="col-md-3">
                <div class="integration card">
                    <div class="head" style="background-image: url('<?php echo base_url('img/logos/activecampaign.png')?>')">
                    </div>
                    <div class="body">
                        <b>ActiveCampaign</b>
                        <div class="activecampaign_label">
                            <?php if(isset($activecampaign) && $activecampaign->configured == 1):?>
                                <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
                        <div class="buttons">
                            <?php if(isset($activecampaign) && $activecampaign->configured == 1):?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="ActiveCampaign" data-template="templateActiveCampaignForm"><?php echo $this->lang->line('integrations_button_update');?></a>
                            <?php else :?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="ActiveCampaign" data-template="templateActiveCampaignForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
                            <?php endif; ?>
                        </div>
                        <template id="templateActiveCampaignForm">
                            <input type="hidden" name="api_name" class="api_name" value="activecampaign" data-id="<?= (isset($activecampaign) ? $activecampaign->id : '') ?>">
                            <div class="form-group">
                                <input type="email" name="email" value="<?= (isset($activecampaign_configs->email) ? $activecampaign_configs->email : '') ?>" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="api_key" value="<?= (isset($activecampaign_configs->api_key) ? $activecampaign_configs->api_key : '') ?>" placeholder="Api Key" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="api_url" value="<?= (isset($activecampaign_configs->api_url) ? $activecampaign_configs->api_url : '') ?>" placeholder="Api URL" class="form-control">
                            </div>
                        </template>
                    </div>
                </div>
            </div><!-- /.col -->

            <div class="col-md-3">
                <div class="integration card">
                    <div class="head" style="background-image: url('<?php echo base_url('img/logos/WebinarJam.png')?>')">
                    </div>
                    <div class="body">
                        <b>WebinarJam</b>
                        <div class="webinarjam_label">
                            <?php if(isset($webinarjam) && $webinarjam->configured == 1):?>
                                <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
                        <div class="buttons">
                            <?php if(isset($webinarjam) && $webinarjam->configured == 1):?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="WebinarJam" data-template="templateWebinarJamForm"><?php echo $this->lang->line('integrations_button_update');?></a>
                            <?php else :?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="WebinarJam" data-template="templateWebinarJamForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
                            <?php endif; ?>
                        </div>
                        <template id="templateWebinarJamForm">
                            <input type="hidden" name="api_name" class="api_name" value="webinarjam" data-id="<?= (isset($webinarjam) ? $webinarjam->id : '') ?>">
                            <div class="form-group">
                                <input type="email" name="email" value="<?= (isset($webinarjam_configs->email) ? $webinarjam_configs->email : '') ?>" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="api_key" value="<?= (isset($webinarjam_configs->api_key) ? $webinarjam_configs->api_key : '') ?>" placeholder="Api Key" class="form-control">
                            </div>
                        </template>
                    </div>
                </div>
            </div><!-- /.col -->

            <div class="col-md-3">
                <div class="integration card">
                    <div class="head" style="background-image: url('<?php echo base_url('img/logos/EverWebinar.png')?>')">
                    </div>
                    <div class="body">
                        <b>EverWebinar</b>
                        <div class="everwebinar_label">
                            <?php if(isset($everwebinar) && $everwebinar->configured == 1):?>
                                <span class="label label-success"><?php echo $this->lang->line('integrations_label_configured');?></span>
                            <?php else :?>
                                <span class="label label-default"><?php echo $this->lang->line('integrations_label_not_configured');?></span>
                            <?php endif; ?>
                        </div>
                        <div class="buttons">
                            <?php if(isset($everwebinar) && $everwebinar->configured == 1):?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="EverWebinar" data-template="templateEverWebinarForm"><?php echo $this->lang->line('integrations_button_update');?></a>
                            <?php else :?>
                                <a href="#modalConfigIntegration" data-toggle="modal" class="btn btn-primary btn-sm btn-block buttonConfigureIntegration" data-integration="EverWebinar" data-template="templateEverWebinarForm"><?php echo $this->lang->line('integrations_button_configure');?></a>
                            <?php endif; ?>
                        </div>
                        <template id="templateEverWebinarForm">
                            <input type="hidden" name="api_name" class="api_name" value="everwebinar" data-id="<?= (isset($everwebinar) ? $everwebinar->id : '') ?>">
                            <div class="form-group">
                                <input type="email" name="email" value="<?= (isset($everwebinar_configs->email) ? $everwebinar_configs->email : '') ?>" placeholder="Email" class="form-control">
                            </div>
                            <div class="form-group">
                                <input type="text" name="api_key" value="<?= (isset($everwebinar_configs->api_key) ? $everwebinar_configs->api_key : '') ?>" placeholder="Api Key" class="form-control">
                            </div>
                        </template>
                    </div>
                </div>
            </div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container -->

	<!-- Modal -->

	<?php $this->load->view("shared/modal_account.php"); ?>

	<div class="modal" id="modalConfigIntegration">
  		<div class="modal-dialog">
    		<div class="modal-content">

      			<div class="modal-header">
        			<button type="button" class="close fui-cross" data-dismiss="modal" aria-hidden="true"></button>
        			<h3 class="modal-title"><?php echo $this->lang->line('integrations_head_configure');?> <span id="spanIntegrationName">Mailchimp</span> <?php echo $this->lang->line('integrations_head_integration');?></h3>
      			</div>

      			<div class="modal-body">
        			<div class="modal-alerts"></div>
      				<form id="formIntegration">
      					<div id="divIntegrationFormInner"></div>
      				</form>
      			</div>

      			<div class="modal-footer">
        			<button class="btn btn-default btn-wide" data-dismiss="modal"><?php echo $this->lang->line('integrations_button_cancel');?></button>
        			<button class="btn btn-primary btn-wide" id="buttonSaveIntegration"><?php echo $this->lang->line('integrations_button_save');?></button>
      			</div>

    		</div>
  		</div>
	</div>
	<!-- /modals -->

	<!-- Load JS here for greater good =============================-->
	<?php if (ENVIRONMENT == 'production') : ?>
		<script src="<?php echo base_url('build/integrations.bundle.js') . '?' . filemtime( FCPATH . 'build/integrations.bundle.js'); ?>"></script>
	<?php elseif (ENVIRONMENT == 'development') : ?>
		<script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/integrations.bundle.js"></script>
	<?php endif; ?>

</body>
</html>