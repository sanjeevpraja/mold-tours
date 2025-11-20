import { registerBlockType } from '@wordpress/blocks';
import {useBlockProps, InnerBlocks} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './editor.scss';
import './style.scss'

// Allowed blocks for the inner blocks area
const ALLOWED_BLOCKS = [
    'core/heading',
    'core/paragraph',
    'core/buttons',
    'core/button',
    'core/columns',
    'core/group',
    'core/spacer',
    'core/separator',
    'core/query-title'
];


registerBlockType('mold/grade-image', {
    title: __('Grade Featured Container', 'mold-tour'),
    icon: 'archive',
    category: 'mold',
    attributes: {
        minHeight: {
            type: 'number',
            default: 400
        }
    },

    edit: ({ attributes, setAttributes }) => {
        const {
            minHeight,
        } = attributes;

        const blockProps = useBlockProps({
            className: `mold-grade-featured-container`
        });

            return (
                <div {...blockProps}>
                        <div  className="mold-featured-content" style={{
                            minHeight: `${minHeight}px`,
                        }}>
                            <InnerBlocks
                                allowedBlocks={ALLOWED_BLOCKS}
                                templateLock={false}
                            />
                        </div>
                </div>
            );
    },

    save: ({ attributes }) => {
        const blockProps = useBlockProps.save({
            className: `mold-grade-featured-container`
        });

        return (
            <div {...blockProps}>
                <div
                    className="mold-featured-background"
                    style={{ minHeight: `${attributes.minHeight}px` }}
                >
                    <InnerBlocks.Content />
                </div>
            </div>
        );
    }
});