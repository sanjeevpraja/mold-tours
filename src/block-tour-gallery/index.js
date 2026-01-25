import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Placeholder, Spinner, PanelBody, ToggleControl, SelectControl, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import './editor.scss';
import './style.scss';


registerBlockType('mold/tour-gallery', {
    title: __('Tour Gallery', 'mold-tour'),
    icon: 'format-gallery',
    category: 'widgets',
    supports: { html: false },

    attributes: {
        speed: { type: 'number', default: 1000 },
        autoDelay: { type: 'number', default: 3000 },
        effect: { type: 'string', default: 'slide' },
        navigation: { type: 'boolean', default: true },
        pagination: { type: 'boolean', default: true },
        slidesPerView: { type: 'number', default: 1 },
        sliderGap: { type: 'number', default: 0 },
        sliderHeight: { type: 'number', default: 600 },
        equalHeight: { type: 'boolean', default: false },
        borderRadius: { type: 'number', default: 0 },
    },

    edit: ({ attributes, setAttributes }) => {
        const { speed, autoDelay, effect, navigation, pagination, slidesPerView, sliderGap, sliderHeight, equalHeight, borderRadius } = attributes;
        const blockProps = useBlockProps({ className: 'mold-tour-gallery-meta' });

        const galleryString = useSelect(
            (select) =>
                select('core/editor')
                    .getEditedPostAttribute('meta')?._tour_gallery || ''
        );

        const ids = galleryString
            ? galleryString.split(',').map((id) => parseInt(id, 10))
            : [];

        const images = useSelect(
            (select) => {
                if (!ids.length) return null;

                const records = select('core').getEntityRecords(
                    'postType',
                    'attachment',
                    { include: ids, per_page: ids.length }
                );

                if (!records) return null;

                // preserve order from meta
                return ids
                    .map((id) => records.find((img) => img.id === id))
                    .filter(Boolean);
            },
            [galleryString]
        );

        const [index, setIndex] = useState(0);

        useEffect(() => {
            if (slidesPerView > 1) {
                return;
            }
            if (!images || images.length <= 1) return;

            const timer = setInterval(() => {
                setIndex((prev) => (prev + 1) % images.length);
            }, autoDelay);

            return () => clearInterval(timer);
        }, [images, autoDelay, slidesPerView]);

        const stylesAttrs = {
            '--mold-border-radius': `${borderRadius}px`,
            '--mold-height': `${sliderHeight}px`,
            ...(slidesPerView > 1 && {
                display: 'grid',
                gridTemplateColumns: `repeat(${slidesPerView}, 1fr)`,
                gap: `${sliderGap}px`,
            }),
        };

        /* --------------------
         * EARLY RETURNS (FIX)
         * -------------------- */

        if (galleryString && !images) {
            return (
                <div {...blockProps}>
                    <Spinner />
                </div>
            );
        }

        if (!images || images.length === 0) {
            return (
                <div {...blockProps}>
                    <Placeholder
                        icon="format-gallery"
                        label={__('Tour Gallery', 'mold-tour')}
                        instructions={__(
                            'No images found. Add some in the Tour Gallery metabox.',
                            'mold-tour'
                        )}
                    />
                </div>
            );
        }

        const img = images[index];

        return (
            <>
                <InspectorControls>
                    <PanelBody title="Slider Settings" initialOpen>
                        <RangeControl
                            label="Border Radius"
                            value={borderRadius}
                            min={0}
                            max={100}
                            step={1}
                            onChange={(v) => setAttributes({ borderRadius: v })}
                        />
                        <RangeControl
                            label="Slides Per View"
                            value={slidesPerView}
                            min={1}
                            max={5}
                            step={1}
                            onChange={(v) => setAttributes({ slidesPerView: v })}
                        />
                        <RangeControl
                            label="Slider Gap"
                            value={sliderGap}
                            min={0}
                            max={100}
                            step={5}
                            onChange={(v) => setAttributes({ sliderGap: v })}
                        />
                        <ToggleControl
                            label="Equal Height"
                            checked={equalHeight}
                            onChange={(v) => setAttributes({ equalHeight: v })}
                        />
                        <RangeControl
                            label="Slider Height"
                            value={sliderHeight}
                            min={200}
                            max={1000}
                            step={50}
                            onChange={(v) => setAttributes({ sliderHeight: v })}
                        />
                        <RangeControl
                            label="Transition Speed (ms)"
                            value={speed}
                            min={100}
                            max={5000}
                            step={100}
                            onChange={(v) => setAttributes({ speed: v })}
                        />

                        <RangeControl
                            label="Autoplay Delay (ms)"
                            value={autoDelay}
                            min={1000}
                            max={10000}
                            step={500}
                            onChange={(v) => setAttributes({ autoDelay: v })}
                        />

                        <SelectControl
                            label="Effect"
                            value={effect}
                            options={[
                                { label: 'Slide', value: 'slide' },
                                { label: 'Fade', value: 'fade' },
                                { label: 'Coverflow', value: 'coverflow' },
                                { label: 'Creative', value: 'creative' },
                            ]}
                            onChange={(v) => setAttributes({ effect: v })}
                        />

                        <ToggleControl
                            label="Navigation"
                            checked={navigation}
                            onChange={(v) => setAttributes({ navigation: v })}
                        />

                        <ToggleControl
                            label="Pagination"
                            checked={pagination}
                            onChange={(v) => setAttributes({ pagination: v })}
                        />
                    </PanelBody>
                </InspectorControls>

                <div {...blockProps}>
                    <figure
                        className={`mold-tour-gallery-editor ${slidesPerView === 1 ? 'is-slider' : 'is-grid'
                            }`}
                        style={{
                            '--mold-border-radius': `${borderRadius}px`,
                            '--mold-height': `${sliderHeight}px`,
                            ...(slidesPerView > 1 && {
                                display: 'grid',
                                gridTemplateColumns: `repeat(${slidesPerView}, 1fr)`,
                                gap: `${sliderGap}px`,
                            }),
                        }}
                    >
                        {slidesPerView === 1 ? (
                            <img
                                src={
                                    images[0].media_details?.sizes?.large?.source_url ||
                                    images[0].source_url
                                }
                                alt=""
                            />
                        ) : (
                            images.map((img) => (
                                <img
                                    key={img.id}
                                    src={
                                        img.media_details?.sizes?.large?.source_url ||
                                        img.source_url
                                    }
                                    alt=""
                                />
                            ))
                        )}
                    </figure>
                </div>

            </>
        );
    },


    save: () => null,
});
