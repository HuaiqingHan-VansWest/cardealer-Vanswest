<?php
require_once trailingslashit( CARDEALER_INC_PATH ) . 'cardealer-templates/class-cardealer-templates.php';

function cardealer_template() {
	return CarDealer_Template::instance();
}
