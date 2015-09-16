<?php

	define('IS_SSL', isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']));
	define("BASE", sprintf("%s://%s%s%s/", 
		IS_SSL ? 'https' : 'http',
		$_SERVER['SERVER_NAME'],
		(IS_SSL && $_SERVER['SERVER_PORT'] != 443) || (!IS_SSL && $_SERVER['SERVER_PORT'] != 80) ? ':' . $_SERVER['SERVER_PORT'] : '',
		strrstr(strrstr(strrstr(strrstr(strrstr($_SERVER['REQUEST_URI'], '/', true), '/', true), '/', true), '/', true), '/', true)
	));
	define("TL_ROOT", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define("JQUERY_SRC", 'assets/jquery/core/' . reset((scandir(TL_ROOT . '/assets/jquery/core', 1))) . '/jquery.min.js');


	function strrstr($haystack, $needle, $before = false) {
		$rpos = strrpos($haystack, $needle);
		if($rpos === false) 
			return false;
		if($before == false) 
			return substr($haystack, $rpos);
		return substr($haystack, 0, $rpos);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Position von Karte verwenden</title>
		<base href="<?php echo BASE; ?>" />
		<script type="text/javascript" src="<?php echo JQUERY_SRC; ?>"></script>
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
		<script type="text/javascript" src="system/modules/nc_geotagging/assets/jquery.geocomplete.min.js"></script>
		<script type="text/javascript">
			var targetLat, targetLon, targetAdress;
			$(function(){
				targetLat = $('#<?php echo $_REQUEST['lat_id']; ?>', parent.document);
				targetLon = $('#<?php echo $_REQUEST['lon_id']; ?>', parent.document);
				targetAddress = $('#<?php echo $_REQUEST['address_id']; ?>', parent.document);
				$("#geocomplete").geocomplete({
					map:           ".map_canvas",
					details:       "form",
					markerOptions: {
						draggable: true
					}
				});
				$("#geocomplete").bind("geocode:dragged", function(event, latLng){
					$("input[name=lat]").val(latLng.lat());
					$("input[name=lng]").val(latLng.lng());
					targetLat.val(latLng.lat());
					targetLon.val(latLng.lng());
					$("#geocomplete").geocomplete("find", latLng.toString());
				}).bind("geocode:result", function(event, results){
					targetLat.val(results.geometry.location.lat());
					targetLon.val(results.geometry.location.lng());
					targetAddress.val(results.formatted_address);
				});
				$("#find").click(function(){
					$("#geocomplete").trigger("geocode");
				});
				if (targetLat.val() > 0 && targetLon.val() > 0)
				{
					$("#geocomplete").geocomplete("find", targetLat.val() + "," + targetLon.val());
				} else {
					$("#geocomplete").geocomplete("find", 'Europa');
				}
			});
		</script>
		<style type="text/css">
			body {
				font-family:Verdana, Geneva, sans-serif;
			}
			.map_canvas {
				width: 100%;
				height: 400px;
				margin: 10px 0;
				position: relative; 
				background-color: rgb(229, 227, 223); 
				overflow: hidden;
				-webkit-transform: translateZ(0);
			}
			#geocomplete {
				width: 760px;
			}
			#find{
				width: 70px;
			}
			fieldset {
				border: 0;
			}
			label {
				width: 120px;
				float: left;
				display: inline-block;
				float: left;
				padding-top: 2px;
			}
			fieldset input {
				width: 150px;
				float: left;
			}
			fieldset input.long {
				width: 690px;
			}
		</style>
	</head>
	<body>
		<form>
			<input id="geocomplete" type="text" placeholder="Geben Sie eine Adresse ein" value="" autocomplete="off">
			<input id="find" type="button" value="Suchen">
			
			<div class="map_canvas"></div>
			
			<fieldset>
				<label>Breitengrad</label>
				<input name="lat" type="text" value="">
				<br style="clear:both;" />
				
				<label>Längengrad</label>
				<input name="lng" type="text" value="">
				<br style="clear:both;" />
				
				<label>Adresse</label>
				<input name="formatted_address" class="long" type="text" value="">
				<br style="clear:both;" />
			</fieldset>
		</form>
	</body>
</html>