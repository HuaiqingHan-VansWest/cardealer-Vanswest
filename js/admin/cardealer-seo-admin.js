/* global YoastSEO */
class CarDealerYoastSEO {
	constructor() {
		// Ensure YoastSEO.js is present and can access the necessary features.
		if ( typeof YoastSEO === "undefined" || typeof YoastSEO.analysis === "undefined" || typeof YoastSEO.analysis.worker === "undefined" ) {
			return;
		}

		YoastSEO.app.registerPlugin( "CarDealerYoastSEO", { status: "ready" } );
		
		this.registerModifications();
	}

	/**
	 * Registers the addContent modification.
	 *
	 * @returns {void}
	 */
	registerModifications() {
		const callback = this.addContent.bind( this );

		// Ensure that the additional data is being seen as a modification to the content.
		YoastSEO.app.registerModification( "content", callback, "CarDealerYoastSEO", 10 );
	}

	/**
	 * Adds to the content to be analyzed by the analyzer.
	 *
	 * @param {string} data The current data string.
	 *
	 * @returns {string} The data string parameter with the added content.
	 */
	addContent( data ) {
		var vehicle_overview = acf.getField('field_588f10e6bbe3b').val();
		if ( '' !== vehicle_overview ) {
			data += vehicle_overview;
		}
		
		var technical_specifications = acf.getField('field_588f185e77748').val();
		if ( '' !== technical_specifications ) {
			data += technical_specifications;
		}
		
		var general_information      = acf.getField('field_588f1902df0e9').val();
		if ( '' !== general_information ) {
			data += general_information;
		}

		return data ;
	}
}
/**
 * Adds eventlistener to load the plugin.
 */
if ( typeof YoastSEO !== "undefined" && typeof YoastSEO.app !== "undefined" ) {
	new CarDealerYoastSEO();
} else {
	jQuery( window ).on(
		"YoastSEO:ready",
		function() {
			new CarDealerYoastSEO();
		}
	);
}