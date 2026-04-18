import { useEffect, useState } from "react";
import AttributeSection from "./parts/AttributeSection";
import ProductGallery from "./parts/ProductGallery";
import ProductDescription from "./parts/ProductDescription";
import type { CartSelections } from "../cart/types";
import type { ProductDetailsData } from "../../data/products";
import { hasAllRequiredSelections } from "../../data/products";

function toKebabCase(value: string): string {
  return value
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

type SingleProductProps = {
  product: ProductDetailsData;
  onBack: () => void;
  onAddToCart: (selections: CartSelections) => void;
};

const SingleProduct = ({ product, onBack, onAddToCart }: SingleProductProps) => {
  const [activeThumb, setActiveThumb] = useState(0);
  const [selections, setSelections] = useState<CartSelections>({});

  useEffect(() => {
    setSelections({});
    setActiveThumb(0);
  }, [product.id]);

  const canAddToCart = product.inStock && hasAllRequiredSelections(product, selections);
  const handleAttributeSelect = (attributeId: string, nextId: string) => {
    setSelections((prev) => ({
      ...prev,
      [String(attributeId)]: String(nextId),
    }));
  };

  return (
    <section className="mt-[80px] ml-[100px] mb-[232px] mr-[245px] md:px-16 lg:px-24">
      <button
        type="button"
        onClick={onBack}
        className="mb-6 border border-[#1D1F22] px-4 py-2 text-sm uppercase"
      >
        Back to products
      </button>
      <div className="mx-auto grid grid-cols-1 gap-10 lg:grid-cols-2">
        <ProductGallery
          productName={product.name}
          gallery={product.gallery}
          activeThumb={activeThumb}
          onChangeThumb={setActiveThumb}
        />

        <article className="flex flex-col ml-[109px] lg:pl-8">
          <h1 className="font-raleway font-semibold text-[30px] leading-[27px] text-[#1D1F22] mb-[32px]">
            {product.name}
          </h1>

          {product.attributes.map((attribute) => {
            const selectedId = selections[String(attribute.id)] ?? "";
            const dataTestId = `product-attribute-${toKebabCase(attribute.name)}`;

            if (attribute.type === "swatch") {
              return (
                <AttributeSection
                  key={attribute.id}
                  title={attribute.name.toUpperCase()}
                  type="swatch"
                  options={attribute.items.map((item) => ({
                    id: item.id,
                    label: item.displayValue,
                    value: item.value,
                  }))}
                  selectedId={selectedId}
                  dataTestId={dataTestId}
                  onSelect={(nextId) => handleAttributeSelect(attribute.id, nextId)}
                />
              );
            }

            return (
              <AttributeSection
                key={attribute.id}
                title={attribute.name.toUpperCase()}
                type="text"
                options={attribute.items.map((item) => ({
                  id: item.id,
                  label: item.displayValue,
                }))}
                selectedId={selectedId}
                dataTestId={dataTestId}
                onSelect={(nextId) => handleAttributeSelect(attribute.id, nextId)}
              />
            );
          })}

          <div className="flex flex-col gap-[10px] mb-[20px]">
            <p className="font-roboto-condensed font-bold text-[18px] leading-[18px] text-[#1D1F22]">
              PRICE:
            </p>
            <data
              value={product.price.toFixed(2)}
              className="font-raleway font-bold text-[24px] leading-[18px] text-[#1D1F22]"
            >
              {product.currencySymbol}
              {product.price.toFixed(2)}
            </data>
          </div>

          <button
            type="button"
            data-testid="add-to-cart"
            disabled={!canAddToCart}
            onClick={() => {
              if (!canAddToCart) return;
              onAddToCart(selections);
            }}
            className={`py-[16px] px-[93.5px] text-[16px] font-bold tracking-wider text-white transition-colors ${
              canAddToCart
                ? "bg-[#5ECE7B] hover:bg-[#4aa869]"
                : "cursor-not-allowed bg-[#A6A6A6]"
            }`}
          >
            {product.inStock ? "ADD TO CART" : "OUT OF STOCK"}
          </button>

          <ProductDescription html={product.descriptionHtml} />
        </article>
      </div>
    </section>
  );
};

export default SingleProduct;
