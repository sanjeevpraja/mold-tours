import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';

import './style.scss';

registerBlockType(metadata.name, {
    edit: ({ attributes, setAttributes }) => {
        return (
          <div>
            <span class="location-value"><span class="material-symbols-outlined location-icon">location_on</span> Location</span>
          </div>
        );
    },
    save: () => null
});
