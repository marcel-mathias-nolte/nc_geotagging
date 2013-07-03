
var NCGEO = {
	finished: false,
	success: false,
	msg: '',
	err: 0,
	lat: 0,
	lon: 0,
	init: function(callback) {
		if (navigator.geolocation) {
			// get current location on startup (cos this may take a while)
			navigator.geolocation.getCurrentPosition(
				function(location) {
					NCGEO.lat = location.coords.latitude;
					NCGEO.lon = location.coords.longitude;
					NCGEO.finished = NCGEO.success = true;
					if (typeof callback !== "undefined") {
						callback();
					}
				},
				function(err) {
					NCGEO.finished = true;
					NCGEO.success = false;
					if(err.code == 1) {
						NCGEO.msg = 'Error: Access is denied!';
					} else if( err.code == 2) {
						NCGEO.msg = 'Error: Position is unavailable!';
					}
					NCGEO.err = err.code;
					if (typeof callback !== "undefined") {
						callback();
					}
				},
				{
					timeout: 60000
				}
			);
		} else {
			NCGEO.finished = true;
			NCGEO.success = false;
			NCGEO.msg = 'Error: No GPS available!';
			if (typeof callback !== "undefined") {
				callback();
			}
		}
	},
	copyCurrentLocationToWidget: function(id) {
		NCGEO.init(
			function() { 
				if (NCGEO.success) { 
					$('ctrl_' + id + '_lat').value = NCGEO.lat; 
					$('ctrl_' + id + '_lon').value = NCGEO.lon; 
				} else { 
					alert(NCGEO.msg); 
				} 
			}
		); 
		return false;
	},
	openMapBox: function(id, title) {
		Backend.openModalIframe(
			{
				width: 860, 
				height: 610,
				title: 'Position von Karte verwenden', 
				url:   'system/modules/nc_geotagging/assets/map.php' + 
					   '?lat_id=' + encodeURI('ctrl_' + id + '_lat') +
					   '&lon_id=' + encodeURI('ctrl_' + id + '_lon') +
					   '&address_id=' + encodeURI('ctrl_' + id + '_address')
			}
		); 
		return false;
	}
}