type TextOption = {
  id: string;
  label: string;
};

type SwatchOption = {
  id: string;
  label: string;
  value: string;
};

function toKebabCase(value: string): string {
  return value
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

function toAttributeOptionToken(value: string): string {
  return value.trim().replace(/\s+/g, "-");
}

type AttributeSectionProps =
  | {
      title: string;
      type: "text";
      options: TextOption[];
      selectedId: string;
      onSelect: (id: string) => void;
      dataTestId?: string;
    }
  | {
      title: string;
      type: "swatch";
      options: SwatchOption[];
      selectedId: string;
      onSelect: (id: string) => void;
      dataTestId?: string;
    };

const legendClassName =
  "mb-[8px] font-roboto-condensed text-[18px] font-bold text-[#1D1F22]";

export default function AttributeSection(props: AttributeSectionProps) {
  if (props.type === "swatch") {
    const attributeKebab = toKebabCase(props.title);
    return (
      <fieldset className="mb-[27px]" data-testid={props.dataTestId}>
        <legend className={legendClassName}>{props.title}:</legend>
        <ul className="flex flex-wrap gap-[8px]">
          {props.options.map((option) => (
            <li key={option.id}>
              <button
                type="button"
                data-testid={`product-attribute-${attributeKebab}-${toAttributeOptionToken(option.value)}`}
                onClick={() => props.onSelect(option.id)}
                aria-label={option.label}
                className={`flex h-[36px] w-[36px] items-center justify-center border-2 transition-all ${
                  props.selectedId === option.id
                    ? "border-[#5ECE7B]"
                    : "border-transparent hover:border-neutral-300"
                }`}
              >
                <span
                  className="block h-full w-full"
                  style={{ backgroundColor: option.value }}
                />
              </button>
            </li>
          ))}
        </ul>
      </fieldset>
    );
  }

  return (
    <fieldset className="mb-[27px]" data-testid={props.dataTestId}>
      <legend className={legendClassName}>{props.title}:</legend>
      <ul className="flex flex-wrap gap-[12px]">
        {props.options.map((option) => (
          <li key={option.id}>
            <button
              type="button"
              data-testid={`product-attribute-${toKebabCase(props.title)}-${toAttributeOptionToken(option.label)}`}
              onClick={() => props.onSelect(option.id)}
              className={`flex items-center justify-center border px-[21px] py-[13px] text-[16px] font-source-sans-pro transition-colors ${
                props.selectedId === option.id
                  ? "border-[#1D1F22] bg-[#1D1F22] text-white"
                  : "border-[#1D1F22] bg-white text-[#1D1F22] hover:border-neutral-500"
              }`}
            >
              {option.label}
            </button>
          </li>
        ))}
      </ul>
    </fieldset>
  );
}
