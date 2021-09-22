function verificar_despacho(latlng){
	var result = {status:'error',key:'fixedMsg',msg:'Los datos enviados para la verificación no son correctos.'};
	if(polygons.length == 0){
		result = {status:'error',key:'fixedMsg',msg:'No se pudo realizar la verificación.'};
		return result;
	}

	var query;

	$.each(polygons,function(i,polygon){
		query = google.maps.geometry.poly.containsLocation( latlng, polygon ) ? true : false;
		if (query) return false;
    });					
	
	if (query) result = {status:'success',key:'existeDespacho'};
	if (!query) result = {status:'error',key:'noExisteDespacho'};
	return result;
}