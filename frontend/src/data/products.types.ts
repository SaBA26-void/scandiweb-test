import type { CartProduct } from "../components/cart/types";

export type ProductDetailsData = CartProduct & {
  inStock: boolean;
  category: string;
  descriptionHtml: string;
  gallery: string[];
};
