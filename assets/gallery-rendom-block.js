( function ( blockEditor, blocks, components, element, i18n, serverSideRender ) {
	var createElement = element.createElement;
	var Fragment = element.Fragment;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ServerSideRender = serverSideRender.default || serverSideRender;
	var headingLevelOptions = [
		{ label: i18n.__( 'Heading 1', 'gallery-rendom' ), value: 1 },
		{ label: i18n.__( 'Heading 2', 'gallery-rendom' ), value: 2 },
		{ label: i18n.__( 'Heading 3', 'gallery-rendom' ), value: 3 },
		{ label: i18n.__( 'Heading 4', 'gallery-rendom' ), value: 4 },
		{ label: i18n.__( 'Heading 5', 'gallery-rendom' ), value: 5 },
		{ label: i18n.__( 'Heading 6', 'gallery-rendom' ), value: 6 },
	];

	blocks.registerBlockType( 'gallery-rendom/random-hero', {
		title: i18n.__( 'Gallery Rendom', 'gallery-rendom' ),
		description: i18n.__( 'Displays one random Gallery Rendom item.', 'gallery-rendom' ),
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
							title: i18n.__( 'Gallery Rendom Settings', 'gallery-rendom' ),
						},
						createElement( SelectControl, {
							label: i18n.__( 'Heading level', 'gallery-rendom' ),
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
					block: 'gallery-rendom/random-hero',
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
