<div class="merchant-body left clearfix">

    <div class="hold right">
        <div class="clearfix export-header">
            <h1 class="left">My Coupons</h1>

            <a href="<?= base_url('/merchant/add-coupon'); ?>" class="export right btn">Add Coupon</a>
        </div>

        <div class="cart-order tableau segment">

            <ul class="stats-summ clearfix">
                <li><?php
                        $items = $coupons->items();
                        if (count($items) == 1 && property_exists($items[0], 'empty')) {
                            echo 0;
                        } else {
                            echo count($items);
                        }
                    ?> <span>Coupons In all</span>
                </li>
                <li>₦-- <span>In Total Sales</span></li>
                <li>₦-- <span>Net Sales</span></li>
            </ul>

            <?= partial('partials/merchant/_display_coupons_table', array('coupons' => $coupons)); ?>
        </div>

    </div>
    <?php
        echo partial('partials/_merchant_footer', array('year' => time('y')));
    ?>
</div>


<div class="merchant-body right">
    <div class="hold">
        <h2>Create your Profile</h2>

        <p>Complete your personal details and that of your business. This information is makes you look more reliable
            and commands trust and comfort in users who are interested in your business discounts.</p>

        <p>It also makes it easy for us to communicate with you regarding any business issues and make deposits to your
            bank account as promised without any hitch.</p>
    </div>
</div>

