import type { CartSelections } from "../components/cart/types";
import type { ProductDetailsData } from "./products.types";

export function getProductById(
  products: ProductDetailsData[],
  productId: string
): ProductDetailsData | null {
  return products.find((product) => product.id === productId) ?? null;
}

export function hasAllRequiredSelections(
  product: ProductDetailsData,
  selections: CartSelections
): boolean {
  return product.attributes.every(
    (attribute) =>
      attribute.items.length === 0 ||
      Boolean(selections[String(attribute.id)])
  );
}
