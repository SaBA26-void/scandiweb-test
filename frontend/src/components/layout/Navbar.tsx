import logo from "../../assets/logo/a-logo.svg";
import cartIcon from "../../assets/icons/Empty Cart.svg";
import { Link } from "react-router";

type NavbarProps = {
  categories: string[];
  activeCategory: string;
  onCategoryChange: (category: string) => void;
  onLogoClick: () => void;
  cartItemCount: number;
  onCartButtonClick: () => void;
};

const Navbar = ({
  categories,
  activeCategory,
  onCategoryChange,
  onLogoClick,
  cartItemCount,
  onCartButtonClick,
}: NavbarProps) => {
  return (
    <header className="mx-[101px] flex h-[80px] items-center px-[101px] pt-[24px]">
      <nav
        className="flex w-full items-center justify-between"
        aria-label="Main navigation"
      >
        <ul className="flex gap-6 pt-[32px] font-raleway text-[16px] leading-[120%] uppercase">
          {categories.map((category) => (
            <li
              key={category}
              data-testid={
                activeCategory === category
                  ? "active-category-link"
                  : "category-link"
              }
              className={`cursor-pointer text-center items-end pb-[32px] ${
                activeCategory === category
                  ? "text-[#5ECE7B] font-semibold border-b-2 border-[#5ECE7B]"
                  : "text-[#1D1F22] font-normal"
              }`}
            >
              <Link
                to={`/${encodeURIComponent(category)}`}
                onClick={() => onCategoryChange(category)}
              >
                {category}
              </Link>
            </li>
          ))}
        </ul>

        <Link
          to="/"
          onClick={onLogoClick}
          className="block border-0 bg-transparent p-0"
          aria-label="Go to all products"
        >
          <img
            src={logo}
            alt="Brand logo"
            className="h-[41px] w-[41px] cursor-pointer"
          />
        </Link>

        <button
          type="button"
          data-testid="cart-btn"
          aria-label="View cart"
          onClick={onCartButtonClick}
          className="relative"
        >
          <img
            src={cartIcon}
            alt="Cart icon"
            className="h-[20px] w-[20px] cursor-pointer"
          />
          {cartItemCount > 0 && (
            <span className="absolute -right-3 -top-2 flex h-[20px] min-w-[20px] items-center justify-center rounded-full bg-black px-1 text-[12px] font-bold text-white">
              {cartItemCount}
            </span>
          )}
        </button>
      </nav>
    </header>
  );
};

export default Navbar;
