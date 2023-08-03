<?php $this->load->view("shared/header.php"); ?>

<body>
<div class="container">
    <h2 class="text-center">Verifier Code</h2>
    <form>
        <div class="form-group">
            <input type="text" value="<?= isset($_GET['code'])?$_GET['code']:"" ?>" placeholder="Verifier Code" name="code" class="form-control">
        </div>
    </form>
</div>
<!-- Load JS here for greater good =============================-->
<?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/integrations.bundle.js') . '?' . filemtime( FCPATH . 'build/integrations.bundle.js'); ?>"></script>
<?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/integrations.bundle.js"></script>
<?php endif; ?>

</body>
</html>