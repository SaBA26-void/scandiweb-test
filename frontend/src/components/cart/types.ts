export type CartAttributeItem = {
  id: string;
  value: string;
  displayValue: string;
};

export type CartAttributeSet = {
  id: string;
  name: string;
  type: "text" | "swatch";
  items: CartAttributeItem[];
};

export type CartProduct = {
  id: string;
  name: string;
  imageUrl: string;
  price: number;
  currencySymbol: string;
  attributes: CartAttributeSet[];
};

export type CartSelections = Record<string, string>;

export type CartLine = {
  lineId: string;
  quantity: number;
  product: CartProduct;
  selections: CartSelections;
};
