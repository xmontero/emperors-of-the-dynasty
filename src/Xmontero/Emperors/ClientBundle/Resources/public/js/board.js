// Tile details.

$( '.tile' ).click( function(){
	data = $( this ).children( '.object' );
	tile = data.data( 'tile' );
	$( '.tileDetails .tileName' ).text( 'Tile ' + tile.name );
});
