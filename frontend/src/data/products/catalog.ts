import { runQuery } from "../graphql/client";
import {
  CATEGORIES_QUERY,
  PRODUCT_BY_ID_QUERY,
  PRODUCTS_QUERY,
} from "../graphql/queries";
import type {
  CategoriesQueryData,
  ProductByIdQueryData,
  ProductsQueryData,
} from "../graphql/productTypes";
import type { ProductDetailsData } from "../products.types";
import { mapProduct } from "./mapProduct";

export async function fetchCategories(): Promise<string[]> {
  const data = await runQuery<CategoriesQueryData>(CATEGORIES_QUERY);
  return data.categories.map((category) => category.name);
}

export async function fetchProducts(category: string): Promise<ProductDetailsData[]> {
  const shouldPassCategory =
    category.trim() !== "" && category.toLowerCase() !== "all";

  const data = await runQuery<ProductsQueryData>(PRODUCTS_QUERY, {
    category: shouldPassCategory ? category : "",
  });

  return data.products.map(mapProduct);
}

export async function fetchProductById(
  id: string
): Promise<ProductDetailsData | null> {
  const data = await runQuery<ProductByIdQueryData>(PRODUCT_BY_ID_QUERY, { id });
  if (!data.product) return null;
  return mapProduct(data.product);
}
