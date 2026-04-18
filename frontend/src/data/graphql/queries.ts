export const CATEGORIES_QUERY = `
  query Categories {
    categories {
      name
    }
  }
`;

export const PRODUCT_BY_ID_QUERY = `
  query Product($id: String!) {
    product(id: $id) {
      id
      name
      inStock
      description
      category
      gallery { url }
      prices {
        amount
        currency { symbol }
      }
      attributes {
        id
        name
        type
        items {
          id
          value
          displayValue
        }
      }
    }
  }
`;

export const PRODUCTS_QUERY = `
  query Products($category: String) {
    products(category: $category) {
      id
      name
      inStock
      description
      category
      gallery { url }
      prices {
        amount
        currency { symbol }
      }
      attributes {
        id
        name
        type
        items {
          id
          value
          displayValue
        }
      }
    }
  }
`;
