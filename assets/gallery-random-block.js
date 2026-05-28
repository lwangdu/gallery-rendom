( function ( blockEditor, blocks, components, element, i18n, serverSideRender ) {
	var createElement = element.createElement;
	var Fragment = element.Fragment;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ServerSideRender = serverSideRender.default || serverSideRender;
	var headingLevelOptions = [
		{ label: i18n.__( 'Heading 1', 'gallery-random' ), value: 1 },
		{ label: i18n.__( 'Heading 2', 'gallery-random' ), value: 2 },
		{ label: i18n.__( 'Heading 3', 'gallery-random' ), value: 3 },
		{ label: i18n.__( 'Heading 4', 'gallery-random' ), value: 4 },
		{ label: i18n.__( 'Heading 5', 'gallery-random' ), value: 5 },
		{ label: i18n.__( 'Heading 6', 'gallery-random' ), value: 6 },
	];

	blocks.registerBlockType( 'gallery-random/random-hero', {
		title: i18n.__( 'Gallery Random', 'gallery-random' ),
		description: i18n.__( 'Displays one random Gallery Random item.', 'gallery-random' ),
		category: 'widgets',
		icon: 'format-gallery',
		supports: {
			html: false,
		},
		attributes: {
			headingLevel: {
				type: 'number',
				default: 2,
			},
		},
		edit: function ( props ) {
			return createElement(
				Fragment,
				null,
				createElement(
					InspectorControls,
					null,
					createElement(
						PanelBody,
						{
							title: i18n.__( 'Gallery Random Settings', 'gallery-random' ),
						},
						createElement( SelectControl, {
							label: i18n.__( 'Heading level', 'gallery-random' ),
							value: props.attributes.headingLevel,
							options: headingLevelOptions,
							onChange: function ( value ) {
								props.setAttributes( {
									headingLevel: parseInt( value, 10 ),
								} );
							},
						} )
					)
				),
				createElement( ServerSideRender, {
					block: 'gallery-random/random-hero',
					attributes: props.attributes,
				} )
			);
		},
		save: function () {
			return null;
		},
	} );
}(
	window.wp.blockEditor,
	window.wp.blocks,
	window.wp.components,
	window.wp.element,
	window.wp.i18n,
	window.wp.serverSideRender
) );
