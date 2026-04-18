export type GraphQLAttributeItem = {
  id: string;
  value: string;
  displayValue: string;
};

export type GraphQLAttributeSet = {
  id: string;
  name: string;
  type: "text" | "swatch";
  items: GraphQLAttributeItem[];
};

export type GraphQLPrice = {
  amount: number;
  currency: {
    symbol: string;
  };
};

export type GraphQLProduct = {
  id: string;
  name: string;
  inStock: boolean;
  description: string;
  category: string;
  gallery: Array<{ url: string }>;
  prices: GraphQLPrice[];
  attributes: GraphQLAttributeSet[];
};

export type CategoriesQueryData = {
  categories: Array<{ name: string }>;
};

export type ProductsQueryData = {
  products: GraphQLProduct[];
};

export type ProductByIdQueryData = {
  product: GraphQLProduct | null;
};
