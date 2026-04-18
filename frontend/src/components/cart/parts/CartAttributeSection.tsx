type TextOption = {
  id: string;
  label: string;
};

type SwatchOption = {
  id: string;
  label: string;
  value: string;
};

type CartAttributeSectionProps =
  | {
      title: string;
      type: "text";
      options: TextOption[];
      selectedId: string;
      onSelect?: (id: string) => void;
      interactive?: boolean;
    }
  | {
      title: string;
      type: "swatch";
      options: SwatchOption[];
      selectedId: string;
      onSelect?: (id: string) => void;
      interactive?: boolean;
    };

function toKebabCase(value: string): string {
  return value
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

export default function CartAttributeSection(props: CartAttributeSectionProps) {
  const interactive = props.interactive ?? true;
  const attributeKebab = toKebabCase(props.title);

  if (props.type === "swatch") {
    return (
      <fieldset data-testid={`cart-item-attribute-${attributeKebab}`}>
        <legend className="font-Raleway text-[14px] text-weight-400 text-[#1D1F22]">
          {props.title}:
        </legend>
        <div className="w-full">
          <ul className="flex flex-wrap gap-[6px]">
            {props.options.map((option) => (
              <li key={option.id} className="shrink-0">
                {(() => {
                  const optionKebab = toKebabCase(option.label);
                  const testIdBase = `cart-item-attribute-${attributeKebab}-${optionKebab}`;
                  const finalTestId =
                    props.selectedId === option.id
                      ? `${testIdBase}-selected`
                      : testIdBase;

                  return (
                    <button
                      type="button"
                      data-testid={finalTestId}
                      onClick={() => props.onSelect?.(option.id)}
                      disabled={!interactive}
                      aria-label={option.label}
                      className={`flex h-[16px] w-[16px] items-center justify-center border-2 transition-all ${
                        props.selectedId === option.id
                          ? "border-[#5ECE7B]"
                          : "border-transparent"
                      }`}
                    >
                      <span
                        className="block h-full w-full"
                        style={{ backgroundColor: option.value }}
                      />
                    </button>
                  );
                })()}
              </li>
            ))}
          </ul>
        </div>
      </fieldset>
    );
  }

  return (
    <fieldset
      className="gap-[8px]"
      data-testid={`cart-item-attribute-${attributeKebab}`}
    >
      <legend className="font-Raleway text-[14px] text-weight-400 text-[#1D1F22]">
        {props.title}:
      </legend>
      <div className="w-full">
        <ul className="flex flex-wrap gap-[6px]">
          {props.options.map((option) => (
            <li key={option.id} className="shrink-0">
              {(() => {
                const optionKebab = toKebabCase(option.label);
                const testIdBase = `cart-item-attribute-${attributeKebab}-${optionKebab}`;
                const finalTestId =
                  props.selectedId === option.id
                    ? `${testIdBase}-selected`
                    : testIdBase;

                return (
                  <button
                    type="button"
                    data-testid={finalTestId}
                    onClick={() => props.onSelect?.(option.id)}
                    disabled={!interactive}
                    className={`flex items-center justify-center border px-[5px] py-[3px] text-[14px] font-source-sans-pro transition-colors ${
                      props.selectedId === option.id
                        ? "border-[#1D1F22] bg-[#1D1F22] text-white"
                        : "border-[#1D1F22] bg-white text-[#1D1F22]"
                    }`}
                  >
                    {option.label}
                  </button>
                );
              })()}
            </li>
          ))}
        </ul>
      </div>
    </fieldset>
  );
}
