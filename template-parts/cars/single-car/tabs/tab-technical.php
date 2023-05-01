<?php
echo do_shortcode( wpautop( get_post_meta( get_the_ID(), 'technical_specifications', true ) ) );
