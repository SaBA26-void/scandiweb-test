import type { CartLine, CartSelections } from "../../components/cart/types";
import type { ProductDetailsData } from "../products.types";
import { fetchProductById } from "../products/catalog";
import { runQuery } from "../graphql/client";
import { PLACE_ORDER_MUTATION, type PlaceOrderResponseData } from "../graphql/mutations";

function remapSelectionsForBackend(
  line: CartLine,
  fresh: ProductDetailsData
): CartSelections {
  const out: CartSelections = {};

  for (const attr of fresh.attributes) {
    if (attr.items.length === 0) continue;

    const key = String(attr.id);
    const rawPrev = line.selections[key];
    if (rawPrev === undefined || rawPrev === "") continue;

    const prevStr = String(rawPrev);

    if (attr.items.some((i) => String(i.id) === prevStr)) {
      out[key] = prevStr;
      continue;
    }

    const oldAttr = line.product.attributes.find((a) => String(a.id) === key);
    const oldItem = oldAttr?.items.find((i) => String(i.id) === prevStr);

    const match =
      oldItem &&
      attr.items.find(
        (i) =>
          i.displayValue === oldItem.displayValue || i.value === oldItem.value
      );

    if (match) {
      out[key] = String(match.id);
      continue;
    }

    if (attr.items[0]) {
      out[key] = String(attr.items[0].id);
    }
  }

  return out;
}

function selectedAttributeItemIdsForLine(line: CartLine): string[] {
  const ids: string[] = [];

  for (const attr of line.product.attributes) {
    if (attr.items.length === 0) continue;

    const key = String(attr.id);
    const raw = line.selections[key];
    if (raw === undefined || raw === "") continue;

    ids.push(String(raw));
  }

  return ids;
}

async function lineToCartInput(line: CartLine) {
  const fresh = await fetchProductById(line.product.id);
  const product = fresh ?? line.product;
  const selections = fresh ? remapSelectionsForBackend(line, fresh) : line.selections;

  const merged: CartLine = {
    ...line,
    product,
    selections,
  };

  return {
    productId: merged.product.id,
    quantity: merged.quantity,
    selectedAttributeItemIds: selectedAttributeItemIdsForLine(merged),
  };
}

export async function placeOrder(lines: CartLine[]): Promise<void> {
  const items = await Promise.all(lines.map((line) => lineToCartInput(line)));

  const data = await runQuery<PlaceOrderResponseData>(PLACE_ORDER_MUTATION, {
    items,
  });

  const result = data.placeOrder;
  if (!result.success) {
    const message =
      result.errors?.length > 0 ? result.errors.join("; ") : "Order could not be placed.";
    throw new Error(message);
  }
}
