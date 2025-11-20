import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';

import './style.scss';

registerBlockType(metadata.name, {
    edit: ({ attributes, setAttributes }) => {
        return (
          <div>
            <span class="grade-value"><span class="material-symbols-outlined grade-icon">speed</span> Grade</span>
          </div>
        );
    },
    save: () => null
});
