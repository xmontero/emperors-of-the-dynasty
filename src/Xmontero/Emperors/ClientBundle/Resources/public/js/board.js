// Tile details.

$( '.tile' ).click( function(){
	data = $( this ).children( '.object' );
	tile = data.data( 'tile' );
	
	tableContent = '<tr><th>Key</th><th>Value</th></tr>';
	
	for( var key in tile )
	{
		value = tile[ key ];
		tableContent = tableContent + '<tr><td>' + key + '</td><td>' + value + '</td></tr>'
	}
	
	$( '.tileDescription > table' ).html( tableContent );
	$( '.tileDetails .caption' ).hide();
});
