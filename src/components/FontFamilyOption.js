import { __ } from '@wordpress/i18n';
import { select } from '@wordpress/data';

/**
 * Get font family options for select controls
 * @return {Array} Array of font family options
 */
export const getFontFamilyOptions = () => {
  const { fontFamilies } = select('core/block-editor').getSettings()?.typography || {};
  const defaultOptions = [
    { label: __('Default', 'wp-mold'), value: '' }
  ];

  if (!fontFamilies || fontFamilies.length === 0) {
    return defaultOptions.concat([
      { 
        label: __('System Font', 'wp-mold'), 
        value: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif' 
      },
      { label: __('Arial', 'wp-mold'), value: 'Arial, sans-serif' },
      { label: __('Georgia', 'wp-mold'), value: 'Georgia, serif' },
    ]);
  }

  return defaultOptions.concat(
    fontFamilies.map((fontFamily) => ({
      label: fontFamily.name,
      value: fontFamily.fontFamily,
      slug: fontFamily.slug
    }))
  );
};