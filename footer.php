
<footer id="footer">
    <div class="container">
        <div class="col-md-12">
            <?php
            $args = array(
                'theme_location'  => 'menu_footer', 
                'container'       => false,
                'container_class' => 'menu-footer',
                'menu_class'      => 'nav nav-pills',
            );
            wp_nav_menu($args);
            ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
