import { createElement, useMemo, type ReactNode } from "react";

type ProductDescriptionProps = {
  html: string;
};

const ALLOWED_TAGS = new Set([
  "p",
  "div",
  "span",
  "ul",
  "ol",
  "li",
  "strong",
  "em",
  "b",
  "i",
  "h1",
  "h2",
  "h3",
  "h4",
  "h5",
  "h6",
  "br",
  "a",
]);

function renderNode(node: Node, key: string): ReactNode {
  if (node.nodeType === Node.TEXT_NODE) {
    return node.textContent;
  }

  if (node.nodeType !== Node.ELEMENT_NODE) {
    return null;
  }

  const element = node as HTMLElement;
  const tagName = element.tagName.toLowerCase();
  if (!ALLOWED_TAGS.has(tagName)) {
    return null;
  }

  const children = Array.from(element.childNodes).map((child, index) =>
    renderNode(child, `${key}-${index}`)
  );

  if (tagName === "a") {
    const href = element.getAttribute("href") ?? "";
    const safeHref =
      href.startsWith("http://") || href.startsWith("https://") ? href : undefined;

    return (
      <a key={key} href={safeHref} target="_blank" rel="noreferrer">
        {children}
      </a>
    );
  }

  return createElement(tagName, { key }, children);
}

const ProductDescription = ({ html }: ProductDescriptionProps) => {
  const content = useMemo(() => {
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, "text/html");
    return Array.from(doc.body.childNodes).map((node, index) =>
      renderNode(node, `product-description-node-${index}`)
    );
  }, [html]);

  return (
    <section
      data-testid="product-description"
      className="mt-[40px] font-roboto font-normal text-[16px] leading-[160%] text-[#1D1F22]"
    >
      {content}
    </section>
  );
};

export default ProductDescription;
