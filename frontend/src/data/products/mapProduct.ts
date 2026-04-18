import type { ProductDetailsData } from "../products.types";
import type { GraphQLProduct } from "../graphql/productTypes";

export function mapProduct(product: GraphQLProduct): ProductDetailsData {
  const firstPrice = product.prices[0];
  const firstImage = product.gallery[0]?.url ?? "";

  return {
    id: product.id,
    name: product.name,
    imageUrl: firstImage,
    gallery: product.gallery.map((image) => image.url),
    price: firstPrice?.amount ?? 0,
    currencySymbol: firstPrice?.currency.symbol ?? "$",
    descriptionHtml: product.description,
    attributes: product.attributes,
    inStock: product.inStock,
    category: product.category,
  };
}
