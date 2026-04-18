import CartSection from "./CartSection";
import type { CartLine } from "./types";

const HEADER_TOP_OFFSET = "104px";

type CartOverlayProps = {
  isOpen: boolean;
  lines: CartLine[];
  isPlacingOrder: boolean;
  onClose: () => void;
  onIncreaseLine: (lineId: string) => void;
  onDecreaseLine: (lineId: string) => void;
  onPlaceOrder: () => void;
};

const CartOverlay = ({
  isOpen,
  lines,
  isPlacingOrder,
  onClose,
  onIncreaseLine,
  onDecreaseLine,
  onPlaceOrder,
}: CartOverlayProps) => {
  if (!isOpen) return null;

  return (
    <>
      <div
        role="presentation"
        className="fixed z-40 cursor-default bg-black/40"
        style={{
          left: 0,
          right: 0,
          bottom: 0,
          top: HEADER_TOP_OFFSET,
        }}
        onClick={onClose}
      />
      <aside
        data-testid="cart-overlay"
        className="fixed z-50 flex max-w-[325px] w-[100%] flex-col bg-white shadow-[-4px_0_24px_rgba(0,0,0,0.08)]"
        style={{
          right: "72px",
          top: HEADER_TOP_OFFSET,
        }}
        role="dialog"
        aria-modal="true"
        aria-label="Shopping cart"
      >
        <CartSection
          lines={lines}
          isPlacingOrder={isPlacingOrder}
          onIncreaseLine={onIncreaseLine}
          onDecreaseLine={onDecreaseLine}
          onPlaceOrder={onPlaceOrder}
        />
      </aside>
    </>
  );
};

export default CartOverlay;
