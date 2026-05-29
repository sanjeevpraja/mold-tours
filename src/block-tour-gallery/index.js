import { registerBlockType } from "@wordpress/blocks";
import { useSelect } from "@wordpress/data";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import {
  Placeholder,
  Spinner,
  PanelBody,
  ToggleControl,
  SelectControl,
  RangeControl,
} from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { useState, useEffect } from "@wordpress/element";
import ColorControl from "../components/ColorControl";
import "./editor.scss";
import "./style.scss";

registerBlockType("mold/tour-gallery", {
  title: __("Tour Gallery", "mold-tour"),
  icon: "format-gallery",
  supports: { html: false },

  attributes: {
    speed: { type: "number", default: 1000 },
    autoDelay: { type: "number", default: 3000 },
    effect: { type: "string", default: "slide" },
    navigation: { type: "boolean", default: true },
    pagination: { type: "boolean", default: true },
    slidesPerView: { type: "number", default: 1 },
    sliderGap: { type: "number", default: 0 },
    sliderHeight: { type: "number", default: 600 },
    equalHeight: { type: "boolean", default: false },
    borderRadius: { type: "number", default: 0 },
    displayType: { type: "string", default: "slider" },
    columns: { type: "number", default: 3 },
    columnsMobile: { type: "number", default: 1 },
    bgColor: { type: "string", default: "#f0f0f0" },
    enableLightbox: { type: "boolean", default: true },
  },

  edit: ({ attributes, setAttributes }) => {
    const {
      speed,
      autoDelay,
      effect,
      navigation,
      pagination,
      slidesPerView,
      sliderGap,
      sliderHeight,
      equalHeight,
      borderRadius,
      displayType,
      columns,
      columnsMobile,
      bgColor,
      enableLightbox,
    } = attributes;
    const blockProps = useBlockProps({ className: "mold-tour-gallery-meta" });

    const galleryString = useSelect(
      (select) =>
        select("core/editor").getEditedPostAttribute("meta")?._tour_gallery ||
        "",
    );

    const ids = galleryString
      ? galleryString.split(",").map((id) => parseInt(id, 10))
      : [];

    const images = useSelect(
      (select) => {
        if (!ids.length) return null;

        const records = select("core").getEntityRecords(
          "postType",
          "attachment",
          { include: ids, per_page: ids.length },
        );

        if (!records) return null;

        // preserve order from meta
        return ids
          .map((id) => records.find((img) => img.id === id))
          .filter(Boolean);
      },
      [galleryString],
    );

    const [index, setIndex] = useState(0);

    useEffect(() => {
      if (displayType !== "slider" || slidesPerView > 1) {
        return;
      }
      if (!images || images.length <= 1) return;

      const timer = setInterval(() => {
        setIndex((prev) => (prev + 1) % images.length);
      }, autoDelay);

      return () => clearInterval(timer);
    }, [images, autoDelay, slidesPerView, displayType]);

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
            label={__("Tour Gallery", "mold-tour")}
            instructions={__(
              "No images found. Add some in the Tour Gallery metabox.",
              "mold-tour",
            )}
          />
        </div>
      );
    }

    return (
      <>
        <InspectorControls>
          <PanelBody title="Gallery Settings" initialOpen>
            <SelectControl
              label="Display Type"
              value={displayType}
              options={[
                { label: "Slider", value: "slider" },
                { label: "Grid", value: "grid" },
              ]}
              onChange={(v) => setAttributes({ displayType: v })}
            />
            <ColorControl
              label="Skeleton Background Color"
              value={bgColor}
              onChange={(v) => setAttributes({ bgColor: v })}
            />
            <ToggleControl
              label="Enable Lightbox"
              checked={enableLightbox}
              onChange={(v) => setAttributes({ enableLightbox: v })}
            />
            <RangeControl
              label="Border Radius (px)"
              value={borderRadius}
              min={0}
              max={100}
              step={2}
              onChange={(v) => setAttributes({ borderRadius: v })}
            />
          </PanelBody>

          {displayType === "slider" && (
            <PanelBody title="Slider Settings" initialOpen={true}>
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
                  { label: "Slide", value: "slide" },
                  { label: "Fade", value: "fade" },
                  { label: "Coverflow", value: "coverflow" },
                  { label: "Creative", value: "creative" },
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
          )}

          {displayType === "grid" && (
            <PanelBody title="Grid Settings" initialOpen={true}>
              <RangeControl
                label="Columns (Desktop / Tablet)"
                value={columns}
                min={1}
                max={6}
                step={1}
                onChange={(v) => setAttributes({ columns: v })}
              />
              <RangeControl
                label="Columns (Mobile)"
                value={columnsMobile}
                min={1}
                max={3}
                step={1}
                onChange={(v) => setAttributes({ columnsMobile: v })}
              />
              <RangeControl
                label="Grid Gap"
                value={sliderGap}
                min={0}
                max={100}
                step={5}
                onChange={(v) => setAttributes({ sliderGap: v })}
              />
            </PanelBody>
          )}
        </InspectorControls>

        <div {...blockProps}>
          <figure
            className={`mold-tour-gallery-editor display-${displayType} ${
              displayType === "slider"
                ? slidesPerView === 1
                  ? "is-slider"
                  : "is-grid"
                : "is-grid-layout"
            }`}
            style={{
              "--mold-border-radius": `${borderRadius}px`,
              "--mold-height":
                displayType === "slider" ? `${sliderHeight}px` : "auto",
              "--mold-skeleton-bg": bgColor,
              ...(displayType === "slider" &&
                slidesPerView > 1 && {
                  display: "grid",
                  gridTemplateColumns: `repeat(${slidesPerView}, 1fr)`,
                  gap: `${sliderGap}px`,
                }),
              ...(displayType === "grid" && {
                display: "grid",
                gridTemplateColumns: `repeat(${columns}, 1fr)`,
                gap: `${sliderGap}px`,
              }),
            }}>
            {displayType === "slider" ? (
              slidesPerView === 1 ? (
                <img
                  style={{ backgroundColor: bgColor }}
                  src={
                    images[index]?.media_details?.sizes?.large?.source_url ||
                    images[index]?.source_url ||
                    images[0]?.media_details?.sizes?.large?.source_url ||
                    images[0]?.source_url
                  }
                  alt=""
                />
              ) : (
                images.map((img) => (
                  <img
                    key={img.id}
                    style={{ backgroundColor: bgColor }}
                    src={
                      img.media_details?.sizes?.large?.source_url ||
                      img.source_url
                    }
                    alt=""
                  />
                ))
              )
            ) : (
              images.map((img) => (
                <div
                  key={img.id}
                  className="mold-tour-gallery-grid-editor-item"
                  style={{
                    backgroundColor: bgColor,
                    borderRadius: `${borderRadius}px`,
                    overflow: "hidden",
                    aspectRatio: "4/3",
                  }}>
                  <img
                    src={
                      img.media_details?.sizes?.large?.source_url ||
                      img.source_url
                    }
                    alt=""
                    style={{
                      width: "100%",
                      height: "100%",
                      objectFit: "cover",
                    }}
                  />
                </div>
              ))
            )}
          </figure>
        </div>
      </>
    );
  },

  save: () => null,
});
