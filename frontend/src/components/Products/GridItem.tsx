import cartIcon from "../../assets/icons/white-Cart-Icon-Empty.svg";
import type { ProductDetailsData } from "../../data/products";
import { useLocation, useNavigate } from "react-router";

type GridItemProps = {
  product: ProductDetailsData;
  onAddToCart: (product: ProductDetailsData) => void;
};

function toKebabCase(value: string): string {
  return value
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

const GridItem = ({ product, onAddToCart }: GridItemProps) => {
  const navigate = useNavigate();
  const location = useLocation();

  return (
    <article
      data-testid={`product-${toKebabCase(product.name)}`}
      className="group w-[386px] h-[444px] flex flex-col relative transition-shadow duration-300 
                 hover:shadow-[0px_4px_35px_0px_#A8ACB030]"
    >
      {/* cart icon (hidden until hover) */}
      {product.inStock && (
        <button
          type="button"
          onClick={() => onAddToCart(product)}
          className="absolute bottom-[75px] right-[36px] w-[52px] h-[52px] rounded-full bg-[#5ECE7B] 
                     flex items-center justify-center opacity-0 transition-opacity duration-300 
                     group-hover:opacity-100"
          aria-label={`Add ${product.name} to cart`}
        >
          <img src={cartIcon} className="w-6 h-6" alt="" />
        </button>
      )}

      {/* image */}
      <button
        type="button"
        onClick={() =>
          navigate(`/product/${encodeURIComponent(product.id)}`, {
            state: { from: location.pathname },
          })
        }
        className="pt-[16px] px-[16px] pb-[24px] text-left"
      >
        <img
          className={`h-[330px] w-[354px] object-contain bg-white ${product.inStock ? "" : "opacity-40"}`}
          src={product.imageUrl}
          alt={`${product.name} product image`}
        />
        {!product.inStock && (
          <p className="absolute inset-x-0 top-[170px] text-center font-raleway text-[24px] text-[#8D8F9A]">
            OUT OF STOCK
          </p>
        )}
      </button>

      {/* text */}
      <div className="flex flex-col items-start px-[16px] pb-[16px] gap-[5px]">
        <button
          type="button"
          onClick={() =>
            navigate(`/product/${encodeURIComponent(product.id)}`, {
              state: { from: location.pathname },
            })
          }
          className="font-raleway font-light text-[18px] leading-[160%] text-[#1D1F22]"
        >
          {product.name}
        </button>
        <p className="font-raleway font-normal text-[18px] leading-[160%] text-[#1D1F22]">
          {product.currencySymbol}
          {product.price.toFixed(2)}
        </p>
      </div>
    </article>
  );
};

export default GridItem;
