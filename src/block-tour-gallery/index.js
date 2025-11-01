import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';
import { Placeholder, Spinner, Notice } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './editor.scss';
import './style.scss';

registerBlockType('mold/tour-gallery', {
    title: __('Tour Gallery', 'mold-tour'),
    icon: 'format-gallery',
    category: 'widgets',
    supports: {
        html: false,
    },

    edit: () => {
        const blockProps = useBlockProps({ className: 'mold-tour-gallery-meta' });

        // Get current post ID
        const postId = useSelect((select) => select('core/editor').getCurrentPostId(), []);

        // Fetch the meta value (_tour_gallery)
        const meta = useSelect(
            (select) =>
                select('core/editor').getEditedPostAttribute('meta') || {},
            []
        );

        const galleryString = meta?._tour_gallery || '';
        const ids = galleryString ? galleryString.split(',').map((id) => parseInt(id, 10)) : [];

        // Fetch attachments for those IDs
        const images = useSelect(
            (select) =>
                ids.length
                    ? select('core').getEntityRecords('postType', 'attachment', {
                          include: ids,
                          per_page: ids.length,
                      })
                    : [],
            [galleryString]
        );

        if (galleryString && !images) {
            return (
                <div {...blockProps}>
                    <Spinner />
                </div>
            );
        }

        if (!galleryString || !images || images.length === 0) {
            return (
                <div {...blockProps}>
                    <Placeholder
                        icon="format-gallery"
                        label={__('Tour Gallery', 'mold-tour')}
                        instructions={__('No images found. Add some in the Tour Gallery metabox.', 'mold-tour')}
                    />
                </div>
            );
        }

        return (
            <div {...blockProps}>
                <div className="mold-gallery-grid">
                    {images.map((img) => (
                        <figure key={img.id} className="mold-gallery-item">
                            <img src={img.media_details.sizes?.thumbnail?.source_url || img.source_url} alt={img.alt_text || ''} />
                        </figure>
                    ))}
                </div>
            </div>
        );
    },

    save: () => null, // dynamic – rendered via PHP if needed
});
