import CartAttributeSection from "./parts/CartAttributeSection";
import type { CartLine } from "./types";

type CartItemProps = {
  line: CartLine;
  onIncrease: () => void;
  onDecrease: () => void;
};

const CartItem = ({ line, onIncrease, onDecrease }: CartItemProps) => {
  const mainImage = line.product.imageUrl;

  return (
    <article className="grid w-full grid-cols-[minmax(0,1fr)_24px_96px] gap-2 overflow-hidden">
      <section className="min-w-0 flex flex-col gap-[8px]">
        <header className="gap-[4px] text-[#1D1F22]">
          <h3 className="break-words font-raleway text-[18px] font-light">
            {line.product.name}
          </h3>
          <p className="font-raleway text-[16px] font-light">
            {line.product.currencySymbol}
            {line.product.price.toFixed(2)}
          </p>
        </header>

        {line.product.attributes.map((attribute) => {
          const selectedId = line.selections[String(attribute.id)] ?? "";
          if (attribute.type === "swatch") {
            const swatchOptions = attribute.items.map((item) => ({
              id: String(item.id),
              label: item.displayValue,
              value: item.value.startsWith("#") ? item.value : "#cccccc",
            }));

            return (
              <CartAttributeSection
                key={attribute.id}
                title={attribute.name}
                type="swatch"
                options={swatchOptions}
                selectedId={selectedId}
                interactive={false}
              />
            );
          }

          const textOptions = attribute.items.map((item) => ({
            id: String(item.id),
            label: item.displayValue,
          }));

          return (
            <CartAttributeSection
              key={attribute.id}
              title={attribute.name}
              type="text"
              options={textOptions}
              selectedId={selectedId}
              interactive={false}
            />
          );
        })}
      </section>

      <div className="flex h-[130px] flex-col items-center justify-between">
        <button
          type="button"
          data-testid="cart-item-amount-increase"
          onClick={onIncrease}
          className="w-[24px] h-[24px] text-[#1D1F22] text-[14px] border-[1px] border-[#1D1F22] text-center active:bg-[#1D1F22] active:text-white hover:bg-[#1D1F22] hover:text-white cursor-pointer"
        >
          +
        </button>
        <p className="font-raleway text-[14px] font-normal text-[#1D1F22] text-center">
          <span data-testid="cart-item-amount">{line.quantity}</span>
        </p>
        <button
          type="button"
          data-testid="cart-item-amount-decrease"
          onClick={onDecrease}
          className="w-[24px] h-[24px] text-[#1D1F22] text-[14px] border-[1px] border-[#1D1F22] text-center active:bg-[#1D1F22] active:text-white hover:bg-[#1D1F22] hover:text-white cursor-pointer"
        >
          -
        </button>
      </div>

      <figure className="mt-1 h-[130px] w-[96px] self-start overflow-hidden">
        <img
          src={mainImage}
          alt={`${line.product.name} product image`}
          className="h-full w-full object-cover"
        />
      </figure>
    </article>
  );
};

export default CartItem;
