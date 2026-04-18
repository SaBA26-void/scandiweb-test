import "./App.css";
import { useEffect, useMemo, useState } from "react";
import Navbar from "./components/layout/Navbar";
import GridPage from "./components/Products/GridPage";
import CartOverlay from "./components/cart/CartOverlay";
import type { CartLine, CartSelections } from "./components/cart/types";
import SingleProduct from "./components/Products/SingleProduct";
import {
  fetchCategories,
  fetchProductById,
  fetchProducts,
  getProductById,
  placeOrder,
  type ProductDetailsData,
} from "./data/products";

const buildLineKey = (productId: string, selections: CartSelections) => {
  const selectionPart = Object.entries(selections)
    .sort(([a], [b]) => a.localeCompare(b))
    .map(([attributeId, valueId]) => `${attributeId}:${valueId}`)
    .join("|");

  return `${productId}|${selectionPart}`;
};

function App() {
  const [categories, setCategories] = useState<string[]>([]);
  const [activeCategory, setActiveCategory] = useState("all");
  const [products, setProducts] = useState<ProductDetailsData[]>([]);
  const [isLoadingProducts, setIsLoadingProducts] = useState(false);
  const [loadError, setLoadError] = useState("");
  const [isPlacingOrder, setIsPlacingOrder] = useState(false);
  const [isCartOpen, setIsCartOpen] = useState(false);
  const [activeProductId, setActiveProductId] = useState<string | null>(null);
  const [detailProduct, setDetailProduct] = useState<ProductDetailsData | null>(null);
  const [pdpLoading, setPdpLoading] = useState(false);
  const [lines, setLines] = useState<CartLine[]>([]);
  const listProduct = activeProductId ? getProductById(products, activeProductId) : null;

  useEffect(() => {
    if (!activeProductId) {
      setDetailProduct(null);
      setPdpLoading(false);
      return;
    }

    let cancelled = false;
    setDetailProduct(null);
    setPdpLoading(true);

    fetchProductById(activeProductId)
      .then((p) => {
        if (!cancelled) setDetailProduct(p);
      })
      .catch(() => {
        if (!cancelled) setDetailProduct(null);
      })
      .finally(() => {
        if (!cancelled) setPdpLoading(false);
      });

    return () => {
      cancelled = true;
    };
  }, [activeProductId]);

  const activeProduct = detailProduct ?? listProduct;

  useEffect(() => {
    const loadCategories = async () => {
      try {
        const nextCategories = await fetchCategories();
        if (nextCategories.length > 0) {
          setCategories(nextCategories);
          setActiveCategory(nextCategories[0]);
        }
      } catch {
        setLoadError("Could not load categories.");
      }
    };

    loadCategories();
  }, []);

  useEffect(() => {
    if (!activeCategory) return;

    const loadProducts = async () => {
      setIsLoadingProducts(true);
      setLoadError("");

      try {
        const nextProducts = await fetchProducts(activeCategory);
        setProducts(nextProducts);
      } catch {
        setLoadError("Could not load products.");
      } finally {
        setIsLoadingProducts(false);
      }
    };

    loadProducts();
  }, [activeCategory]);

  const cartItemCount = useMemo(
    () => lines.reduce((sum, line) => sum + line.quantity, 0),
    [lines]
  );

  const addProduct = (product: ProductDetailsData, selections: CartSelections) => {
    if (!product.inStock) return;

    setLines((prev) => {
      const key = buildLineKey(product.id, selections);
      const existingIndex = prev.findIndex(
        (line) => buildLineKey(line.product.id, line.selections) === key
      );

      if (existingIndex >= 0) {
        const next = [...prev];
        next[existingIndex] = {
          ...next[existingIndex],
          quantity: next[existingIndex].quantity + 1,
        };
        return next;
      }

      return [
        ...prev,
        {
          lineId: `line-${Date.now()}-${Math.random().toString(36).slice(2)}`,
          product,
          selections,
          quantity: 1,
        },
      ];
    });
  };

  const getDefaultSelections = (product: ProductDetailsData): CartSelections => {
    const defaults: CartSelections = {};

    product.attributes.forEach((attribute) => {
      const firstOption = attribute.items[0];
      if (firstOption) {
        defaults[String(attribute.id)] = String(firstOption.id);
      }
    });

    return defaults;
  };

  const increaseLine = (lineId: string) => {
    setLines((prev) =>
      prev.map((line) =>
        line.lineId === lineId ? { ...line, quantity: line.quantity + 1 } : line
      )
    );
  };

  const decreaseLine = (lineId: string) => {
    setLines((prev) =>
      prev.flatMap((line) => {
        if (line.lineId !== lineId) return [line];
        if (line.quantity === 1) return [];
        return [{ ...line, quantity: line.quantity - 1 }];
      })
    );
  };

  return (
    <div>
      <Navbar
        categories={categories}
        activeCategory={activeCategory}
        onCategoryChange={(nextCategory) => {
          setActiveProductId(null);
          setActiveCategory(nextCategory);
        }}
        onLogoClick={() => {
          setActiveProductId(null);
          const allCategory =
            categories.find((c) => c.toLowerCase() === "all") ??
            categories[0] ??
            "all";
          setActiveCategory(allCategory);
        }}
        cartItemCount={cartItemCount}
        onCartButtonClick={() => setIsCartOpen((prev) => !prev)}
      />
      {activeProductId && pdpLoading ? (
        <main className="flex min-h-[40vh] items-center justify-center font-raleway text-[#1D1F22]">
          Loading product…
        </main>
      ) : activeProductId && activeProduct ? (
        <SingleProduct
          key={activeProductId}
          product={activeProduct}
          onBack={() => setActiveProductId(null)}
          onAddToCart={(selections) => {
            addProduct(activeProduct, selections);
            setIsCartOpen(true);
          }}
        />
      ) : (
        <GridPage
          categoryName={activeCategory}
          isLoading={isLoadingProducts}
          errorMessage={loadError}
          products={products}
          onAddToCart={async (product) => {
            try {
              const fresh = await fetchProductById(product.id);
              const p = fresh ?? product;
              addProduct(p, getDefaultSelections(p));
            } catch {
              addProduct(product, getDefaultSelections(product));
            }
          }}
          onOpenProduct={setActiveProductId}
        />
      )}
      <CartOverlay
        isOpen={isCartOpen}
        lines={lines}
        isPlacingOrder={isPlacingOrder}
        onClose={() => setIsCartOpen(false)}
        onIncreaseLine={increaseLine}
        onDecreaseLine={decreaseLine}
        onPlaceOrder={async () => {
          if (lines.length === 0 || isPlacingOrder) return;

          try {
            setIsPlacingOrder(true);
            await placeOrder(lines);
            setLines([]);
            setIsCartOpen(false);
          } catch (error) {
            const message =
              error instanceof Error ? error.message : "Could not place order.";
            setLoadError(message);
          } finally {
            setIsPlacingOrder(false);
          }
        }}
      />
    </div>
  );
}

export default App;
