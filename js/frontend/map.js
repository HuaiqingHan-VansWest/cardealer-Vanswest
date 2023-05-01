(function( $ ) {

/**
 * new_map
 *
 * Renders a Google Map onto the selected jQuery element
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @return  object The map instance.
 */
function initMap( $el ) {

	// Find marker elements within map.
	var $markers = $el.find('.marker');

	var zm = 10;
	if ( cardealer_map_obj.zoom ) {
		zm = parseInt(cardealer_map_obj.zoom);
	}

	// Create gerenic map.
	var mapArgs = {
		zoom		: $el.data('zoom') || zm,
		center		: new google.maps.LatLng(0, 0),
		mapTypeId	: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map( $el[0], mapArgs );

	// Add markers.
	map.markers = [];
	$markers.each(function(){
		initMarker( $(this), map );
	});

	// Center map based on markers.
	centerMap( map );

	// Return map instance.
	return map;
}

/**
 * initMarker
 *
 * Creates a marker for the given jQuery element and map.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   jQuery $el The jQuery element.
 * @param   object The map instance.
 * @return  object The marker instance.
 */
function initMarker( $marker, map ) {

	// Get position from marker.
	var lat  = $marker.data('lat');
	var lng  = $marker.data('lng');
	var zoom = $marker.data('zoom');
	var latLng = {
		lat: parseFloat( lat ),
		lng: parseFloat( lng )
	};

	// var latlng = new google.maps.LatLng( $marker.data('lat'), $marker.data('lng') );
	// Create marker instance.
	var marker = new google.maps.Marker({
		position : latLng,
		map: map,
		zoom: zoom,
	});

	// Append to reference for later use.
	map.markers.push( marker );

	// If marker contains HTML, add it to an infoWindow.
	if( $marker.html() ){

		// Create info window.
		var infowindow = new google.maps.InfoWindow({
			content: $marker.html()
		});

		// Show info window when marker is clicked.
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open( map, marker );
		});
	}
}

/**
 * centerMap
 *
 * Centers the map showing all markers in view.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   object The map instance.
 * @return  void
 */
function centerMap( map ) {

	// Create map boundaries from all map markers.
	var bounds = new google.maps.LatLngBounds();
	$.each( map.markers, function( i, marker ){
		var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
		bounds.extend( latlng );
	});
	var zm = 10;
	if ( cardealer_map_obj.zoom ) {
		zm = parseInt(cardealer_map_obj.zoom);
	}

	// Case: Single marker.
	if( map.markers.length == 1 ){
		var zoom = map.markers[0].zoom || zm;
		map.setCenter( bounds.getCenter() );
		map.setZoom( zoom );

	// Case: Multiple markers.
	} else {
		map.fitBounds( bounds );
	}
}

// Render maps on page load.
var map = null;
$(document).ready(function(){
	$('.acf-map').each(function(){
		// create map
		map = initMap( $(this) );
	});
	$('.cd-tab-map').on('click',function(){
		google.maps.event.trigger(map, 'resize');
		centerMap(map);
	});
});

})(jQuery);
