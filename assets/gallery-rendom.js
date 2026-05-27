( function () {
	function toggleCaption( button ) {
		var captionId = button.getAttribute( 'aria-controls' );
		var caption = document.getElementById( captionId );
		var label = button.querySelector( '.screen-reader-text' );

		if ( ! caption ) {
			return;
		}

		var expanded = button.getAttribute( 'aria-expanded' ) === 'true';
		button.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
		caption.hidden = expanded;

		if ( label ) {
			label.textContent = expanded ? 'Show image caption' : 'Hide image caption';
		}
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '.gallery-rendom__info' );

		if ( button ) {
			toggleCaption( button );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key !== 'Escape' ) {
			return;
		}

		var item = event.target.closest ? event.target.closest( '.gallery-rendom__item' ) : null;

		if ( ! item ) {
			return;
		}

		item.querySelectorAll( '.gallery-rendom__info[aria-expanded="true"]' ).forEach( function ( button ) {
			var caption = document.getElementById( button.getAttribute( 'aria-controls' ) );
			var label = button.querySelector( '.screen-reader-text' );

			button.setAttribute( 'aria-expanded', 'false' );

			if ( caption ) {
				caption.hidden = true;
			}

			if ( label ) {
				label.textContent = 'Show image caption';
			}
		} );
	} );
}() );
