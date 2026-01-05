</main><!-- #main -->

<footer class="site-footer">
    <div class="container">
        <?php 
        $social_enabled = get_theme_mod('soplos_footer_enable_social', true);
        $social_pos = get_theme_mod('soplos_footer_social_position', 'bottom_bar');
        $footer_cols = intval(get_theme_mod('soplos_footer_columns', 3));

        // Prepare Social HTML Logic (Reuse Header Helper Logic if possible or rebuild)
        // Since soplos_get_social_links() is helper, verify if it exists or reuse raw logic
        // We will reuse the logic from header.php which is robust now or create a cleaner block here.
        ob_start();
        ?>
        <div class="social-icons footer-social">
            <?php
            // Retrieve Sort Order (Consistent with Header)
            $default_order = 'facebook,twitter,instagram,youtube,linkedin,tiktok,twitch,github,discord,telegram,paypal,mastodon,rss';
            $order_list = explode(',', get_theme_mod('soplos_social_order', $default_order));

            // Define Icons Map
            $icons_map = array(
                'facebook'  => 'fab fa-facebook',
                'twitter'   => 'fab fa-twitter',
                'instagram' => 'fab fa-instagram',
                'youtube'   => 'fab fa-youtube',
                'linkedin'  => 'fab fa-linkedin',
                'tiktok'    => 'fab fa-tiktok',
                'twitch'    => 'fab fa-twitch',
                'github'    => 'fab fa-github',
                'discord'   => 'fab fa-discord',
                'telegram'  => 'fab fa-telegram',
                'paypal'    => 'fab fa-paypal',
                'mastodon'  => 'fab fa-mastodon',
                'rss'       => 'fas fa-rss',
            );

            foreach ($order_list as $network) {
                $network = trim($network);
                if (!array_key_exists($network, $icons_map)) continue;

                $url = get_theme_mod("soplos_{$network}_url", '');
                if (!empty($url)) {
                    echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr(ucfirst($network)) . '">';
                    echo '<i class="' . esc_attr($icons_map[$network]) . '"></i>';
                    echo '</a>';
                }
            }
            ?>
        </div>
        <?php
        $social_html = ob_get_clean();
        ?>

        <?php if ($social_enabled && $social_pos === 'above_widgets') : ?>
            <div class="footer-social-wrapper above-widgets">
                <?php echo $social_html; ?>
            </div>
        <?php endif; ?>

        <?php if (get_theme_mod('soplos_show_footer_widgets', true)) : ?>
        <div class="footer-widgets-grid" style="display: grid; grid-template-columns: repeat(<?php echo $footer_cols; ?>, 1fr); gap: 30px;">
            <?php for ($i = 1; $i <= $footer_cols; $i++) : ?>
                <div class="footer-column footer-column-<?php echo $i; ?>">
                    <?php if (is_active_sidebar('footer-' . $i)) : ?>
                        <?php dynamic_sidebar('footer-' . $i); ?>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        
        <div class="footer-bottom">
            <?php
            // Footer Logo
            $footer_logo = get_theme_mod('soplos_footer_logo', '');
            if (!empty($footer_logo)) : ?>
            <div class="footer-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url($footer_logo); ?>" alt="<?php bloginfo('name'); ?>">
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ($social_enabled && $social_pos === 'bottom_bar') : ?>
                <?php echo $social_html; ?>
            <?php endif; ?>
            
            <?php if (get_theme_mod('soplos_show_footer_credits', true)) : ?>
            <p><?php echo esc_html(get_theme_mod('soplos_copyright_text', '© ' . date('Y') . ' SoplosLinux. All rights reserved.')); ?></p>
            <?php endif; ?>
            
            <div class="legal-links">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ));
                ?>
            </div>
        </div>
    </div>
</footer>

<?php if (get_theme_mod('soplos_back_to_top', true)) : ?>
<button class="back-to-top" id="backToTop" aria-label="<?php esc_attr_e('Back to top', 'soplos'); ?>">
    <i class="fas fa-chevron-up"></i>
</button>
<?php endif; ?>



<?php wp_footer(); ?>
</body>
</html>
