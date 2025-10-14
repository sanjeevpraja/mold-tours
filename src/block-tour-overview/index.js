const { registerBlockType } = wp.blocks;
const { useBlockProps, InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;
const { __ } = wp.i18n;

registerBlockType('mold/tour-overview', {
    title: __('Tour Overview', 'mold-tour'),
    description: __('Display trip overview information including category, location, grade, and custom overview items.', 'mold-tour'),
    icon: 'location',
    category: 'mold-tour',
    keywords: [
        __('trip', 'mold-tour'),
        __('overview', 'mold-tour'),
        __('tour', 'mold-tour'),
        __('details', 'mold-tour')
    ],
    attributes: {
        align: {
            type: 'string',
            default: 'full'
        },
        layout: {
            type: 'string',
            default: 'vertical'
        }
    },

    edit: function(props) {
        const blockProps = useBlockProps();

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Layout Settings', 'mold-tour')}>
                        <SelectControl
                            label={__('Layout', 'mold-tour')}
                            value={props.attributes.layout}
                            options={[
                                { label: __('Vertical List', 'mold-tour'), value: 'vertical' },
                                { label: __('Horizontal Grid', 'mold-tour'), value: 'horizontal' },
                            ]}
                            onChange={(layout) => props.setAttributes({ layout })}
                        />
                    </PanelBody>
                </InspectorControls>
                
                <div {...blockProps}>
                    <div className="mold-block-preview">
                        <div className="block-preview-header">
                            <h3>{__('Trip Overview', 'mold-tour')}</h3>
                            <span className="block-preview-badge">{__('Dynamic Block', 'mold-tour')}</span>
                        </div>
                        
                        <div className="block-preview-content">
                            <ul className="trip-overview-preview">
                                <li>
                                    <span className="icon-barcode"></span>
                                    <div className="detail">
                                        <div className="title">{__('Category', 'mold-tour')}</div>
                                        <div className="desc">{__('Adventure Tours', 'mold-tour')}</div>
                                    </div>
                                </li>
                                <li>
                                    <span className="icon-earth"></span>
                                    <div className="detail">
                                        <div className="title">{__('Location', 'mold-tour')}</div>
                                        <div className="desc">{__('Nepal, Everest', 'mold-tour')}</div>
                                    </div>
                                </li>
                                <li>
                                    <span className="icon-difficulty"></span>
                                    <div className="detail">
                                        <div className="title">{__('Grade', 'mold-tour')}</div>
                                        <div className="desc">{__('Moderate', 'mold-tour')}</div>
                                    </div>
                                </li>
                                <li>
                                    <span className="icon-calendar"></span>
                                    <div className="detail">
                                        <div className="title">{__('Duration', 'mold-tour')}</div>
                                        <div className="desc">15 Days</div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div className="block-preview-notice">
                            <em>{__('This block will display dynamic trip overview information based on the current product.', 'mold-tour')}</em>
                        </div>
                    </div>
                </div>
            </>
        );
    },

    save: function() {
        // Dynamic blocks don't need save function as they render on server
        return null;
    }
});