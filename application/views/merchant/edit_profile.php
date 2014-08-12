<div class="container">
    <?= $breadcrumbs ?>
</div>
<div class="gap"></div>
<div class="merchant-body left clearfix">

    <div class="hold right">
        <h1>Edit your Profile</h1>

        <div class="alert-error form-alert">
            <?php echo validation_errors(); ?>
        </div>
        <?php if (!empty($success_msg)) { ?>
            <div class='alert form-alert alert-success'><p><?= $success_msg ?></p></div>
        <?php } ?>
        <?php if (!empty($error_msg)) { ?>
            <div class='alert form-alert alert-error'><p><?= $error_msg ?></p></div>
        <?php } ?>

        <form action="<?= base_url(Merchant::MERCHANT_URL . '/edit-profile') ?>" method="post">
            <?php
            $values = $profile;
            /*
              $keys = array();
              $values = array();
              array_keys($keys);
              array_values($values);

             *
             */
            ?>
            <?php
            foreach ($values as $key => $value) {
                if ($key == 'Short Description' || $key == 'Opening Hours') {
                    ?>
                    <label><?= $key ?></label>
                    <textarea name="<?= strtolower(str_ireplace(' ', '_', $key)); ?>" placeholder="<?= $key ?>" class="span12" cols="6">
                        <?= $value ?>
                    </textarea>
                    <?php
                } else {
                    ?>
                    <label><?= $key ?></label>
                    <input type="text" name="<?= strtolower(str_ireplace(' ', '_', $key)); ?>" placeholder="<?= $key ?>" value="<?= $value ?>" class="span12">
                    <?php
                }
            }
            ?>
            <input type="submit" value="Save" class="btn btn-primary">
        </form>

    </div>
    <?php
    echo partial('partials/_merchant_footer', array('year' => time('y')));
    ?>
</div>


<div class="merchant-body right">
    <div class="hold">
        <h2>Create your Profile</h2>
        <p>Complete your personal details and that of your business. This information is makes you look more reliable and commands trust and comfort in users who are interested in your business discounts.</p>
        <p>It also makes it easy for us to communicate with you regarding any business issues and make deposits to your bank account as promised without any hitch.</p>
    </div>
</div>
