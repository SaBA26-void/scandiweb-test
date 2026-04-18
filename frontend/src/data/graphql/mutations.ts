export type PlaceOrderPayload = {
  success: boolean;
  orderId: number | null;
  errors: string[];
};

export type PlaceOrderResponseData = {
  placeOrder: PlaceOrderPayload;
};

export const PLACE_ORDER_MUTATION = `
  mutation PlaceOrder($items: [CartLineInput!]!) {
    placeOrder(items: $items) {
      success
      orderId
      errors
    }
  }
`;
