import CartItem from "./CartItem";
import type { CartLine } from "./types";

type CartSectionProps = {
  lines: CartLine[];
  isPlacingOrder: boolean;
  onIncreaseLine: (lineId: string) => void;
  onDecreaseLine: (lineId: string) => void;
  onPlaceOrder: () => void;
};

const CartSection = ({
  lines,
  isPlacingOrder,
  onIncreaseLine,
  onDecreaseLine,
  onPlaceOrder,
}: CartSectionProps) => {
  const totalItemCount = lines.reduce((sum, line) => sum + line.quantity, 0);
  const cartTotalAmount = lines.reduce(
    (sum, line) => sum + line.quantity * line.product.price,
    0,
  );
  const currencySymbol = lines[0]?.product.currencySymbol ?? "$";

  return (
    <section className="flex w-full max-w-[325px] flex-col overflow-x-hidden">
      <div className="flex w-full flex-1 flex-col gap-6 px-4 pb-4 pt-6">
        <header>
          <h2 className="font-raleway text-[16px] font-[700] text-[#1D1F22]">
            My Bag,{" "}
            <span className="font-raleway text-[16px] font-[500] text-[#1D1F22]">
              {totalItemCount} {totalItemCount === 1 ? "Item" : "Items"}
            </span>
          </h2>
        </header>

        <ul className="flex max-h-[360px] flex-col gap-8 overflow-y-auto pr-1">
          {lines.map((line) => (
            <li key={line.lineId}>
              <CartItem
                line={line}
                onIncrease={() => onIncreaseLine(line.lineId)}
                onDecrease={() => onDecreaseLine(line.lineId)}
              />
            </li>
          ))}
        </ul>

        <dl className="flex items-center justify-between text-center">
          <dt className="font-raleway text-[16px] font-bold text-[#1D1F22]">
            Total
          </dt>
          <dd
            data-testid="cart-total"
            className="font-raleway text-[16px] font-[700] text-[#1D1F22]"
          >
            {currencySymbol}
            {cartTotalAmount.toFixed(2)}
          </dd>
        </dl>
      </div>

      <button
        type="button"
        disabled={lines.length === 0 || isPlacingOrder}
        onClick={onPlaceOrder}
        className={`mx-4 mb-4 py-[13px] text-[14px] text-center text-white transition-colors ${
          lines.length === 0 || isPlacingOrder
            ? "cursor-not-allowed bg-[#A6A6A6]"
            : "cursor-pointer bg-[#5ECE7B] hover:bg-[#4aa869]"
        }`}
      >
        {isPlacingOrder ? "PLACING..." : "PLACE ORDER"}
      </button>
    </section>
  );
};

export default CartSection;
