type ProductGalleryProps = {
  productName: string;
  gallery: string[];
  activeThumb: number;
  onChangeThumb: (nextIndex: number) => void;
};

const ProductGallery = ({
  productName,
  gallery,
  activeThumb,
  onChangeThumb,
}: ProductGalleryProps) => {
  const activeImageUrl = gallery[activeThumb] ?? gallery[0] ?? "";

  const showPrevious = () => {
    if (gallery.length === 0) return;
    onChangeThumb((activeThumb - 1 + gallery.length) % gallery.length);
  };

  const showNext = () => {
    if (gallery.length === 0) return;
    onChangeThumb((activeThumb + 1) % gallery.length);
  };

  return (
    <figure
      data-testid="product-gallery"
      className="flex min-w-0 max-w-full items-start gap-10 overflow-x-hidden"
    >
      {/* Vertical scroll only when thumbnails exceed max height; no horizontal scroll */}
      <div className="flex max-h-[478px] w-[79px] min-w-0 shrink-0 flex-col gap-5 overflow-x-hidden overflow-y-auto">
        {gallery.map((src, index) => (
          <button
            key={src + index}
            type="button"
            onClick={() => onChangeThumb(index)}
            className={`box-border h-[80px] w-[79px] max-w-full shrink-0 overflow-hidden border transition-all ${
              activeThumb === index
                ? "border-black"
                : "border-transparent hover:border-neutral-300"
            }`}
            aria-label={`View image ${index + 1}`}
          >
            <img
              src={src}
              alt={`${productName} thumbnail ${index + 1}`}
              width={79}
              height={80}
              loading="lazy"
              className="h-full max-h-full w-full max-w-full object-cover"
            />
          </button>
        ))}
      </div>

      {/* Fixed height keeps prev/next arrows centered; image scales inside without shifting layout */}
      <div className="relative h-[478px] w-full max-w-[575px] min-w-0 shrink">
        <div className="flex h-full w-full items-center justify-center">
          <img
            src={activeImageUrl}
            alt={`${productName} main product image`}
            width={800}
            height={800}
            className="max-h-full max-w-full object-contain"
          />
        </div>
        <button
          type="button"
          onClick={showPrevious}
          aria-label="Previous image"
          className="absolute left-4 top-1/2 z-10 -translate-y-1/2 bg-neutral-800/90 py-2 px-4 text-white hover:bg-neutral-900"
        >
          {"<"}
        </button>
        <button
          type="button"
          onClick={showNext}
          aria-label="Next image"
          className="absolute right-4 top-1/2 z-10 -translate-y-1/2 bg-neutral-800/90 py-2 px-4 text-white hover:bg-neutral-900"
        >
          {">"}
        </button>
      </div>
    </figure>
  );
};

export default ProductGallery;
