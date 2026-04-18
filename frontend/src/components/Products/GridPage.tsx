import GridItem from "./GridItem";
import type { ProductDetailsData } from "../../data/products";

type GridPageProps = {
  categoryName: string;
  isLoading: boolean;
  errorMessage: string;
  products: ProductDetailsData[];
  onAddToCart: (product: ProductDetailsData) => void | Promise<void>;
  onOpenProduct: (productId: string) => void;
};

const GridPage = ({
  categoryName,
  isLoading,
  errorMessage,
  products,
  onAddToCart,
  onOpenProduct,
}: GridPageProps) => {
  const heading = categoryName.charAt(0).toUpperCase() + categoryName.slice(1);

  return (
    <main className="flex flex-col items-center">
      {/* heading */}
      <header className="pt-[80px] mb-[103px] self-start ml-[250px]">
        <h1 className="font-raleway font-normal text-[42px] leading-[160%] tracking-[0px] text-[#1D1F22]">
          {heading}
        </h1>
      </header>

      {/* grid */}
      <section className="grid [grid-template-columns:repeat(3,minmax(0,400px))] gap-[40px] mb-[191px]">
        {isLoading && <p className="text-[18px] text-[#1D1F22]">Loading products...</p>}
        {errorMessage && <p className="text-[18px] text-red-600">{errorMessage}</p>}
        {products.map((product) => (
          <GridItem
            key={product.id}
            product={product}
            onAddToCart={onAddToCart}
            onOpenProduct={onOpenProduct}
          />
        ))}
      </section>
    </main>
  );
};

export default GridPage;
