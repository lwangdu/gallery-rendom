import { getContext, store, withSyncEvent } from '@wordpress/interactivity';

store( 'galleryRendom', {
	state: {
		get captionButtonLabel() {
			const context = getContext();

			return context.isCaptionOpen ? context.hideCaptionText : context.showCaptionText;
		},
	},
	actions: {
		toggleCaption() {
			const context = getContext();

			context.isCaptionOpen = ! context.isCaptionOpen;
		},
		closeCaptionOnEscape: withSyncEvent( ( event ) => {
			if ( event.key !== 'Escape' ) {
				return;
			}

			const context = getContext();

			if ( context.isCaptionOpen ) {
				context.isCaptionOpen = false;
			}
		} ),
	},
} );
