<?php
add_action( 'before_image_gallery_slider', 'cardealer_vehicle_sold_label', 10 );
add_action( 'after_image_gallery_slider', 'cardealer_vehicle_image_gallery_video_button', 10 );
get_template_part( 'template-parts/cars/single-car/car-image' );
